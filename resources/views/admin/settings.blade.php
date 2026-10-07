@extends('layouts.admin')
@section('title', $sections[$section][0])
@push('head')<meta name="csrf" content="{{ csrf_token() }}">@endpush
@section('content')
@php $fields = $sections[$section][1]; $hasRich = collect($fields)->contains(fn ($f) => $f[1] === 'rich'); @endphp
<div class="top"><h1>{{ $sections[$section][0] }}</h1></div>
<div class="tabs">@foreach($sections as $k => $s)<a href="{{ route('admin.settings', $k) }}" @class(['on' => $k === $section])>{{ $s[0] }}</a>@endforeach</div>
@if($section === 'eposta')
<div class="panel"><h2>Gmail ile kurulum</h2><ol style="margin:0 0 0 18px;padding:0;line-height:1.8">
  <li>Google hesabınızda <b>2 Adımlı Doğrulama</b>'yı açın (hesap güvenliği ayarları).</li>
  <li>Google hesabı → Güvenlik → <b>Uygulama şifreleri</b> bölümünden "Posta" için 16 haneli bir şifre oluşturun.</li>
  <li>Aşağıda sunucu <code>smtp.gmail.com</code>, port <code>587</code>, şifreleme <b>TLS</b>, kullanıcı adı Gmail adresiniz, şifre yerine bu <b>uygulama şifresini</b> yazın.</li>
  <li>Kaydedin ve <b>Test e-postası gönder</b> düğmesine basın.</li></ol></div>
@endif
<form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update', $section) }}">
  @csrf @method('PUT')
  <div class="panel">
  @foreach($fields as $key => $f)
    @php [$label, $type] = [$f[0], $f[1]]; $help = $f[2] ?? ''; $val = old($key, setting($key)); @endphp
    <div class="row">
      <label for="f-{{ $key }}">{{ $label }}</label>
      @switch($type)
        @case('textarea')<textarea id="f-{{ $key }}" name="{{ $key }}">{{ $val }}</textarea>@break
        @case('rich')@include('admin.partials.rich', ['name' => $key, 'id' => $key, 'value' => $val])@break
        @case('email')<input id="f-{{ $key }}" type="email" name="{{ $key }}" value="{{ $val }}">@break
        @case('password')<input id="f-{{ $key }}" type="password" name="{{ $key }}" value="" autocomplete="new-password" placeholder="{{ setting($key) ? '•••••••• (kayıtlı, değiştirmek için yazın)' : '' }}">@break
        @case('select')<select id="f-{{ $key }}" name="{{ $key }}">@foreach($f[3] as $ov => $ol)<option value="{{ $ov }}" @selected($val === $ov)>{{ $ol }}</option>@endforeach</select>@break
        @case('image')
          @if($val)<img class="thumb" src="{{ img($val) }}" alt=""><label style="font-weight:400"><input type="checkbox" name="remove[{{ $key }}]" value="1"> Kaldır</label>@endif
          <input id="f-{{ $key }}" type="file" name="upload[{{ $key }}]" accept="image/*">@break
        @case('video')
          @if($val)<video src="{{ img($val) }}" controls style="max-width:320px;border-radius:10px"></video><label style="font-weight:400"><input type="checkbox" name="remove[{{ $key }}]" value="1"> Videoyu kaldır</label>@endif
          <input id="f-{{ $key }}" type="file" name="upload[{{ $key }}]" accept="video/mp4,video/webm">@break
        @case('images')
          @php $list = json_decode((string) $val, true) ?: []; @endphp
          <div class="gal">@foreach($list as $i => $g)<label><img src="{{ img($g) }}" alt=""><input type="checkbox" name="keep[{{ $key }}][]" value="{{ $g }}" checked> Tut</label>@endforeach</div>
          <span class="help">İşareti kaldırdığınız fotoğraf galeriden çıkar.</span>
          <input id="f-{{ $key }}" type="file" name="upload[{{ $key }}][]" accept="image/*" multiple>@break
        @default<input id="f-{{ $key }}" type="text" name="{{ $key }}" value="{{ $val }}">
      @endswitch
      @if($help)<span class="help">{{ $help }}</span>@endif
    </div>
  @endforeach
  </div>
  <div class="sticky-save"><button class="btn" type="submit">Kaydet</button></div>
</form>
@if($section === 'eposta')
<form method="post" action="{{ route('admin.settings.testmail') }}">@csrf<button class="btn sec" type="submit">Test e-postası gönder</button> <span class="help">Önce ayarları kaydedin.</span></form>
@endif
@if($hasRich)@include('admin.partials.rich-assets')@endif
@endsection
