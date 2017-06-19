<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $dates = ['published_at'];

    public function setTitleAttribute($value){
        //the title attribute of this object
        $this->attributes['title'] = $value;

        //check if this object(the instance of Post with 'title' updated) has been saved to database, if not,
        //generate a url slug and set it to 'slut' atribute
        if(!$this->exists){
            $this->attributes['slug'] = str_slug($value);
        }
    }
}
