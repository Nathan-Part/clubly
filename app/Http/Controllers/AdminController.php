<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function validations()
    {
        $users = \App\Models\User::where('is_validated', false)->where('is_admin', false)->get();
        return view('pages_site.admin_validations', compact('users'));
    }

    public function valider($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->is_validated = true;
        $user->save();

        return redirect()->route('admin.validations')->with('success', 'L\'utilisateur a été validé avec succès.');
    }
}
