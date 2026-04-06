@extends('pages_site/fond')

@section('entete')
@stop

@section('titre')
Liste des membres
@stop

@section('titre_contenu')
Membres du club
@stop

@section('contenu')
    @foreach ($les_membres as $membre)
        <div class="h1">{{ $membre->prenom }} {{ $membre->nom }}</div>
        <div class="h2">
            @if(Auth::check() && (Auth::user()->is_validated || Auth::user()->is_admin))
                {{ $membre->adresse }}
            @else
                <i>Email masqué (en attente de validation)</i>
            @endif
        </div>
        <br>
    @endforeach
@stop

@section('pied_page')
@stop
