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

    public function list_post()
    {
        $posts = Post::paginate(10);

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $posts,
        ]);
    }

    public function detail_post($id)
    {
        $post = Post::find($id);

        return view('post.detail', ['post' => $post]);
    }
}
