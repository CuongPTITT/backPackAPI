<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Doctrine\DBAL\Logging\Middleware;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $posts = Post::paginate(10);

        return view('home', ['posts' => $posts]);
    }

    public function update_post(Request $request)
    {
        $post_id = $request->input('post_id');

        $post = Post::find($post_id);

        if ($request->has('title')) {
            $post->title = $request->input('title');
        }

        if ($request->has('description')) {
            $post->description = $request->input('description');
        }

        if ($request->hasFile('image')) {
            $post->image = $request->file('image');
        }

        if ($request->has('status')) {
            $post->status = $request->input('status');
        }

        $post->save();

        return response()->json(['success' => true]);
    }

    public function detail_post($id)
    {
        $post = Post::find($id);

        return view('post.detail', ['post' => $post]);
    }

    public function edit_post($id)
    {
        $post = Post::find($id);

        return view('post.edit', ['post' => $post]);
    }
}
