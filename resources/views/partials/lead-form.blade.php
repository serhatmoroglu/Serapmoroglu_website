{{-- $type: arayin | randevu | iletisim ; $stack: tek sütun ; $title/$lead: başlık --}}
@php
    $type = $type ?? 'arayin';
    $subjects = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) setting('form_subjects')))));
    $title = $title ?? setting('form_title');
    $lead = $lead ?? setting('form_lead');
    $fid = 'talep-'.$type;
    $done = session('lead_ok') === $type;
@endphp
<section class="form {{ ($stack ?? false) ? 'stack' : '' }}" id="{{ $fid }}">
  <div><h2>{{ $title }}</h2><p>{{ $lead }}</p></div>
  @if($done)
    <div class="ok" role="status">
      <b>Talebiniz bize ulaştı.</b>
      <span>En kısa sürede sizi arayacağız. Beklemek istemezseniz WhatsApp'tan da yazabilirsiniz.</span>
      <span><a class="btn wa" href="{{ session('lead_wa') ?? wa_link() }}" target="_blank" rel="noopener">WhatsApp'tan yazın</a></span>
    </div>
  @else
  <form class="fields" method="post" action="{{ route('lead.store') }}" data-lead novalidate>
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="page" value="{{ request()->path() }}">
    <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
    <label class="f">Ad soyad
      <input name="name" value="{{ old('type') === $type ? old('name') : '' }}" autocomplete="name" required placeholder="Adınız soyadınız">
      @if(old('type') === $type)@error('name')<span class="err">{{ $message }}</span>@enderror @endif
    </label>
    <label class="f">{{ $type === 'iletisim' ? 'Telefon (isteğe bağlı)' : 'Telefon' }}
      <input name="phone" type="tel" inputmode="tel" value="{{ old('type') === $type ? old('phone') : '' }}" autocomplete="tel" @if($type!=='iletisim') required @endif placeholder="05XX XXX XX XX">
      @if(old('type') === $type)@error('phone')<span class="err">{{ $message }}</span>@enderror @endif
    </label>
    @if($type === 'iletisim')
      <label class="f full">E-posta
        <input name="email" type="email" value="{{ old('type') === $type ? old('email') : '' }}" autocomplete="email" required placeholder="ornek@eposta.com">
        @if(old('type') === $type)@error('email')<span class="err">{{ $message }}</span>@enderror @endif
      </label>
      <label class="f full">Başlık<input name="subject" value="{{ old('type') === $type ? old('subject') : '' }}" placeholder="Konu başlığı"></label>
      <label class="f full">Mesajınız<textarea name="message" placeholder="Sorunuzu veya talebinizi yazın">{{ old('type') === $type ? old('message') : '' }}</textarea></label>
    @else
      <label class="f full">Konu
        <select name="subject">@foreach($subjects as $s)<option @selected(old('subject') === $s)>{{ $s }}</option>@endforeach</select>
      </label>
      @if($type === 'randevu')
        <label class="f full">Notunuz (isteğe bağlı)<textarea name="message" placeholder="Uygun olduğunuz gün ve saat gibi">{{ old('type') === $type ? old('message') : '' }}</textarea></label>
      @endif
    @endif
    <button class="btn dark full" type="submit" style="grid-column:1/-1">{{ $type === 'iletisim' ? 'Gönder' : ($type === 'randevu' ? 'Randevu talebi gönder' : 'Beni arayın') }}</button>
  </form>
  @endif
</section>
