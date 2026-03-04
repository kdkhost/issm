@extends('errors.layout')

@section('title', '404 - Página Não Encontrada')
@section('code', '404')
@section('heading', 'Página não encontrada')
@section('message', 'A página que você está procurando pode ter sido removida, renomeada ou está temporariamente indisponível.')

@section('icon')
<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
@endsection
