@extends('errors.layout')

@section('title', '405 - Método Não Permitido')
@section('code', '405')
@section('heading', 'Método não permitido')
@section('message', 'O método de requisição utilizado não é suportado para esta página. Tente acessar de outra forma.')

@section('icon')
<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
@endsection
