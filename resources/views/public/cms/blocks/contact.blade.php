{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $settings = $section->settings ?? [];
    $title = $settings["title"] ?? "Entre em Contato";
    $description = $settings["description"] ?? "Estamos prontos para ouvir você.";
    $bgColor = $settings["background_color"] ?? "bg-white";
    $email = $settings["email"] ?? \App\Models\Setting::get("contact_email");
    $phone = $settings["phone"] ?? \App\Models\Setting::get("contact_phone");
    $address = $settings["address"] ?? \App\Models\Setting::get("contact_address");
    $showForm = $settings["show_form"] ?? true;
@endphp
<section class="{{ $bgColor }} py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            @if($title)
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">{{ $title }}</h2>
            @endif
            @if($description)
            <p class="text-lg text-gray-600 mt-4 max-w-2xl mx-auto">{{ $description }}</p>
            @endif
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
            @if($showForm)
            <div class="bg-gray-50 rounded-2xl p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ route("contact.store") }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                            <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail *</label>
                            <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assunto *</label>
                        <input type="text" name="subject" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensagem *</label>
                        <textarea name="message" rows="4" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-700 text-white font-bold px-6 py-3 rounded-lg hover:bg-green-800 transition-colors shadow-md">
                        Enviar Mensagem
                    </button>
                </form>
            </div>
            @endif
            <div class="{{ $showForm ? "" : "mx-auto max-w-lg" }}">
                <div class="space-y-6">
                    @if($email)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">E-mail</h4>
                            <a href="mailto:{{ $email }}" class="text-green-700 hover:text-green-800">{{ $email }}</a>
                        </div>
                    </div>
                    @endif
                    @if($phone)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Telefone</h4>
                            <a href="tel:{{ preg_replace('/\D/', '', $phone) }}" class="text-green-700 hover:text-green-800">{{ $phone }}</a>
                        </div>
                    </div>
                    @endif
                    @if($address)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Endereço</h4>
                            <p class="text-gray-600">{{ $address }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @if(($settings["show_map"] ?? false) && ($settings["map_embed"] ?? false))
                <div class="mt-8 rounded-xl overflow-hidden shadow-sm">
                    {!! $settings["map_embed"] !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
