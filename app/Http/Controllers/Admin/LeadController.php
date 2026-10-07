<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    private function query(Request $r)
    {
        return Lead::query()
            ->when($r->filled('type'), fn ($q) => $q->where('type', $r->type))
            ->when($r->filled('status'), fn ($q) => $q->where('status', $r->status))
            ->when($r->filled('q'), fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$r->q}%")->orWhere('phone', 'like', "%{$r->q}%")
                ->orWhere('email', 'like', "%{$r->q}%")->orWhere('message', 'like', "%{$r->q}%")))
            ->latest();
    }

    public function index(Request $request)
    {
        return view('admin.leads.index', ['leads' => $this->query($request)->paginate(25)->withQueryString()]);
    }

    public function show(Lead $lead)
    {
        return view('admin.leads.show', ['lead' => $lead]);
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate(['status' => ['required', 'in:yeni,arandi,kapandi'], 'note' => ['nullable', 'string', 'max:2000']]);
        $lead->update($data);
        return back()->with('ok', 'Talep güncellendi.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.leads.index')->with('ok', 'Talep silindi.');
    }

    public function export(Request $request)
    {
        $rows = $this->query($request)->get();
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // Excel için UTF-8
            fputcsv($out, ['Tarih', 'Tür', 'Ad soyad', 'Telefon', 'E-posta', 'Konu', 'Mesaj', 'Durum', 'Not'], ';');
            foreach ($rows as $l) {
                fputcsv($out, [$l->created_at->format('d.m.Y H:i'), Lead::TYPES[$l->type] ?? $l->type, $l->name, $l->phone, $l->email,
                    $l->subject, $l->message, Lead::STATUSES[$l->status] ?? $l->status, $l->note], ';');
            }
            fclose($out);
        }, 'talepler-'.date('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
