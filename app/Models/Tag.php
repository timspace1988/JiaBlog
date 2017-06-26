<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Tag extends Model
{
    protected $fillable = [
        'tag', 'title', 'subtitle', 'page_image', 'meta_description', 'reverse_direction',
    ];
    //$fillable = [] determine which column could be filled with an array (Mass Assignment Protection),
    // e.g. addNeededTags function heavily depends on this

    /**
     * The many-to-many relationship between tags and posts.
     * @return BelongsToMany
     */
     public function posts(){
         return $this->belongsToMany(Post::class, 'post_tag_pivot');
     }

     /**
      * Add a list of tags to tags table in database (Mass Assignment)
      *
      *@param array $tags LIst of tags to check/add
      */
     public static function addNeededTags(array $tags){
         if(count($tags) === 0){
             return;
         }

         $found = static::whereIn('tag', $tags)->lists('tag')->all();
         //check which tags in list already exist in tags table, then get all these existing tags' tag value

         foreach(array_diff($tags, $found) as $tag){
             static::create([
                 'tag' => $tag,
                 'title' => $tag,
                 'subtitle' => 'Subtitle for ' . $tag,
                 'page_image' => '',
                 'meta_description' => '',
                 'reverse_direction' => false,
             ]);
         }
     }
}
