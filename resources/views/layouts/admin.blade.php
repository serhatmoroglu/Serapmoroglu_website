<!doctype html>
<html lang="tr"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>@yield('title', 'Yönetim') · {{ setting('site_name') }}</title>
<link rel="stylesheet" href="/css/admin.css?v={{ @filemtime(public_path('css/admin.css')) }}">
@stack('head')
</head><body>
@php $newLeads = \App\Models\Lead::where('status','yeni')->count(); $r = request()->route()?->getName(); @endphp
<div class="shell">
  <aside class="side" id="side">
    <div class="brand">{{ setting('site_name') }}<br><small style="margin:0;opacity:.6;text-transform:none;letter-spacing:0">Yönetim paneli</small></div>
    <a href="{{ route('admin.dashboard') }}" @class(['on' => $r === 'admin.dashboard'])>Panel</a>
    <a href="{{ route('admin.leads.index') }}" @class(['on' => str_starts_with($r, 'admin.leads')])>Gelen talepler @if($newLeads)<span class="badge">{{ $newLeads }}</span>@endif</a>
    <small>İçerik</small>
    <a href="{{ route('admin.tedaviler.index') }}" @class(['on' => str_starts_with($r, 'admin.tedaviler')])>Tedaviler</a>
    <a href="{{ route('admin.posts.index', 'yazi') }}">Yazılarım</a>
    <a href="{{ route('admin.posts.index', 'gazete') }}">Gazete / Basın</a>
    <a href="{{ route('admin.posts.index', 'video') }}">Videolar</a>
    <small>Ayarlar</small>
    @foreach(\App\Http\Controllers\Admin\SettingController::SECTIONS as $k => $s)
      <a href="{{ route('admin.settings', $k) }}" @class(['on' => request()->route('section') === $k])>{{ $s[0] }}</a>
    @endforeach
    <small>Hesap</small>
    <a href="/" target="_blank">Siteyi görüntüle ↗</a>
    <a href="{{ route('admin.password') }}">Şifre değiştir</a>
    <form method="post" action="{{ route('admin.logout') }}">@csrf<a href="#" onclick="this.closest('form').submit();return false">Çıkış yap</a></form>
  </aside>
  <div class="main">
    <button class="btn sec burger" type="button" onclick="document.getElementById('side').classList.toggle('open')" style="margin-bottom:12px">☰ Menü</button>
    @if(session('ok'))<div class="flash ok" role="status">{{ session('ok') }}</div>@endif
    @if(session('err'))<div class="flash err" role="alert">{{ session('err') }}</div>@endif
    @if(session('info'))<div class="flash info">{{ session('info') }}</div>@endif
    @if($errors->any())<div class="flash err" role="alert"><b>Kaydedilemedi:</b><ul style="margin:6px 0 0 18px;padding:0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    @yield('content')
  </div>
</div>
@stack('scripts')
</body></html>
