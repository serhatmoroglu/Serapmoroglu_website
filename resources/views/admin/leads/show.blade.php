@extends('layouts.admin')
@section('title', 'Talep #'.$lead->id)
@section('content')
<div class="top"><h1>Talep #{{ $lead->id }}</h1><a class="btn sec" href="{{ route('admin.leads.index') }}">← Listeye dön</a></div>
<div class="grid2">
<div class="panel"><h2>Bilgiler</h2>
  <p><b>{{ \App\Models\Lead::TYPES[$lead->type] ?? $lead->type }}</b> · {{ $lead->created_at->format('d.m.Y H:i') }}</p>
  <p><b>Ad soyad:</b> {{ $lead->name }}</p>
  @if($lead->phone)<p><b>Telefon:</b> <a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}">{{ $lead->phone }}</a></p>@endif
  @if($lead->email)<p><b>E-posta:</b> <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></p>@endif
  @if($lead->subject)<p><b>Konu:</b> {{ $lead->subject }}</p>@endif
  @if($lead->message)<p><b>Mesaj:</b><br>{!! nl2br(e($lead->message)) !!}</p>@endif
  <p class="help">Gelen sayfa: /{{ $lead->page }} · E-posta bildirimi: {{ $lead->mail_sent ? 'gönderildi' : 'gönderilemedi' }}</p>
  @if($lead->phone)@php $n = preg_replace('/\D+/', '', $lead->phone); $n = str_starts_with($n, '0') ? '9'.$n : (strlen($n) === 10 ? '90'.$n : $n); @endphp
    <a class="btn" style="background:#1fa855" target="_blank" rel="noopener" href="https://wa.me/{{ $n }}?text={{ rawurlencode('Merhaba '.$lead->name.', Dr. Serap Özdamar kliniğinden yazıyoruz. Talebiniz bize ulaştı.') }}">WhatsApp'tan yaz</a>@endif
</div>
<div class="panel"><h2>Takip</h2>
  <form method="post" action="{{ route('admin.leads.update', $lead) }}">@csrf @method('PUT')
    <div class="row"><label for="st">Durum</label><select id="st" name="status">@foreach(\App\Models\Lead::STATUSES as $k => $v)<option value="{{ $k }}" @selected($lead->status === $k)>{{ $v }}</option>@endforeach</select></div>
    <div class="row"><label for="nt">Not</label><textarea id="nt" name="note">{{ old('note', $lead->note) }}</textarea></div>
    <button class="btn" type="submit">Kaydet</button>
  </form>
  <form method="post" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Bu talep kalıcı olarak silinecek. Emin misiniz?')" style="margin-top:14px">@csrf @method('DELETE')<button class="btn red sm" type="submit">Talebi sil</button></form>
</div></div>
@endsection
