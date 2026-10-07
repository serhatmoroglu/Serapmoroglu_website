<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(string $kind)
    {
        return view('admin.posts.index', ['kind' => $kind, 'items' => Post::where('kind', $kind)->orderBy('sort')->get()]);
    }

    public function create(string $kind)
    {
        return view('admin.posts.form', ['kind' => $kind, 'item' => new Post(['kind' => $kind, 'published' => true, 'sort' => (int) Post::where('kind', $kind)->max('sort') + 1])]);
    }

    public function store(Request $request, string $kind)
    {
        return $this->save($request, new Post(['kind' => $kind]), 'Kaydedildi.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', ['kind' => $post->kind, 'item' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        return $this->save($request, $post, 'Güncellendi.');
    }

    public function destroy(Post $post)
    {
        Uploads::forget($post->image);
        $kind = $post->kind;
        $post->delete();
        return redirect()->route('admin.posts.index', $kind)->with('ok', 'Silindi.');
    }

    private function save(Request $request, Post $item, string $msg)
    {
        $kind = $item->kind;
        $data = $request->validate([
            'title' => ['required', 'string', 'max:220'],
            'slug' => ['nullable', 'string', 'max:200', 'regex:/^[a-z0-9-]+$/', Rule::unique('posts', 'slug')->ignore($item->id)],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'body' => ['nullable', 'string'],
            'external_url' => [$kind === 'gazete' ? 'required' : 'nullable', 'url', 'max:500'],
            'video_url' => [$kind === 'video' ? 'required' : 'nullable', 'url', 'max:500'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
        ], ['slug.regex' => 'Adres yalnızca küçük harf, rakam ve tire içerebilir.',
            'external_url.required' => 'Haberin bağlantısı zorunludur.', 'video_url.required' => 'Video bağlantısı zorunludur.']);

        $data['slug'] = $data['slug'] ?: Str::limit(Str::slug(Str::ascii($data['title'])), 70, '');
        if ($item->exists === false && \App\Models\Post::where('slug', $data['slug'])->exists()) {
            $data['slug'] .= '-'.Str::lower(Str::random(4));
        }
        $data['sort'] = $data['sort'] ?? 0;
        $data['published'] = $request->boolean('published');
        unset($data['image_file']);

        if ($request->hasFile('image_file')) {
            Uploads::forget($item->image);
            $data['image'] = Uploads::image($request->file('image_file'));
        } elseif ($request->boolean('remove_image')) {
            Uploads::forget($item->image);
            $data['image'] = null;
        }
        $item->fill($data)->save();
        return redirect()->route('admin.posts.edit', $item)->with('ok', $msg);
    }
}
