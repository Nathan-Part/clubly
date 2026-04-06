@extends('pages_site.fond')

@section('titre', 'Attente de validation')

@section('titre_contenu')
    <h1>Compte en attente de validation</h1>
@endsection

@section('contenu')
    <div style="background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 20px; border-radius: 5px; margin-top: 20px;">
        <p>Votre compte a été créé avec succès, mais il doit être validé par un administrateur avant que vous puissiez accéder au contenu du site.</p>
        <p>L'administrateur consultera régulièrement les nouvelles demandes. Merci de votre patience.</p>
    </div>
    
    <div style="margin-top: 20px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background-color: #6c757d; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                Se déconnecter
            </button>
        </form>
    </div>
@endsection
