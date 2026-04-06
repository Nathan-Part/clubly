@extends('pages_site/fond')
@section('entete')
@stop
@section('titre')
Club des Usagers de l'Espace galactique
@stop
@section('titre_contenu')
Liste des membres
@stop
@section('contenu')
@foreach ($les_membres as $membre)
    <h3>
        @auth
            @if(Auth::user()->email === $membre->adresse)
                <a href="{{ url('/modifier/'.$membre->id) }}"> {{ $membre->prenom }} {{ $membre->nom }}</a>
            @else
                {{ $membre->prenom }} {{ $membre->nom }}
            @endif
        @else
            {{ $membre->prenom }} {{ $membre->nom }}
        @endauth
    </h3>
    <div class='h2'>
        @if(Auth::check() && (Auth::user()->is_validated || Auth::user()->is_admin))
            {{ $membre->adresse }}
        @elseif(Auth::check())
            <i>[Adresse masquée - En attente de validation]</i>
        @else
            <i>[Adresse masquée - Connectez-vous]</i>
        @endif
    </div>
@endforeach
<a href="{{ url('/creer') }}"> Créer nouveau membre </a>
@stop
@section('pied_page')
LP3MI 2026
@stop
