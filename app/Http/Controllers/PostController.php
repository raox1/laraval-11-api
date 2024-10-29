<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function addNewPost(Request $request) {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = auth()->user()->id;
            $post->save();

            return response()->json([
                'message' => 'post added successfully',
                'post_id' => $post->id
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 403);
        }
    }

    // edit a post
    public function editPost(Request $request) {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'post_id' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            $post_data = Post::find($request->post_id);

            $updatedPost = $post_data->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'message' => 'post updated successfully',
                'updated post' => $updatedPost,
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 403);
        }
    }


        // edit a post approach 2
        public function editPost2(Request $request, $post_id) {
            $validated = Validator::make($request->all(), [
                'title' => 'required|string',
                'content' => 'required|string',
            ]);
    
            if ($validated->fails()) {
                return response()->json($validated->errors(), 403);
            }
    
            try {
                $post_data = Post::find($request->post_id);
    
                $updatedPost = $post_data->update([
                    'title' => $request->title,
                    'content' => $request->content,
                ]);
    
                return response()->json([
                    'message' => 'post updated successfully',
                    'updated post' => $updatedPost,
                ], 200);
            } catch(\Throwable $th) {
                return response()->json([
                    'error' => $th->getMessage()
                ], 403);
            }
        }


    // retrieve all posts
    public function getAllPosts() {

        try {
            $posts = Post::all();
            return response()->json([
                'posts' => $posts
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 403);
        }
    }
}
