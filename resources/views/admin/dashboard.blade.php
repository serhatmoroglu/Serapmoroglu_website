@extends('layouts.admin')
@section('title', 'Panel')
@section('content')
<div class="top"><h1>Panel</h1><a class="btn sec" href="/" target="_blank">Siteyi görüntüle ↗</a></div>
@unless($smtpOk)<div class="flash info"><b>E-posta bildirimi için SMTP girilmemiş.</b> Talepler panelde görünür ama size e-posta gitmez. <a href="{{ route('admin.settings', 'eposta') }}">E-posta ayarlarını yapın →</a></div>@endunless
@if($mailFailed)<div class="flash err">Son 14 günde <b>{{ $mailFailed }}</b> talebin e-posta bildirimi gönderilemedi. <a href="{{ route('admin.settings', 'eposta') }}">Ayarları kontrol edin →</a></div>@endif
<div class="stats">
  <div class="stat"><b>{{ $new }}</b><span>Yeni talep</span></div>
  <div class="stat"><b>{{ $week }}</b><span>Son 7 günde gelen</span></div>
  <div class="stat"><b>{{ $total }}</b><span>Toplam talep</span></div>
  <div class="stat"><b>{{ $treatments }}</b><span>Tedavi sayfası</span></div>
  <div class="stat"><b>{{ $posts }}</b><span>Yazı</span></div>
</div>
<div class="panel"><h2>Son talepler</h2>
  @include('admin.leads.table', ['leads' => $latest])
  <p style="margin:12px 0 0"><a href="{{ route('admin.leads.index') }}">Tüm talepleri gör →</a></p>
</div>
@endsection
