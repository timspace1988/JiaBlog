<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Post;
use App\Models\Tag;
use App\Jobs\BlogIndexData;
use Carbon\Carbon;

use App\Services\RssFeed;
use App\Services\SiteMap;

class BlogController extends Controller
{
    //get the index of posts, if a tag is given by request, we apply different index template according to tag
    //we ask Tag class for template, if tag is not given, we apply the default one
    public function index(Request $request){
        // $posts = Post::where('published_at', '<=', Carbon::now())//if we set post published in future, it will not be displayed for other user until then
        //                ->orderBy('published_at', 'desc')
        //                ->paginate(config('blog.posts_per_page'));
        //
        // return view('blog.index', compact('posts'));

        $tag = $request->get('tag');
        $data = $this->dispatch(new BlogIndexData($tag));
        $layout = $tag ? Tag::layout($tag) : 'blog.layouts.index';
        return view($layout, $data);
    }

    //display specified blog page
    //Note: if find this blog through a tag passed in querying string(the index list with a particular tag)
    //we will pass the tag data to view so that we can build previous and next link under index with this tag
    public function showPost($slug, Request $request){
        //with() eager loading helps with n+1 issue
        $post = Post::with('tags')->whereSlug($slug)->firstOrFail();
        $tag = $request->get('tag');
        if($tag){
            $tag = Tag::whereTag($tag)->firstOrFail();
        }

        return view($post->layout, compact('post', 'tag', 'slug'));//withPost($post) will pass the varible $post to blog\post.blade.php
    }

    //show RSS feed
    public function rss(RssFeed $feed){
        $rss = $feed->getRSS();

        return response($rss)->header('Content-type', 'application/rss+xml');
        //response($content)->header(), $content is the content displayed on destination page
    }

    //show Site Map
    public function siteMap(SiteMap $siteMap){
        $map = $siteMap->getSiteMap();

        return response($map)->header('Content-type', 'text/xml');
    }
}
