@extends('layouts.admin')
@php $label = \App\Models\Post::KINDS[$kind]; @endphp
@section('title', $label)
@section('content')
<div class="top"><h1>{{ $label }}</h1><a class="btn" href="{{ route('admin.posts.create', $kind) }}">+ Yeni ekle</a></div>
<div class="panel scroll"><table>
  <thead><tr><th>Sıra</th><th>Başlık</th><th>Durum</th><th></th></tr></thead>
  <tbody>@forelse($items as $p)
    <tr><td>{{ $p->sort }}</td><td><a href="{{ route('admin.posts.edit', $p) }}"><b>{{ $p->title }}</b></a>@if($p->external_url)<br><span class="help">{{ \Illuminate\Support\Str::limit($p->external_url, 70) }}</span>@endif</td>
      <td><span class="pill {{ $p->published ? '' : 'off' }}">{{ $p->published ? 'Yayında' : 'Gizli' }}</span></td>
      <td style="white-space:nowrap"><a class="btn sec sm" href="{{ route('admin.posts.edit', $p) }}">Düzenle</a>@if($kind === 'yazi') <a class="btn sec sm" href="{{ route('posts.show', $p->slug) }}" target="_blank">Gör ↗</a>@endif</td></tr>
  @empty<tr><td colspan="4" style="color:var(--mute)">Henüz kayıt yok.</td></tr>@endforelse</tbody></table></div>
@endsection
