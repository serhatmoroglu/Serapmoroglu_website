@extends('layouts.admin')
@section('title', 'Gelen talepler')
@section('content')
<div class="top"><h1>Gelen talepler</h1><a class="btn sec" href="{{ route('admin.leads.export', request()->query()) }}">Excel için indir (CSV)</a></div>
<form class="filters" method="get">
  <input type="search" name="q" value="{{ request('q') }}" placeholder="Ad, telefon, e-posta ara">
  <select name="type"><option value="">Tüm türler</option>@foreach(\App\Models\Lead::TYPES as $k => $v)<option value="{{ $k }}" @selected(request('type') === $k)>{{ $v }}</option>@endforeach</select>
  <select name="status"><option value="">Tüm durumlar</option>@foreach(\App\Models\Lead::STATUSES as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach</select>
  <button class="btn" type="submit">Filtrele</button>
</form>
<div class="panel">@include('admin.leads.table', ['leads' => $leads])@include('admin.partials.pager', ['p' => $leads])</div>
@endsection
