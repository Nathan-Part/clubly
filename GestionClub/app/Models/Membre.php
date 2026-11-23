<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    protected $fillable = ['user_id', 'nom', 'prenom', 'adresse'];

    public function biographie()
    {
        return $this->hasOne(Biographie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
