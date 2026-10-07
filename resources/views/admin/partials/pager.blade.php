@if($p->hasPages())
<div style="display:flex;gap:8px;align-items:center;margin-top:14px">
  @if($p->onFirstPage())<span class="btn sec sm" style="opacity:.4">← Önceki</span>@else<a class="btn sec sm" href="{{ $p->previousPageUrl() }}">← Önceki</a>@endif
  <span class="help">Sayfa {{ $p->currentPage() }} / {{ $p->lastPage() }} · toplam {{ $p->total() }}</span>
  @if($p->hasMorePages())<a class="btn sec sm" href="{{ $p->nextPageUrl() }}">Sonraki →</a>@else<span class="btn sec sm" style="opacity:.4">Sonraki →</span>@endif
</div>
@endif
