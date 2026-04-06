@extends('pages_site/fond')

@section('titre')
    Erreur
@stop

@section('titre_contenu')
    Erreur
@stop

@section('contenu')
    <p class="h1">Désolé !</p>
    <p>{{ $message }}</p>
    <p><a href="{{ url('membres') }}">Retour à la liste des membres</a></p>
@stop

@section('pied_page')
    LP3MI 2026
@stop
