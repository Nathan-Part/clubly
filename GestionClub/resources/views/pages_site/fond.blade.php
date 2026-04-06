<!DOCTYPE html>
<html>
<head>
    @vite('resources/css/mon_style.css')
    @yield('entete')
    <title>@yield('titre')</title>
</head>
<body>
    <div class="container">
        <nav class="main-menu">
            <ul>
                <li><a href="{{ url('/') }}">Accueil</a></li>
                <li><a href="{{ url('membres') }}">Membres</a></li>
                @auth
                    @php 
                        $membreExist = \App\Models\Membre::where('adresse', Auth::user()->email)->first();
                    @endphp
                    @if($membreExist)
                        <li><a href="{{ url('modifier/'.$membreExist->id) }}">Éditer mon profil</a></li>
                    @else
                        <li><a href="{{ url('creer') }}">Créer mon profil</a></li>
                    @endif
                    <li><a href="{{ url('identite') }}">Profil</a></li>
                    @if(Auth::user()->is_admin)
                        <li><a href="{{ route('admin.validations') }}"><strong>Validations</strong></a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="logout-btn">Déconnexion ({{ Auth::user()->name }})</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                    <li><a href="{{ route('register') }}">Inscription</a></li>
                @endauth
            </ul>
        </nav>
        <div class="titre_contenu">
            @yield('titre_contenu')
        </div>
        <div class="contenu">
            @yield('contenu')
        </div>
        <div class="pied_page">@yield('pied_page')</div>
    </div>
</body>
</html>
