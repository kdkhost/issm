@extends('errors.layout')

@section('title', '429 - Muitas Requisições')
@section('code', '429')
@section('heading', 'Muitas requisições')
@section('message', 'Você fez muitas solicitações em um curto período. Por favor, aguarde alguns instantes antes de tentar novamente.')

@section('icon')
<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
@endsection
