@extends('layouts.site')
@section('title', 'Hakkımızda')
@section('description', 'Dr. Serap Özdamar kimdir? İstanbul Üniversitesi Diş Hekimliği, Marmara Üniversitesi Ağız, Diş ve Çene Cerrahisi uzmanı.')
@section('image', setting('doctor_image'))
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / Hakkımızda</div><h1>Profesyonel diş hekimimiz ile diş sağlığınızı koruyoruz</h1></div></div>
<div class="wrap">
  <section class="doc" style="--doc-img:url('{{ img(setting('doctor_image')) }}');margin-top:clamp(28px,5vw,56px)">
    <div class="pic" role="img" aria-label="{{ setting('site_name') }}"></div>
    <div class="txt"><h2>{{ setting('about_title') }}</h2><div style="opacity:.9">{!! setting('about_bio') !!}</div><div><a class="btn" href="{{ route('appointment') }}">Randevu al</a></div></div>
  </section>
  @if(setting('about_video'))<section class="sec"><video controls preload="metadata" playsinline src="{{ img(setting('about_video')) }}" style="width:100%;max-width:760px;margin-inline:auto"></video></section>@endif
  <section class="sec"><h2>{{ setting('about_comfort_title') }}</h2><div class="prose" style="padding-inline:0;margin-inline:0">{!! setting('about_comfort') !!}</div>
    @if(count($images))<div class="gallery">@foreach($images as $i)<img src="{{ img($i) }}" alt="Klinik" loading="lazy">@endforeach</div>@endif
  </section>
  <div style="margin-top:56px">@include('partials.lead-form', ['type' => 'arayin'])</div>
</div>
@endsection
