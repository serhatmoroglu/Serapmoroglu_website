@extends('layouts.site')
@section('title', 'İletişim')
@section('description', 'Dr. Serap Özdamar ile iletişime geçin: telefon, WhatsApp, adres ve çalışma saatleri.')
@section('content')
<div class="pagehead"><div class="wrap"><div class="crumbs"><a href="/">Ana sayfa</a> / İletişim</div><h1>Bize ulaşın</h1><p>Sağlıklı ve estetik bir gülüş için buradayız. Sorularınız ve randevu talepleriniz için arayın, yazın ya da formu doldurun.</p></div></div>
<div class="wrap">
  <div class="contact-grid">
    <div>
      <dl class="info">
        <dt>Telefon</dt><dd><a href="{{ tel_link() }}">{{ setting('phone') }}</a></dd>
        <dt>WhatsApp</dt><dd><a href="{{ wa_link() }}" target="_blank" rel="noopener">Mesaj gönderin</a></dd>
        <dt>Adres</dt><dd>{{ setting('address') }}</dd>
        <dt>Hafta içi</dt><dd>{{ setting('hours_weekday') }}</dd>
        <dt>Cumartesi</dt><dd>{{ setting('hours_saturday') }}</dd>
        <dt>Pazar</dt><dd>{{ setting('hours_sunday') }}</dd>
      </dl>
      <div class="cta"><a class="btn dark" href="{{ tel_link() }}">Hemen ara</a><a class="btn line" href="{{ setting('map_link') }}" target="_blank" rel="noopener">Yol tarifi al</a></div>
    </div>
    <div class="map"><iframe src="{{ setting('map_embed') }}" title="Klinik konumu" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
  </div>
  <div style="margin-top:48px">@include('partials.lead-form', ['type' => 'iletisim', 'title' => 'Bize yazın', 'lead' => 'Mesajınızı bırakın, e-posta ile size dönelim.', 'stack' => false])</div>
</div>
@endsection
