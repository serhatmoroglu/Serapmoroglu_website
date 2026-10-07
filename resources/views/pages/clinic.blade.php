@extends('layouts.site')
@section('title', 'Kliniğimiz')
@section('description', 'Ataşehir Varyap Meridian’daki kliniğimiz: modern ekipman ve konforlu bir ortam.')
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / Kliniğimiz</div><h1>Kliniğimiz</h1><p>{{ setting('klinik_intro') }}</p></div></div>
<div class="wrap"><section class="sec" style="padding-top:clamp(28px,5vw,48px)">
  @if(count($images))<div class="gallery" style="margin-top:0">@foreach($images as $i)<img src="{{ img($i) }}" alt="Klinik" loading="lazy">@endforeach</div>@endif
  <div style="margin-top:56px">@include('partials.lead-form', ['type' => 'arayin'])</div>
</section></div>
@endsection
