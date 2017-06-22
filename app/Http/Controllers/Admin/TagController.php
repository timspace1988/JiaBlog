<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tag;
use App\Http\Requests\TagCreateRequest;
use App\Http\Requests\TagUpdateRequest;

class TagController extends Controller
{
    //field properties
    protected $fields = [
        'tag' => '',
        'title' => '',
        'subtitle' => '',
        'meta_description' => '',
        'page_image' => '',
        'layout' => 'blog.layouts.index',
        'reverse_direction' => 0,
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tag.index')->withTags($tags);//withTag($tags)will pass variable $tags to view-page
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default){
            $data[$field] = old($field, $default);
        }

        return view('admin.tag.create', $data);
        //in view, each content in data[] will be a variable e.g. $tag, if use compact('data'), $data will be passed to view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TagCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    //Inject a TagCreateRequest instance, which will apply the validation on request's data before executing the function
    public function store(TagCreateRequest $request)
    {
        $tag = new Tag();
        foreach(array_keys($this->fields) as $field){
            $tag->$field = $request->get($field);
        }
        $tag->save();

        return redirect()->route('admin.tag.index')->withSuccess("The tag '$tag->tag' was created.");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $data = ['id' => $id];
        foreach (array_keys($this->fields) as $field){
            $data[$field] = old($field, $tag->$field);//display previously entered input. if not exits, display the original tag's data
        }

        return view('admin.tag.edit', $data);
    }

    /**
     * Update the specified tag in storage.
     *
     * @param  TagUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagUpdateRequest $request, $id)
    {

        $tag = Tag::findOrFail($id);

        foreach (array_keys(array_except($this->fields, ['tag'])) as $field){
            $tag->$field = $request->get($field);
        }
        $tag->save();

        return redirect()->route('admin.tag.edit', $id)->withSuccess("Changes saved.");
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.tag.index')->withSuccess("The '$tag->tag' tag has been deleted.");
    }
}
