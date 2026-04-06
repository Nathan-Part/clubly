@extends('pages_site/fond')

@section('titre')
    Opération réussie
@stop

@section('titre_contenu')
    Confirmation
@stop

@section('contenu')
    <p class="h1">Succès !</p>
    <p>Le membre a été {{ $action }} avec succès.</p>
    <p><a href="{{ url('membres') }}">Retour à la liste des membres</a></p>
@stop

@section('pied_page')
    LP3MI 2026
@stop
