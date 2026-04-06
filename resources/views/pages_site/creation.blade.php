@extends('pages_site/fond')
@section('entete')
@stop
@section('titre')
Club des Usagers de l'Espace galactique
@stop
@section('titre_contenu')
Création d'un nouveau membre
@stop
@section('contenu')
<div class="formgroup">
    {!! html()->form('POST', url('enregistrer'))->open() !!}
    <div class="formgroup">
        {{ html()->label('Prénom du membre', 'prenom') }}
        {{ html()->text('prenom')->class('formcontrol') }}
    </div>
    <div class="formgroup">
        {{ html()->label('Nom du membre', 'nom') }}
        {{ html()->text('nom', $user->name)->class('formcontrol') }}
    </div>
    <div class="formgroup">
        {{ html()->label('Identifiant Email (automatique)', 'adresse') }}
        {{ html()->text('adresse', $user->email)->class('formcontrol')->attribute('readonly', true) }}
    </div>
    <p>
    </p>
    {!! html()->submit("Creation membre") !!}
    {!! html()->form()->close() !!}
</div>
@stop
@section('pied_page')
LP3MI 2026
@stop
