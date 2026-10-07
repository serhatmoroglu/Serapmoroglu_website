<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Treatment;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private function gallery(string $key): array
    {
        $v = json_decode((string) setting($key, '[]'), true);
        return is_array($v) ? $v : [];
    }

    public function home(Request $request)
    {
        // Eski WordPress adreslerini (?page_id=N) yeni adreslere taşı
        if ($id = (int) $request->query('page_id')) {
            $to = config("clinic.legacy_pages.$id");
            return redirect($to ?: '/', 301);
        }

        return view('pages.home', [
            'groups' => Treatment::published()->orderBy('sort')->get()->groupBy('group'),
            'posts' => Post::published()->kind('yazi')->orderBy('sort')->take(3)->get(),
            'gallery' => $this->gallery('home_images'),
        ]);
    }

    public function about()
    {
        return view('pages.about', ['images' => $this->gallery('about_images')]);
    }

    public function clinic()
    {
        return view('pages.clinic', ['images' => $this->gallery('klinik_images')]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function appointment()
    {
        return view('pages.appointment');
    }
}
