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
    {!! html()->form('POST', url('creation/membre'))->open() !!}
    <div class="formgroup">
        {!! html()->label('Nom', 'nom') !!}
        {!! html()->text('nom')->class('formcontrol')->required() !!}
    </div>
    <div class="formgroup">
        {!! html()->label('Prenom', 'prenom') !!}
        {!! html()->text('prenom')->class('formcontrol')->required() !!}
    </div>
    <div class="formgroup">
        {!! html()->label('Adresse électronique', 'adresse') !!}
        {!! html()->email('adresse')->class('formcontrol')->required() !!}
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
