@extends('layouts.site')
@section('title', 'Randevu Al')
@section('description', 'Dr. Serap Özdamar için online randevu talebi. Adınızı, telefonunuzu ve konuyu yazın, sizi arayalım.')
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / Randevu</div><h1>Randevu al</h1><p>Formu doldurun, sizi arayarak uygun gün ve saati birlikte belirleyelim.</p></div></div>
<div class="wrap" style="margin-top:clamp(28px,5vw,56px)">
  @include('partials.lead-form', ['type' => 'randevu', 'title' => 'Randevu talebi', 'lead' => 'Telefon numaranız yalnızca sizi aramak için kullanılır.'])
  <p class="sec lead" style="padding-top:20px">Acil durumlarda doğrudan arayın: <a href="{{ tel_link() }}"><b>{{ setting('phone') }}</b></a> · <a href="{{ wa_link() }}" target="_blank" rel="noopener">WhatsApp</a></p>
</div>
@endsection
