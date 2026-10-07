@extends('layouts.site')
@section('title', $post->title)
@section('description', \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->body), 160))
@section('image', $post->image)
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / <a href="{{ route('posts.index') }}">Yazılarım</a></div><h1>{{ $post->title }}</h1></div></div>
<div class="wrap">
  <article class="prose">
    @if($post->image)<img class="feat" src="{{ img($post->image) }}" alt="{{ $post->title }}">@endif
    {!! $post->body !!}
    <div class="sidebox"><h3>Sorularınız için</h3><p>Muayene için randevu alın ya da WhatsApp'tan yazın.</p>
      <div class="cta" style="margin-top:14px"><a class="btn dark" href="{{ route('appointment') }}">Randevu al</a><a class="btn wa" href="{{ wa_link() }}" target="_blank" rel="noopener">WhatsApp</a></div></div>
  </article>
  @if($more->count())<section class="sec" style="padding-top:0"><h2 style="font-size:26px">Diğer yazılar</h2>
    <div class="cards">@foreach($more as $p)<a class="card" href="{{ route('posts.show', $p->slug) }}"><div class="ph" style="background-image:url('{{ img($p->image) }}')"></div><div class="bd"><h3>{{ $p->title }}</h3></div></a>@endforeach</div></section>@endif
</div>
@endsection
