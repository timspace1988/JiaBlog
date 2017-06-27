<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\tag;

class PostTableSeeder extends Seeder
{
    /**
     * Seed the posts table
     *
     * @return void
     */
    public function run()
    {
        //pull all the tag names from the file
        $tags = Tag::lists('tag')->all();

        Post::truncate();//truncate(remove all) existing records in posts table

        //Don't forget to truncate the pivot table
        DB::table('post_tag_pivot')->truncate();

        //create 20 fake posts data records(actually call post model factory 20 times)
        factory(Post::class, 20)->create()->each(function($post) use($tags){
            //30% of time don't assign a tag
            if(mt_rand(1, 100) <= 30){
                return;
            }

            shuffle($tags);
            $postTags = [$tags[0]];
            //30% of time we are assigning tags, assign 2
            if(mt_rand(1, 100) <= 30){
                $postTags[] = $tags[1];
            }

            $post->syncTags($postTags);
        });
    }
}
