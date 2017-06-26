<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Markdowner;
use App\Models\Tag;

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
     *
     *@param string $value
     */
    public function setTitleAttribute($value){
        //the title attribute of this object
        $this->attributes['title'] = $value;

        //if a record respresented by this instance already exists in database, we will not do slug attribute setting for this object
        if(!$this->exists){
            $this->setUniqueSLug($value, '');
        }
    }

    /**
     * Recursive routine to set a unique slug
     *
     * @param string $title
     * @param string $extra
     */
    protected function setUniqueSlug($title, $extra){
        $slug = str_slug($title . '-' . $extra);//generate a url slug

        //ensure the slug value of this object(to be stored in database later) is unique
        if(static::whereSlug($slug)->exists()){
            $this->setUniqueSlug($title, $extra + 1);
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
}
