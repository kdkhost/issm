@extends('errors.layout')

@section('title', '408 - Tempo Esgotado')
@section('code', '408')
@section('heading', 'Tempo de requisição esgotado')
@section('message', 'O servidor demorou muito para processar sua solicitação. Verifique sua conexão com a internet e tente novamente.')

@section('icon')
<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
@endsection
