<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactFormController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ];

        // Validação reCAPTCHA se a chave estiver configurada
        $secretKey = Setting::get('recaptcha_secret_key');
        if ($secretKey) {
            $rules['g-recaptcha-response'] = 'required';
        }

        $validated = $request->validate($rules);

        // Verificação da resposta reCAPTCHA v3 junto ao Google
        if ($secretKey && $request->filled('g-recaptcha-response')) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $request->input('g-recaptcha-response'),
            ]);
            $result = $response->json();
            
            // Para reCAPTCHA v3, verificamos se success é true e o score é > 0.5
            if (empty($result['success']) || ($result['score'] ?? 0) < 0.5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verificação de segurança falhou. Tente novamente.',
                ], 422);
            }
        }

        Contact::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.',
        ], 200);
    }
}
