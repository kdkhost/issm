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

        // Valida o CAPTCHA configurado: Cloudflare Turnstile ou Google reCAPTCHA
        $turnstileSecret = Setting::get('turnstile_secret_key');
        $recaptchaSecret = Setting::get('recaptcha_secret_key');

        if ($turnstileSecret) {
            $rules['cf-turnstile-response'] = 'required';
        } elseif ($recaptchaSecret) {
            $rules['g-recaptcha-response'] = 'required';
        }

        $validated = $request->validate($rules);

        // Cloudflare Turnstile verification
        if ($turnstileSecret && $request->filled('cf-turnstile-response')) {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret'   => $turnstileSecret,
                'response' => $request->input('cf-turnstile-response'),
            ]);
            $result = $response->json();

            if (empty($result['success'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verificação de segurança falhou. Tente novamente.',
                ], 422);
            }
        }

        // Google reCAPTCHA v3 verification (fallback)
        if (!$turnstileSecret && $recaptchaSecret && $request->filled('g-recaptcha-response')) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $recaptchaSecret,
                'response' => $request->input('g-recaptcha-response'),
            ]);
            $result = $response->json();

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
