@extends("layouts.admin")
@section("title", "Meu Perfil")
@section("page-title", "Meu Perfil")
@section("page-subtitle", "Gerencie suas informações e senha de acesso")

@section("content")
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Card de identidade --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="text-white font-black text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <h2 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h2>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_admin ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                {{ $user->is_admin ? 'Administrador' : 'Usuário' }}
            </span>
            <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400 text-left space-y-1">
                <p><span class="font-medium text-gray-500">Membro desde:</span> {{ $user->created_at->format('d/m/Y') }}</p>
                <p><span class="font-medium text-gray-500">Último acesso:</span> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Formulários --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Dados pessoais --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-5 pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Dados Pessoais
            </h3>

            @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium text-sm transition-colors">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        {{-- Alterar senha --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-5 pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Alterar Senha
            </h3>

            <form method="POST" action="{{ route('admin.profile.password') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Senha Atual</label>
                        <input type="password" name="current_password"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('current_password') border-red-400 @enderror"
                            autocomplete="current-password">
                        @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
                        <input type="password" name="password"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-400 @enderror"
                            autocomplete="new-password">
                        <p class="text-gray-400 text-xs mt-1">Mínimo 8 caracteres, letras maiúsculas, minúsculas e números.</p>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                            autocomplete="new-password">
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 font-medium text-sm transition-colors">
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
