@extends('layouts.site')
@section('title', 'Tedaviler')
@section('description', 'İmplant, sinüs lifting, gömük diş çekimi, laminate veneer, ortodonti ve daha fazlası: Dr. Serap Özdamar tedavi alanları.')
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / Tedaviler</div><h1>Tedavilerimiz</h1><p>Her tedavinin süreci, uygulanma biçimi ve iyileşme bilgisi kendi sayfasında anlatılıyor.</p></div></div>
<div class="wrap"><section class="sec">
  @foreach(\App\Models\Treatment::GROUPS as $key => $label)
    @if(isset($groups[$key]))
    <div class="group"><h2>{{ $label }}</h2>
      <div class="grid">@foreach($groups[$key] as $t)<a class="tile" href="{{ route('treatments.show', $t->slug) }}"><b>{{ $t->title }}</b><span>{{ $t->summary }}</span></a>@endforeach</div>
    </div>@endif
  @endforeach
  <div style="margin-top:56px">@include('partials.lead-form', ['type' => 'arayin'])</div>
</section></div>
@endsection
