@extends('layouts.site')
@section('bodyclass', 'home')
@section('content')
<section class="hero" style="--hero-img:url('{{ img(setting('hero_image')) }}')">
  <div class="wrap">
    <div class="copy">
      <span class="tag">{{ setting('hero_tag') }}</span>
      <h1>{{ setting('hero_title') }}</h1>
      <p class="lead">{{ setting('hero_lead') }}</p>
      <div class="cta"><a class="btn" href="{{ route('appointment') }}">Randevu al</a><a class="btn ghost" href="#tedaviler">Tedaviler</a></div>
    </div>
  </div>
</section>
<div class="strip"><div class="wrap">
  @foreach([1,2,3,4] as $i)<div><b>{{ setting("strip_{$i}_value") }}</b><span>{{ setting("strip_{$i}_label") }}</span></div>@endforeach
</div></div>

<div class="wrap">
  <section class="sec" id="tedaviler">
    @foreach(\App\Models\Treatment::GROUPS as $key => $label)
      @if(isset($groups[$key]))
      <div class="group"><h2>{{ $label }}</h2>
        <div class="grid">
          @foreach($groups[$key] as $t)<a class="tile" href="{{ route('treatments.show', $t->slug) }}"><b>{{ $t->title }}</b><span>{{ $t->summary }}</span></a>@endforeach
        </div>
      </div>
      @endif
    @endforeach
  </section>

  <section class="doc" style="--doc-img:url('{{ img(setting('doctor_image')) }}')">
    <div class="pic" role="img" aria-label="{{ setting('site_name') }}"></div>
    <div class="txt">
      <q>{{ setting('doctor_quote') }}</q>
      <h2>{{ setting('site_name') }}</h2>
      <p>{{ setting('doctor_text') }}</p>
      <div><a class="btn" href="{{ route('about') }}">Hakkımda</a></div>
    </div>
  </section>

  @if($posts->count())
  <section class="sec">
    <h2>Yazılarım</h2>
    <div class="cards">
      @foreach($posts as $p)
        <a class="card" href="{{ route('posts.show', $p->slug) }}">
          <div class="ph" style="background-image:url('{{ img($p->image) }}')"></div>
          <div class="bd"><h3>{{ $p->title }}</h3><p>{{ \Illuminate\Support\Str::limit($p->excerpt, 120) }}</p><span class="more">Devamını oku →</span></div>
        </a>
      @endforeach
    </div>
  </section>
  @endif

  <div style="margin-top:clamp(48px,7vw,72px)">@include('partials.lead-form', ['type' => 'arayin'])</div>
</div>
@endsection
