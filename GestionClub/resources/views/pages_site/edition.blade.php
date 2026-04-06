@extends('pages_site/fond')

@section('entete')
@stop

@section('titre')
Club des Usagers de l'Espace galactique
@stop

@section('titre_contenu')
Modification des infos du membre
@stop

@section('contenu')
<div class="formgroup">
    {!! html()->model($un_membre)->form('PATCH', url('miseAJour', $un_membre->id))->open() !!}
    <div class="formgroup">
        {{ html()->label('Nom', 'nom') }}
        {{ html()->text('nom')->class('formcontrol') }}
    </div>
    <div class="formgroup">
        {{ html()->label('Prenom :', 'prenom') }}
        {{ html()->text('prenom')->class('formcontrol') }}
    </div>
    <div class="formgroup">
        {{ html()->label('Adresse électronique', 'adresse') }}
        {{ html()->text('adresse')->class('formcontrol') }}
    </div>
    <p>
    </p>
    {!! html()->submit("Modifier membre", ['class' => 'btn btn-info']) !!}
    {!! html()->form()->close() !!}
</div>
@stop

@section('pied_page')
LP3MI 2026
@stop