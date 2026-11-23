<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Membre;
use Illuminate\Support\Facades\Auth;

class ControleurMembres extends Controller
{
    // des variables
    protected $les_membres;
    protected $soumissions;

    public function __construct(Membre $membres, Request $requetes) {
        $this->les_membres = $membres;
        $this->soumissions = $requetes;
    }

    public function index() {
        $les_membres = $this->les_membres->all();
        return view('pages_site/consultation_edition', compact('les_membres'));
    }

    public function indexCss()
    {
        $les_membres = $this->les_membres->all();
        return view('pages_site/membrescss', compact('les_membres'));
    }

    public function afficher($numero) {
        $un_membre = $this->les_membres->find($numero);
        if (!$un_membre) {
            return response()->view('pages_site/erreur', ['message' => 'Ce membre n\'existe pas.'], 404);
        }
        return view('pages_site/membre', compact('un_membre'));
    }

    public function creer() {
        $exist = $this->les_membres->where('user_id', Auth::id())
            ->orWhere('adresse', Auth::user()->email)
            ->first();
        if ($exist) {
            return redirect('modifier/' . $exist->id)->with('info', 'Vous avez déjà un profil membre, vous pouvez le modifier ici.');
        }
        return view('pages_site/creation', ['user' => Auth::user()]);
    }

    public function enregistrer(Request $request) {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
        ]);

        // Un utilisateur ne peut avoir qu'un seul profil membre.
        $exist = $this->les_membres->where('user_id', Auth::id())
            ->orWhere('adresse', Auth::user()->email)
            ->first();
        if ($exist) {
            return redirect('modifier/' . $exist->id)->with('info', 'Vous avez déjà un profil membre, vous pouvez le modifier ici.');
        }

        $membre = new Membre();
        $membre->nom = $request->nom;
        $membre->prenom = $request->prenom;
        // Le lien de propriété et l'adresse sont déterminés par le compte
        // authentifié, jamais par des données fournies par le client.
        $membre->user_id = Auth::id();
        $membre->adresse = Auth::user()->email;
        $membre->save();
        return view('pages_site/confirmation', ['action' => 'créé']);
    }

    /**
     * Détermine si l'utilisateur connecté est autorisé à gérer ce membre.
     * Autorisé si : il en est le propriétaire (user_id), OU c'est une ligne
     * legacy (user_id null) dont l'adresse correspond à son email, OU il est
     * administrateur. Pour une ligne legacy revendiquée par son propriétaire
     * via l'email, on lui attache définitivement le user_id.
     */
    protected function peutGerer($un_membre) {
        if (!Auth::check()) {
            return false;
        }
        $utilisateur = Auth::user();

        if ($un_membre->user_id !== null && $un_membre->user_id === $utilisateur->id) {
            return true;
        }

        if ($utilisateur->is_admin) {
            return true;
        }

        // Ligne legacy sans propriétaire : repli sur la correspondance email.
        if ($un_membre->user_id === null && $utilisateur->email === $un_membre->adresse) {
            $un_membre->user_id = $utilisateur->id;
            $un_membre->save();
            return true;
        }

        return false;
    }

    public function editer($numero) {
        $un_membre = $this->les_membres->find($numero);
        if (!$un_membre) {
            return response()->view('pages_site/erreur', ['message' => 'Ce membre n\'existe pas.'], 404);
        }
        if (!$this->peutGerer($un_membre)) {
            return redirect('membres')->with('error', 'Vous n\'êtes pas autorisé à modifier ce membre.');
        }
        return view('pages_site/edition', compact('un_membre'));
    }

    public function miseAJour($numero) {
        $un_membre = $this->les_membres->find($numero);
        if (!$un_membre) {
            return response()->view('pages_site/erreur', ['message' => 'Ce membre n\'existe pas.'], 404);
        }
        if (!$this->peutGerer($un_membre)) {
             return redirect('membres')->with('error', 'Vous n\'êtes pas autorisé à mettre à jour ce membre.');
        }
        $la_soumission = $this->soumissions;
        $la_soumission->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'biographie' => ['nullable', 'string', 'max:5000'],
        ]);
        $un_membre->nom = $la_soumission->nom;
        $un_membre->prenom = $la_soumission->prenom;
        // On ne met JAMAIS à jour l'adresse ni le user_id depuis la requête :
        // ils restent liés au propriétaire du profil.
        $un_membre->save();

        // Update or Create biography
        $un_membre->biographie()->updateOrCreate(
            ['membre_id' => $un_membre->id],
            ['description' => $la_soumission->biographie]
        );

        return view('pages_site/confirmation', ['action' => 'modifié']);
    }

    public function identite() {
        if (Auth::check())
        {
            $utilisateur = Auth::user();
            $id = Auth::id();
            return view('pages_site/identite',compact('utilisateur','id'));
        }
        else
            echo "<h1>Accès non autorisé</h1>";
    }

    public function acces_protege() {
        if (Auth::check()) {
            $infos_utilisateur = Auth::user();
            $utilisateur = $infos_utilisateur->name;
            echo "<h1>Utilisateur authentifié : ".$utilisateur."</h1>";
        } else {
            echo "<h1>Accès non autorisé</h1>";
        }
    }
}
