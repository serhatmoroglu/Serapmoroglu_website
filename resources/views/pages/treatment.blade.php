@extends('layouts.site')
@section('title', $treatment->title)
@section('description', $treatment->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($treatment->body), 160))
@section('image', $treatment->image)
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / <a href="{{ route('treatments.index') }}">Tedaviler</a> / {{ $treatment->title }}</div><h1>{{ $treatment->title }}</h1></div></div>
<div class="wrap">
  <article class="prose">
    @if($treatment->image)<img class="feat" src="{{ img($treatment->image) }}" alt="{{ $treatment->title }}">@endif
    {!! $treatment->body !!}
    <div class="sidebox"><h3>Bu tedavi hakkında bilgi alın</h3><p>Numaranızı bırakın, sizi arayalım ya da WhatsApp'tan yazın.</p>
      <div class="cta" style="margin-top:14px"><a class="btn dark" href="{{ route('appointment') }}">Randevu al</a><a class="btn wa" href="{{ wa_link('Merhaba, '.$treatment->title.' hakkında bilgi almak istiyorum.') }}" target="_blank" rel="noopener">WhatsApp</a></div>
    </div>
  </article>
  <section class="sec" style="padding-top:0"><h2 style="font-size:26px">Diğer tedaviler</h2>
    <div class="grid">@foreach($others as $t)<a class="tile" href="{{ route('treatments.show', $t->slug) }}"><b>{{ $t->title }}</b><span>{{ $t->summary }}</span></a>@endforeach</div>
  </section>
</div>
@endsection
