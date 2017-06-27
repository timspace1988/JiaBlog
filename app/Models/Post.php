<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Markdowner;
use App\Models\Tag;

use Carbon\Carbon;

class Post extends Model
{
    protected $dates = ['published_at'];

    protected $fillable = [
        'title', 'subtitle', 'content_raw', 'page_image', 'meta_description',
        'layout', 'is_draft', 'published_at',
    ];

    /**
     * Return the date portion of published_at
     * This will allow us to use $post->publish_date even though Post doesn't have this property
     */
    public function getPublishDateAttribute($value){
        return $this->published_at->format('M-j-Y');
    }

    /**
     * Return the time portion of published_at
     */
    public function getPublishTimeAttribute($value){
        return $this->published_at->format('g:i A');
    }

    /**
     * Alias for content_raw
     * When you call $post->content, this function will be automatically executed.
     * We do this because the Post's property and posts table column have been
     * changed to content_row and content_html, but we still want to use "conent"
     * in our view's input field, the following codes make it convenient to sitll
     * use $post->content but get correct values
     */
    public function getContentAttribute($value){
        return $this->content_raw;
    }

    /**
     * The many-to-many relationship between posts and tags.
     *
     *@return BelongsToMany
     */
    public function tags(){
        return $this->belongsToMany(Tag::class, 'post_tag_pivot');
    }

    /**
     * Set the title attributes and automatically the slug, just set these attributes for this Post instance, not saved in database yet
     * This will be automatically called whenever you assign a value to a Post instance's title property
     *@param string $value
     */
    public function setTitleAttribute($value){
        //the title attribute of this object
        $this->attributes['title'] = $value;

        //if a record respresented by this instance already exists in database, we have no need to do slug attribute setting for this object
        //Because this instance already existing only happended in the case we update a post, it should already have a unique slug when it was created
        if(!$this->exists){
            $this->setUniqueSLug($value, '');
        }
    }

    /**
     * Recursive routine to set a unique slug
     * This is prety useful as we use title words(http://xxxxx/word1-word2-word3) as part of url to show a specific blog
     * (if two articles has same title, the url will be title and title-1)
     *
     * @param string $title
     * @param string $extra
     */
    protected function setUniqueSlug($title, $extra){
        $slug = str_slug($title . '-' . $extra);//generate a url slug

        //ensure the slug value of this object(to be stored in database later) is unique
        if(static::whereSlug($slug)->exists()){
            $this->setUniqueSlug($title, (Integer)$extra + 1);
            return;
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Set the HTML content automaticaly when the raw content is set
     *
     * @param string $value
     */
    public function setContentRawAttribute($value){
        $markdown = new Markdowner();

        $this->attributes['content_raw'] = $value;
        $this->attributes['content_html'] = $markdown->toHTML($value);
    }

    /**
     * Synchronizes the tags with the post, adding new tags as needed
     *
     * @param array $tags
     */
    public function syncTags(array $tags){
        Tag::addNeededTags($tags);//this will store the newly entered tags in create/edit post form to database's tags table

        if(count($tags)){
            $this->tags()->sync(Tag::whereIn('tag', $tags)->lists('id')->all());
            return;
        }
        //sync(tags_array) will assign tags to post instance(old tags just gone), sync(tags_array, false) will assign new tags and keep old ones
        //but in this project, we want the new tags replace the old ones

        $this->tags()->detach();//if we just give an empty array, we just remove all tags from this post object
    }

    /**
     * Return URL (link) to post
     *
     * @param Tag $tag
     * @return string
     */
    public function url(Tag $tag=null){
        $url = route('blog.show', $this->slug);
        if($tag){
            $url .= '?tag='.urlencode($tag->tag);
        }

        return $url;
    }

    /**
     * Return array of tag links
     *
     * @param string $base
     * @return array
     */
    public function tagLinks(){
        $tags = $this->tags()->lists('tag');
        $return = [];
        foreach($tags as $tag){
            $url = route('blog.index', ['tag' => urlencode($tag)]);
            $return[] = '<a href="'.$url.'">'.e($tag).'</a>';
        }
        return $return;
    }

    /**
     * Return next post after this one or null
     *
     * @param Tag $tag
     * @return Post
     */
    public function newerPost(Tag $tag = null){
        $query = static::where('published_at', '>', $this->published_at)
          ->where('published_at', '<=', Carbon::now())//this conditon ensure that all posts set published in future will not displayed
          ->where('is_draft', 0)
          ->orderBy('published_at', 'asc');
        if($tag){
            $query = $query->whereHas('tags', function($q) use($tag){
                $q->where('tag', '=', $tag->tag);
            });
        }

        return $query->first();
    }

    /**
     * Return older post before this one or null
     *
     * @param Tag $tag
     * @return Post
     */
    public function olderPost(Tag $tag = null){
        $query = static::where('published_at', '<', $this->published_at)
          ->where('is_draft', 0)
          ->orderBy('published_at', 'desc');
        if($tag){
            $query = $query->whereHas('tags', function($q) use ($tag){
                $q->where('tag', '=', $tag->tag);
            });
        }

        return $query->first();
    }
}
