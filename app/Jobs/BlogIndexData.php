<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;

//This job will filter the posts with specified tag
class BlogIndexData extends Job implements SelfHandling
{
    protected $tag;


    /**
     * Create a new job instance.
     *
     * @param string|null $tag
     *
     * @return void
     */
    public function __construct($tag)
    {
        $this->tag = $tag;
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        //when a tag is given, output all posts with that  tag
        if ($this->tag){
            return $this->tagIndexData($this->tag);
        }

        //if no tag is passed, just return all posts without filtering on tag
        return $this->normalIndexData();
    }

    /**
     * Return data for normal index page(all posts without tag filtering)
     *
     * @return array
     */
    protected function normalIndexData(){
        $posts = Post::with('tags')//eager loading (this will get all posts and all related tags info, which helps with N+1 problem)
          ->where('published_at', '<=', Carbon::now())
          ->where('is_draft', 0)
          ->orderBy('published_at', 'desc')
          ->simplePaginate(config('blog.posts_per_page'));

        return [
            'title' => config('blog.title'),
            'subtitle' => config('blog.subtitle'),
            'posts' => $posts,
            'page_image' => config('blog.page_image'),
            'meta_description' => config('blog.description'),
            'reverse_direction' => false,
            'tag' => null,
        ];
    }

    /**
     * Return data for a tag index page
     *
     * @param string $tag
     *
     * @return array
     */
    protected function tagIndexData($tag){
        $tag = Tag::where('tag', $tag)->firstOrFail();
        $reverse_direction = (bool)$tag->reverse_direction;

        //whereHas() will make a query based on a relationship existence,
        //if another model in this relationship is Tag, the first param must be tags (means post where has tags with constrains)
        //Second param is a closure function used as constrains, it has only one param
        //No mater $q or $query is assigned, a Query will be injected. Inside the function are the constrains(description) on tags
        $posts = Post::where('published_at', '<=', Carbon::now())
          ->whereHas('tags', function($query) use ($tag){
              $query->where('tag', '=', $tag->tag);
          })
          ->where('is_draft', 0)
          ->orderBy('published_at', $reverse_direction ? 'asc' : 'desc')
          ->simplePaginate(config('blog.posts_per_page'));
        $posts->addQuery('tag', $tag->tag);
        //add a query string value to paginator, e.g. ?tag=xxx
        //fist param is key, second param is value

        $page_image = $tag->page_image ?: config('blog.page_image');

        return [
            'title' => $tag->title,
            'subtitle' => $tag->subtitle,
            'posts' => $posts,
            'page_image' => $page_image,
            'tag' => $tag,
            'reverse_direction' => $reverse_direction,
            'meta_description' => $tag->meta_description ?: config('blog.description'),
        ];
    }
}
