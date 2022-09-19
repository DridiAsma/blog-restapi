<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //get all
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')->
            with('user:id,name,image')->withCount('comments', 'likes')
            ->with('likes', function($like){
                return $like->where('user_id', auth()->user()->id)
                ->select('id', 'user_id', 'post_id')->get();
            })->get()
        ], 200);
    }

 //get single post
 public function show($id)
 {
    return response([
        'post' => Post::where('id', $id)->
        withCount('comments', 'likes')->get()
    ], 200);
 }


 //create posts
 public function store(Request $request){

    $valide = $request->validate([
        'body' => 'required|string'
    ]);
    $image = $this->saveImage($request->image, 'posts');

    $post = Post::create([
        'body' => $valide['body'],
        'user_id' => auth()->user()->id,
        'image' => $image
    ]);

    return response(['message' => 'Post created', 'post' => $post]);
 }



 public function update(Request $request, $id)
 {
    $post = Post::find($id);
    if(!$post)
    {
        return response([
            'message' => 'Post not found'
        ], 403);
    }

    //validate fields
    $valide = $request->validate([
        'body' => 'required|string'
    ]);

    //for now skip for post image
    return response([
        'message' => 'post Update',
        'post' => $post
    ], 200);

 }

 public function delete($id)
 {
    $post = Post::find($id);
    if(!$post)
    {
    return response(['message' => 'post not foind.'], 403);
    }
    if($post->user_id != auth()->user()->id)
    {
        return response([
            'message' => 'Permission denied.'
        ], 403);
    }

    $post->comments()->delete();
    $post->likes()->delete();
    $post->delete();

    return response([
        'message' => 'Post deleted.'
    ], 200);
 }

}
