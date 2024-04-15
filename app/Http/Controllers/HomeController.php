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
        return view('home');
    }

    public function detail_post($id)
    {
        return view('post.detail');
    }

    public function edit_post($id)
    {
        $post = Post::find($id);

        return view('post.edit', ['post' => $post]);
    }
}
