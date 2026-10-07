<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('pages.posts', ['posts' => Post::published()->kind('yazi')->orderBy('sort')->get()]);
    }

    public function show(Post $post)
    {
        abort_unless($post->published && $post->kind === 'yazi', 404);
        return view('pages.post', [
            'post' => $post,
            'more' => Post::published()->kind('yazi')->where('id', '!=', $post->id)->orderBy('sort')->take(3)->get(),
        ]);
    }

    public function press()
    {
        return view('pages.press', [
            'news' => Post::published()->kind('gazete')->orderBy('sort')->get(),
            'videos' => Post::published()->kind('video')->orderBy('sort')->get(),
        ]);
    }
}
