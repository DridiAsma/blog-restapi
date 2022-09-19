<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commantaire;
use App\Models\Post;

class CommantaireController extends Controller
{
    public function index($id){
        $post = Post::find($id);
        if(!$post)
        {
            return response([
                'message' => 'post not found'
            ], 403);

        }

        return response([
            'comments' => $post->comments()->with('user:id,name,image')->get()
        ], 200);
    }

// create
    public function store(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response([
                'message' => 'Post not found'
            ], 403);
        }
        //validate fields
        $valide = $request->validate([
            'comment' => 'required|string'
        ]);

        Commantaire::create([
            'comment' => $valide['comment'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'comment created'
        ], 200);
    }

    //update
    public function update(Request $request, $id)
    {
        $comment = Commantaire::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update([
            'comment' => $attrs['comment']
        ]);

        return response([
            'message' => 'Comment updated.'
        ], 200);
    }

    // delete a comment
    public function destroy($id)
    {
        $comment = Commantaire::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $comment->delete();

        return response([
            'message' => 'Comment deleted.'
        ], 200);
    }


}
