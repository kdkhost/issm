@extends("layouts.app")
@section("title", "Fale Conosco - ISSM")

@push("styles")
<style>
.page-stat {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.1); padding:6px 14px; border-radius:24px;
    font-size:13px; color:#fff; font-weight:500;
}
.page-stat svg { width:16px; height:16px; opacity:.8; }

.contact-card {
    background: #fff;
    border-radius: 24px;
    padding: 32px;
    text-align: center;
    border: 1px solid #f1f5f9;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}
.contact-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.contact-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}
.map-container {
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    border: 8px solid #fff;
}
.grecaptcha-badge { visibility: hidden !important; }
</style>
@endpush

@php
$cmsPage = cms_page('contact');
@endphp

@section("content")

@if($cmsPage && $cmsPage->use_custom_html)
    {!! $cmsPage->custom_html !!}
@else

{{-- Hero Banner Premium --}}
<div style="background:linear-gradient(135deg,#166534 0%,#15803d 50%,#059669 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#86efac;margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ cms('contact', 'hero', 'breadcrumb', 'Contato') }}</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            {{ cms('contact', 'hero', 'title', 'Fale Conosco') }}
        </h1>
        <p style="font-size:16px;color:#bbf7d0;max-width:600px;margin-bottom:20px;">
            {{ cms('contact', 'hero', 'subtitle', 'Estamos prontos para ouvir você. Entre em contato para parcerias, dúvidas ou informações sobre o ISSM.') }}
        </p>
    </div>
</div>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" style="margin-bottom: 80px;">
            {{-- Endereço --}}
            <div class="contact-card">
                <div class="contact-icon bg-green-100 text-green-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ cms('contact', 'info', 'visit_title', 'Visite-nos') }}</h3>
                <p class="text-gray-500 leading-relaxed">{{ $settings['contact_address'] ?: 'Serra do Mendanha, Rio de Janeiro - RJ' }}</p>
            </div>

            {{-- Email --}}
            <div class="contact-card">
                <div class="contact-icon bg-blue-100 text-blue-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ cms('contact', 'info', 'email_title', 'E-mail') }}</h3>
                <p class="text-gray-500 leading-relaxed">{{ $settings['contact_email'] ?: 'contato@issm.org.br' }}</p>
            </div>

            {{-- Telefone --}}
            <div class="contact-card">
                <div class="contact-icon bg-purple-100 text-purple-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ cms('contact', 'info', 'phone_title', 'Telefone') }}</h3>
                <p class="text-gray-500 leading-relaxed">{{ $settings['contact_phone'] ?: '(21) 99999-9999' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-stretch">
            {{-- Formulário --}}
            <div class="bg-white rounded-[32px] p-8 lg:p-12 shadow-xl border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 mb-8">{{ cms('contact', 'form', 'title', 'Envie uma Mensagem') }}</h2>
                    
                    <form id="contact-form-page" action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nome Completo</label>
                                <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-green-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Seu E-mail</label>
                                <input type="email" name="email" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-green-500 focus:bg-white transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Assunto</label>
                            <input type="text" name="subject" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-green-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mensagem</label>
                            <textarea name="message" rows="5" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-green-500 focus:bg-white transition-all resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-black py-4 rounded-xl shadow-lg shadow-green-900/20 transition-all flex items-center justify-center gap-2">
                            <span>{{ cms('contact', 'form', 'submit_text', 'Enviar agora') }}</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Mapa e Informações (Padronizado) --}}
            <div class="bg-white rounded-[32px] p-8 lg:p-12 shadow-xl border border-gray-100 flex flex-col">
                <h2 class="text-3xl font-black text-gray-900 mb-8">{{ cms('contact', 'form', 'map_title', 'Nossa Localização') }}</h2>
                
                @if($settings['contact_map_embed'])
                <div class="map-container mb-8 flex-grow">
                    <div style="height: 100%; min-height: 350px;">
                        {!! html_entity_decode($settings['contact_map_embed']) !!}
                    </div>
                </div>
                @endif
                
                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-xl font-black text-gray-900 mb-2">Horário de Atendimento</h4>
                    <p class="text-gray-600 font-medium leading-relaxed">
                        {{ cms('contact', 'info', 'hours', 'Segunda a Sexta: 08:00 às 17:00 • Sábados: 08:00 às 12:00') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
@php $recaptchaKey = \App\Models\Setting::get('recaptcha_site_key'); @endphp
@if($recaptchaKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaKey }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contact-form-page');
        if (!contactForm) return;

        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span>Enviando...</span>';
            }

            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $recaptchaKey }}', {action: 'contact'}).then(function(token) {
                    let input = form.querySelector('input[name="g-recaptcha-response"]');
                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        form.appendChild(input);
                    }
                    input.value = token;
                    form.submit();
                });
            });
        });
    });
</script>
@endif
@endpush

@endif

@endsection
