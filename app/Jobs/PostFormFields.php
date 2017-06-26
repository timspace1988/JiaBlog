<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;

class PostFormFields extends Job implements SelfHandling
{
    /**
     * The id (if any) of the post row
     *
     * @var integer
     */
    protected $id;

    /**
     * List of the fields and default value for each field
     *
     * @var array
     */
    protected $fieldList = [
        'title' => '',
        'subtitle' => '',
        'page_image' => '',
        'content' => '',
        'meta_description' => '',
        'is_draft' => '0',
        'publish_date' => '',
        'publish_time' => '',
        'layout' => 'blog.layouts.post',
        'tags' => [],
    ];

    /**
     * Create a new job(command) instance.
     *
     * @param integer $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Execute the job(command).
     * This job will return an array of fields and values to use to populate a form
     * Whenever this job is dispatched, the following function will be executed
     * and the output will be returned as the result of ->dispatch(a job).
     *
     * @return array of fieldnames => values
     */
    public function handle()
    {
        $fields = $this->fieldList;

        if($this->id){
            $fields = $this->fieldsFromModel($this->id, $fields);
        }else{
            $when = Carbon::now()->addHour();//add one hour, this means the article is defaultly set published in one hour later
            $fields['publish_date'] = $when->format('M-j-Y');
            $fields['publish_time'] = $when->format('g:i A');
        }

        foreach($fields as $fieldName => $fieldValue){
            $fields[$fieldName] = old($fieldName, $fieldValue);
            //the old() function will get the old value from a session, second param is the default value if the sepecific field name is not existing
        }

        return array_merge($fields, ['allTags' => Tag::lists('tag')->all()]);
    }

    /**
     * Return the field values from the model
     *
     * @param integer $id
     * @param array $fields
     * @return array
     */
     protected function fieldsFromModel($id, array $fields){
         $post = Post::findOrFail($id);

         $fieldNames = array_keys(array_except($fields, ['tags']));

         $fields = ['id' => $id];
         foreach($fieldNames as $fieldName){
             $fields[$fieldName] = $post->{$fieldName};
             //here we want to get a property of post object, the propery name is the result of execution of {$fieldName}
         }

         $fields['tags'] = $post->tags()->lists('tag')->all();

         return $fields;
     }
}
