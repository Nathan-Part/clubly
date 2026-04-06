@foreach ($les_membres as $membre)
<h3>
{{ $membre->prenom }} {{ $membre->nom }}
</h3>
<div class='h2'>
@if(Auth::check() && (Auth::user()->is_validated || Auth::user()->is_admin))
    {{ $membre->adresse }}
@else
    <i>Email masqué (en attente de validation)</i>
@endif
</div>
@endforeach
