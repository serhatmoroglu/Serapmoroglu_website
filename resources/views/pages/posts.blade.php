@extends('layouts.site')
@section('title', 'Yazılarım')
@section('description', 'Dr. Serap Özdamar’ın implant, gömük diş, çene eklemi, bruksizm ve diş estetiği üzerine yazıları.')
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / Yazılarım</div><h1>Yazılarım</h1><p>Ağız, diş ve çene sağlığı hakkında merak edilenler.</p></div></div>
<div class="wrap"><section class="sec"><div class="cards" style="margin-top:0">
  @foreach($posts as $p)
    <a class="card" href="{{ route('posts.show', $p->slug) }}">
      <div class="ph" style="background-image:url('{{ img($p->image) }}')"></div>
      <div class="bd"><h3>{{ $p->title }}</h3><p>{{ \Illuminate\Support\Str::limit($p->excerpt, 140) }}</p><span class="more">Devamını oku →</span></div>
    </a>
  @endforeach
</div></section></div>
@endsection
