@extends('errors.layout')

@section('title', '401 - Não Autorizado')
@section('code', '401')
@section('heading', 'Não autorizado')
@section('message', 'Você precisa estar autenticado para acessar esta página. Faça login e tente novamente.')

@section('icon')
<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
@endsection
