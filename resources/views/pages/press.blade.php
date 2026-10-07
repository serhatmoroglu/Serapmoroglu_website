@extends('layouts.site')
@section('title', 'Basında Biz')
@section('description', 'Dr. Serap Özdamar’ın gazete ve haber sitelerindeki röportajları, televizyon ve sosyal medya programları.')
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / Basında Biz</div><h1>Basında Biz</h1><p>Gazete ve haber sitelerinde çıkan yazılar, televizyon ve sosyal medya programları.</p></div></div>
<div class="wrap">
  <section class="sec" id="gazete"><h2>Gazete yazılarım</h2>
    <div class="cards news">
      @foreach($news as $n)
        <a class="card" href="{{ $n->external_url }}" target="_blank" rel="noopener">
          <div class="ph" style="background-image:url('{{ img($n->image) }}')"></div>
          <div class="bd"><h3>{{ $n->title }}</h3><p>{{ \Illuminate\Support\Str::limit($n->excerpt, 130) }}</p><span class="more">Haberin tamamı ↗</span></div>
        </a>
      @endforeach
    </div>
  </section>
  <section class="sec" id="video"><h2>Sosyal medya ve TV</h2>
    <div class="cards">
      @foreach($videos as $v)
        <div class="card video">
          @if($v->embed_url)<iframe src="{{ $v->embed_url }}" title="{{ $v->title }}" loading="lazy" allowfullscreen></iframe>@else<div class="ph" style="background-image:url('{{ img($v->image) }}')"></div>@endif
          <div class="bd"><h3>{{ $v->title }}</h3>@if($v->video_url)<a class="more" href="{{ $v->video_url }}" target="_blank" rel="noopener">YouTube'da izle ↗</a>@endif</div>
        </div>
      @endforeach
    </div>
  </section>
</div>
@endsection
