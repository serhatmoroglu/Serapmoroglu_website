@extends('layouts.admin')
@section('title', 'Tedaviler')
@section('content')
<div class="top"><h1>Tedaviler</h1><a class="btn" href="{{ route('admin.tedaviler.create') }}">+ Yeni tedavi</a></div>
<div class="panel scroll"><table>
  <thead><tr><th>Sıra</th><th>Başlık</th><th>Grup</th><th>Durum</th><th></th></tr></thead>
  <tbody>@foreach($items as $t)
    <tr><td>{{ $t->sort }}</td><td><a href="{{ route('admin.tedaviler.edit', $t) }}"><b>{{ $t->title }}</b></a><br><span class="help">/tedaviler/{{ $t->slug }}</span></td>
      <td>{{ \App\Models\Treatment::GROUPS[$t->group] ?? $t->group }}</td>
      <td><span class="pill {{ $t->published ? '' : 'off' }}">{{ $t->published ? 'Yayında' : 'Gizli' }}</span></td>
      <td style="white-space:nowrap"><a class="btn sec sm" href="{{ route('admin.tedaviler.edit', $t) }}">Düzenle</a> <a class="btn sec sm" href="{{ route('treatments.show', $t->slug) }}" target="_blank">Gör ↗</a></td></tr>
  @endforeach</tbody></table></div>
@endsection
