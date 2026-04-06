<h2> Infos Membre </h2>
<h3>
{{ $un_membre->prenom }} {{ $un_membre->nom }}
</h3>
<div class='h2'>
    @if(Auth::check() && (Auth::user()->is_validated || Auth::user()->is_admin))
        {{ $un_membre->adresse }}
    @else
        <i>Email masqué (en attente de validation)</i>
    @endif
</div>
<hr>
<h3>Biographie</h3>
<p>{{ $un_membre->biographie?->description ?? 'Aucun texte biographique.' }}</p>
