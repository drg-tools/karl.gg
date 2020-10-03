<?php

namespace App\Http\Controllers;

use App\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()->paginate();

        return view('posts.index')->withPosts($posts);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        return view('posts.show')->withPost($post);
    }
}
