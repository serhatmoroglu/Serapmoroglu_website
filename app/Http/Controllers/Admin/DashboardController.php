<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Treatment;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'new' => Lead::where('status', 'yeni')->count(),
            'total' => Lead::count(),
            'week' => Lead::where('created_at', '>=', now()->subDays(7))->count(),
            'latest' => Lead::latest()->take(8)->get(),
            'treatments' => Treatment::count(),
            'posts' => Post::where('kind', 'yazi')->count(),
            'smtpOk' => trim((string) setting('smtp_host')) !== '',
            'mailFailed' => Lead::where('mail_sent', false)->where('created_at', '>=', now()->subDays(14))->count(),
        ]);
    }
}
