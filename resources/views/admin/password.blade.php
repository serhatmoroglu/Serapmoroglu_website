@extends('layouts.admin')
@section('title', 'Şifre değiştir')
@section('content')
<div class="top"><h1>Şifre değiştir</h1></div>
<div class="panel" style="max-width:520px"><form method="post" action="{{ route('admin.password') }}">@csrf
  <div class="row"><label for="cp">Mevcut şifre</label><input id="cp" type="password" name="current_password" required autocomplete="current-password"></div>
  <div class="row"><label for="np">Yeni şifre</label><input id="np" type="password" name="password" required minlength="10" autocomplete="new-password"><span class="help">En az 10 karakter.</span></div>
  <div class="row"><label for="np2">Yeni şifre (tekrar)</label><input id="np2" type="password" name="password_confirmation" required autocomplete="new-password"></div>
  <button class="btn" type="submit">Şifreyi güncelle</button>
</form></div>
@endsection
