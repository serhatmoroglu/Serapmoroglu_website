@extends('layouts.admin')
@php $label = \App\Models\Post::KINDS[$kind]; @endphp
@section('title', $item->exists ? $label.' düzenle' : 'Yeni '.$label)
@push('head')<meta name="csrf" content="{{ csrf_token() }}">@endpush
@section('content')
<div class="top"><h1>{{ $item->exists ? $item->title : 'Yeni '.mb_strtolower($label) }}</h1><a class="btn sec" href="{{ route('admin.posts.index', $kind) }}">← Listeye dön</a></div>
<form method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.posts.update', $item) : route('admin.posts.store', $kind) }}">
  @csrf @if($item->exists) @method('PUT') @endif
  <div class="panel">
    <div class="row"><label for="title">Başlık</label><input id="title" type="text" name="title" value="{{ old('title', $item->title) }}" required></div>
    @if($kind === 'gazete')<div class="row"><label for="eu">Haberin bağlantısı</label><input id="eu" type="url" name="external_url" value="{{ old('external_url', $item->external_url) }}" placeholder="https://..." required><span class="help">Ziyaretçi "Haberin tamamı" deyince bu adrese gider.</span></div>@endif
    @if($kind === 'video')<div class="row"><label for="vu">YouTube bağlantısı</label><input id="vu" type="url" name="video_url" value="{{ old('video_url', $item->video_url) }}" placeholder="https://youtu.be/..." required></div>@endif
    <div class="row"><label for="ex">Kısa özet</label><textarea id="ex" name="excerpt" maxlength="600">{{ old('excerpt', $item->excerpt) }}</textarea><span class="help">Kartlarda görünür.</span></div>
    @include('admin.partials.image-field', ['label' => $kind === 'gazete' ? 'Gazete / haber görseli' : 'Görsel', 'current' => $item->image, 'name' => 'image_file', 'removeName' => 'remove_image'])
    <div class="grid2">
      <div class="row"><label for="sort">Sıra</label><input id="sort" type="number" name="sort" value="{{ old('sort', $item->sort) }}" min="0"></div>
      @if($kind === 'yazi')<div class="row"><label for="slug">Sayfa adresi</label><input id="slug" type="text" name="slug" value="{{ old('slug', $item->slug) }}" placeholder="boşsa başlıktan üretilir"></div>@endif
    </div>
    <label style="font-weight:400"><input type="checkbox" name="published" value="1" @checked(old('published', $item->published))> Sitede yayınla</label>
  </div>
  @if($kind === 'yazi')<div class="panel"><h2>Yazı metni</h2>@include('admin.partials.rich', ['name' => 'body', 'id' => 'body', 'value' => old('body', $item->body)])</div>@endif
  <div class="sticky-save"><button class="btn" type="submit">Kaydet</button></div>
</form>
@if($item->exists)<form method="post" action="{{ route('admin.posts.destroy', $item) }}" onsubmit="return confirm('Bu kayıt kalıcı olarak silinecek. Emin misiniz?')">@csrf @method('DELETE')<button class="btn red sm" type="submit">Sil</button></form>@endif
@if($kind === 'yazi')@include('admin.partials.rich-assets')@endif
@endsection
