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
        $exist = $this->les_membres->where('adresse', Auth::user()->email)->first();
        if ($exist) {
            return redirect('modifier/' . $exist->id)->with('info', 'Vous avez déjà un profil membre, vous pouvez le modifier ici.');
        }
        return view('pages_site/creation', ['user' => Auth::user()]);
    }

    public function enregistrer(Request $request) {
        $membre = new Membre();
        $membre->nom = $request->nom;
        $membre->adresse = $request->adresse;
        $membre->prenom = $request->prenom;
        $membre->save();
        return view('pages_site/confirmation', ['action' => 'créé']);
    }

    public function editer($numero) {
        $un_membre = $this->les_membres->find($numero);
        if (!$un_membre) {
            return response()->view('pages_site/erreur', ['message' => 'Ce membre n\'existe pas.'], 404);
        }
        if (!Auth::check() || Auth::user()->email !== $un_membre->adresse) {
            return redirect('membres')->with('error', 'Vous n\'êtes pas autorisé à modifier ce membre.');
        }
        return view('pages_site/edition', compact('un_membre'));
    }

    public function miseAJour($numero) {
        $un_membre = $this->les_membres->find($numero);
        if (!$un_membre) {
            return response()->view('pages_site/erreur', ['message' => 'Ce membre n\'existe pas.'], 404);
        }
        if (!Auth::check() || Auth::user()->email !== $un_membre->adresse) {
             return redirect('membres')->with('error', 'Vous n\'êtes pas autorisé à mettre à jour ce membre.');
        }
        $la_soumission = $this->soumissions;
        $un_membre->nom = $la_soumission->nom;
        $un_membre->prenom = $la_soumission->prenom;
        $un_membre->adresse = $la_soumission->adresse;
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
