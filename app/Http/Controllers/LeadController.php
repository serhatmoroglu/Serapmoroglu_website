<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Support\Notifier;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        // Bot tuzağı: gizli alan doluysa sessizce başarılı say
        if ($request->filled('website')) {
            return back()->with('lead_ok', true);
        }

        $type = $request->input('type');
        abort_unless(array_key_exists($type, Lead::TYPES), 422);

        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'phone' => [$type === 'iletisim' ? 'nullable' : 'required', 'string', 'max:30', 'regex:/^[\d\s+()\-]{10,20}$/'],
            'email' => [$type === 'iletisim' ? 'required' : 'nullable', 'email', 'max:160'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['nullable', 'string', 'max:3000'],
            'page' => ['nullable', 'string', 'max:200'],
        ];
        $messages = [
            'name.required' => 'Lütfen adınızı ve soyadınızı yazın.',
            'phone.required' => 'Size ulaşabilmemiz için telefon numarası gerekli.',
            'phone.regex' => 'Telefon numarasını rakamlarla yazın (örnek: 05XX XXX XX XX).',
            'email.required' => 'Lütfen e-posta adresinizi yazın.',
            'email.email' => 'E-posta adresi geçerli görünmüyor.',
        ];
        $data = $request->validate($rules, $messages);

        $lead = Lead::create($data + ['type' => $type, 'ip' => $request->ip()]);
        $lead->update(['mail_sent' => Notifier::leadReceived($lead)]);

        $text = "Merhaba, ben {$lead->name}.".($lead->subject ? " {$lead->subject} hakkında bilgi almak istiyorum." : ' Randevu hakkında bilgi almak istiyorum.');

        return redirect(url()->previous().'#talep-'.$type)
            ->with('lead_ok', $type)
            ->with('lead_wa', wa_link($text));
    }
}
