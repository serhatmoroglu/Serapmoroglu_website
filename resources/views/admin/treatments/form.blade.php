@extends('layouts.admin')
@section('title', $item->exists ? 'Tedaviyi düzenle' : 'Yeni tedavi')
@section('content')
@push('head')<meta name="csrf" content="{{ csrf_token() }}">@endpush
<div class="top"><h1>{{ $item->exists ? $item->title : 'Yeni tedavi' }}</h1><a class="btn sec" href="{{ route('admin.tedaviler.index') }}">← Listeye dön</a></div>
<form method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.tedaviler.update', $item) : route('admin.tedaviler.store') }}">
  @csrf @if($item->exists) @method('PUT') @endif
  <div class="panel">
    <div class="row"><label for="title">Başlık</label><input id="title" type="text" name="title" value="{{ old('title', $item->title) }}" required></div>
    <div class="grid2">
      <div class="row"><label for="group">Grup</label><select id="group" name="group">@foreach(\App\Models\Treatment::GROUPS as $k => $v)<option value="{{ $k }}" @selected(old('group', $item->group) === $k)>{{ $v }}</option>@endforeach</select></div>
      <div class="row"><label for="sort">Sıra</label><input id="sort" type="number" name="sort" value="{{ old('sort', $item->sort) }}" min="0"><span class="help">Küçük sayı önce görünür.</span></div>
    </div>
    <div class="row"><label for="summary">Kısa açıklama</label><input id="summary" type="text" name="summary" maxlength="200" value="{{ old('summary', $item->summary) }}"><span class="help">Kartlarda başlığın altında görünür.</span></div>
    <div class="row"><label for="slug">Sayfa adresi</label><input id="slug" type="text" name="slug" value="{{ old('slug', $item->slug) }}" placeholder="boş bırakırsanız başlıktan üretilir"><span class="help">Örnek: implant-tedavisi → /tedaviler/implant-tedavisi. Mevcut sayfanın adresini değiştirirseniz eski bağlantılar çalışmaz.</span></div>
    @include('admin.partials.image-field', ['label' => 'Öne çıkan görsel', 'current' => $item->image, 'name' => 'image_file', 'removeName' => 'remove_image'])
    <label style="font-weight:400"><input type="checkbox" name="published" value="1" @checked(old('published', $item->published))> Sitede yayınla</label>
  </div>
  <div class="panel"><h2>İçerik</h2>@include('admin.partials.rich', ['name' => 'body', 'id' => 'body', 'value' => old('body', $item->body)])</div>
  <div class="panel"><div class="row"><label for="md">Arama motoru açıklaması (isteğe bağlı)</label><textarea id="md" name="meta_description" maxlength="300">{{ old('meta_description', $item->meta_description) }}</textarea><span class="help">Boşsa içeriğin ilk cümleleri kullanılır.</span></div></div>
  <div class="sticky-save"><button class="btn" type="submit">Kaydet</button></div>
</form>
@if($item->exists)<form method="post" action="{{ route('admin.tedaviler.destroy', $item) }}" onsubmit="return confirm('Bu tedavi sayfası kalıcı olarak silinecek. Emin misiniz?')">@csrf @method('DELETE')<button class="btn red sm" type="submit">Bu tedaviyi sil</button></form>@endif
@include('admin.partials.rich-assets')
@endsection
