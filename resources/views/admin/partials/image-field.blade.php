{{-- $label, $current, $name (dosya alanı adı), $removeName --}}
<div class="row"><label>{{ $label }}</label>
  @if($current)<img class="thumb" src="{{ img($current) }}" alt=""><label style="font-weight:400"><input type="checkbox" name="{{ $removeName }}" value="1"> Mevcut görseli kaldır</label>@endif
  <input type="file" name="{{ $name }}" accept="image/*"><span class="help">JPG, PNG veya WebP. Büyük dosyalar otomatik küçültülür.</span>
</div>
