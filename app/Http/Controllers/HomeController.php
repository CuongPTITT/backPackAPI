<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Doctrine\DBAL\Logging\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $posts = Post::paginate(10);

        return view('home', ['posts' => $posts]);
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
