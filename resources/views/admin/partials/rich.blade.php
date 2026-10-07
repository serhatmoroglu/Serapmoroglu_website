{{-- $name, $value, $id : Quill zengin metin editörü --}}
<div class="rich" data-name="{{ $name }}">
  <div id="ed-{{ $id }}">{!! $value !!}</div>
  <input type="hidden" name="{{ $name }}" id="in-{{ $id }}" value="{{ $value }}">
</div>
