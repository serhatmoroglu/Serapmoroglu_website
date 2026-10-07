<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Treatment;

class SeoController extends Controller
{
    public function sitemap()
    {
        $urls = collect(['/', '/tedaviler', '/yazilar', '/basinda-biz', '/klinik', '/hakkimizda', '/iletisim', '/randevu'])
            ->merge(Treatment::published()->pluck('slug')->map(fn ($s) => "/tedaviler/$s"))
            ->merge(Post::published()->kind('yazi')->pluck('slug')->map(fn ($s) => "/yazilar/$s"))
            ->map(fn ($u) => '<url><loc>'.e(url($u)).'</loc></url>')->implode('');
        return response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$urls.'</urlset>', 200, ['Content-Type' => 'application/xml']);
    }

    public function robots()
    {
        return response("User-agent: *\nDisallow: /yonetim\nSitemap: ".url('/sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain']);
    }
}
