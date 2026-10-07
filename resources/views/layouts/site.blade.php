@php
    $siteName = setting('site_name');
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? "$pageTitle | $siteName" : $siteName.' — '.setting('tagline');
    $desc = trim($__env->yieldContent('description')) ?: setting('seo_description');
    $ogImage = trim($__env->yieldContent('image')) ?: setting('hero_image');
    $navGroups = \App\Models\Treatment::published()->orderBy('sort')->get(['slug', 'title', 'group'])->groupBy('group');
    $ld = [
        '@context' => 'https://schema.org', '@type' => 'Dentist', 'name' => $siteName,
        'url' => url('/'), 'telephone' => setting('phone'), 'image' => url(img($ogImage) ?? '/'),
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => setting('address'), 'addressLocality' => 'Ataşehir', 'addressRegion' => 'İstanbul', 'addressCountry' => 'TR'],
        'medicalSpecialty' => 'Dentistry',
        'sameAs' => array_values(array_filter([setting('instagram'), setting('facebook'), setting('youtube')])),
    ];
@endphp
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($desc), 300) }}">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="tr_TR">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($desc), 200) }}">
@if($ogImage)<meta property="og:image" content="{{ url(img($ogImage)) }}">@endif
<meta name="theme-color" content="#0f1d4a">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700&family=DM+Sans:wght@400;500;700&display=swap">
<link rel="stylesheet" href="/css/site.css?v={{ @filemtime(public_path('css/site.css')) }}">
<script type="application/ld+json">{!! json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@if(setting('ga_id'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('ga_id') }}"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','{{ setting('ga_id') }}');</script>
@endif
@stack('head')
</head>
<body class="@yield('bodyclass')">
<a class="skip" href="#icerik">İçeriğe geç</a>
<header class="site-header">
  <div class="wrap bar">
    <a class="logo" href="/"><i></i>{{ $siteName }}</a>
    <nav class="nav" aria-label="Ana menü">
      <a href="/" @if(request()->is('/')) aria-current="page" @endif>Ana Sayfa</a>
      <div class="dd">
        <button type="button" aria-haspopup="true">Tedaviler ▾</button>
        <div class="dd-panel cols">
          @foreach(\App\Models\Treatment::GROUPS as $key => $label)
            <div><h4>{{ $label }}</h4>
              @foreach($navGroups[$key] ?? [] as $t)<a href="{{ route('treatments.show', $t->slug) }}">{{ $t->title }}</a>@endforeach
            </div>
          @endforeach
        </div>
      </div>
      <div class="dd">
        <button type="button" aria-haspopup="true">Yazılarım ▾</button>
        <div class="dd-panel">
          <a href="{{ route('posts.index') }}">Yazılarım</a>
          <a href="{{ route('press') }}">Basında Biz</a>
          <a href="{{ route('press') }}#video">Sosyal Medya &amp; TV</a>
          <a href="{{ route('press') }}#gazete">Gazete Yazılarım</a>
        </div>
      </div>
      <a href="{{ route('clinic') }}" @if(request()->is('klinik')) aria-current="page" @endif>Kliniğimiz</a>
      <a href="{{ route('about') }}" @if(request()->is('hakkimizda')) aria-current="page" @endif>Hakkımızda</a>
      <a href="{{ route('contact') }}" @if(request()->is('iletisim')) aria-current="page" @endif>İletişim</a>
    </nav>
    <a class="call" href="{{ tel_link() }}">{{ setting('phone') }}</a>
    <button class="burger" type="button" id="burger" aria-expanded="false" aria-controls="mnav">Menü</button>
  </div>
</header>
<nav class="mnav" id="mnav" aria-label="Mobil menü">
  <a href="/">Ana Sayfa</a>
  <details><summary>Tedaviler</summary>
    <a href="{{ route('treatments.index') }}">Tüm tedaviler</a>
    @foreach($navGroups->flatten() as $t)<a href="{{ route('treatments.show', $t->slug) }}">{{ $t->title }}</a>@endforeach
  </details>
  <details><summary>Yazılarım</summary>
    <a href="{{ route('posts.index') }}">Yazılarım</a><a href="{{ route('press') }}">Basında Biz</a>
    <a href="{{ route('press') }}#video">Sosyal Medya &amp; TV</a><a href="{{ route('press') }}#gazete">Gazete Yazılarım</a>
  </details>
  <a href="{{ route('clinic') }}">Kliniğimiz</a><a href="{{ route('about') }}">Hakkımızda</a><a href="{{ route('contact') }}">İletişim</a>
</nav>

<main id="icerik">@yield('content')</main>

<footer class="site-footer">
  <div class="wrap">
    <div>
      <strong style="font:700 20px var(--display)">{{ $siteName }}</strong>
      <p style="margin-top:8px;opacity:.85">{{ setting('doctor_title') }}</p>
      <p style="margin-top:12px;opacity:.85">{{ setting('address') }}</p>
    </div>
    <div>
      <h4>Çalışma saatleri</h4>
      <p>Hafta içi: {{ setting('hours_weekday') }}</p>
      <p>Cumartesi: {{ setting('hours_saturday') }}</p>
      <p>Pazar: {{ setting('hours_sunday') }}</p>
      <h4 style="margin-top:16px">İletişim</h4>
      <a href="{{ tel_link() }}">{{ setting('phone') }}</a>
      <a href="{{ wa_link() }}" target="_blank" rel="noopener">WhatsApp ile yazın</a>
    </div>
    <div>
      <h4>Bağlantılar</h4>
      <a href="{{ route('treatments.index') }}">Tedaviler</a><a href="{{ route('posts.index') }}">Yazılarım</a>
      <a href="{{ route('press') }}">Basında Biz</a><a href="{{ route('appointment') }}">Randevu al</a>
      @if(setting('instagram'))<a href="{{ setting('instagram') }}" target="_blank" rel="noopener">Instagram</a>@endif
      @if(setting('facebook'))<a href="{{ setting('facebook') }}" target="_blank" rel="noopener">Facebook</a>@endif
      @if(setting('youtube'))<a href="{{ setting('youtube') }}" target="_blank" rel="noopener">YouTube</a>@endif
    </div>
    <div class="copy">© {{ date('Y') }} {{ $siteName }}. Bu sitedeki bilgiler genel bilgilendirme amaçlıdır, muayene yerine geçmez.</div>
  </div>
</footer>

<a class="wa-float" href="{{ wa_link() }}" target="_blank" rel="noopener" aria-label="WhatsApp'tan yazın">
  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2zm5.8 14.2c-.2.7-1.4 1.3-1.9 1.3-.5.1-1.1.1-1.8-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5-4.5-.2-.2-1.2-1.6-1.2-3s.8-2.1 1-2.4c.3-.3.6-.3.8-.3h.6c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .5l-.4.6-.4.4c-.1.2-.3.3-.1.6.2.3.7 1.2 1.6 1.9 1.1 1 2 1.3 2.3 1.4.3.1.4.1.6-.1l.8-1c.2-.3.4-.2.6-.1l2 1c.3.1.5.2.5.3.1.2.1.8-.1 1.4z"/></svg>
  <span>WhatsApp'tan yazın</span>
</a>
<div class="mbar"><a href="{{ route('appointment') }}">Randevu al</a><a href="{{ tel_link() }}">Ara</a></div>
<script src="/js/site.js?v={{ @filemtime(public_path('js/site.js')) }}" defer></script>
</body>
</html>
