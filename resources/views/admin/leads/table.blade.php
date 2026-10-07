<div class="scroll"><table>
  <thead><tr><th>Tarih</th><th>Tür</th><th>Ad soyad</th><th>Telefon</th><th>Konu</th><th>Durum</th></tr></thead>
  <tbody>@forelse($leads as $l)
    <tr><td>{{ $l->created_at->format('d.m.Y H:i') }}</td><td>{{ \App\Models\Lead::TYPES[$l->type] ?? $l->type }}</td>
      <td><a href="{{ route('admin.leads.show', $l) }}"><b>{{ $l->name }}</b></a></td><td>{{ $l->phone ?: $l->email }}</td>
      <td>{{ \Illuminate\Support\Str::limit($l->subject, 40) }}</td><td><span class="pill {{ $l->status }}">{{ \App\Models\Lead::STATUSES[$l->status] ?? $l->status }}</span></td></tr>
  @empty<tr><td colspan="6" style="color:var(--mute)">Henüz talep yok. Sitedeki formlardan gelen talepler burada listelenir.</td></tr>@endforelse</tbody>
</table></div>
