<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Adresse extends Model
{
    use HasFactory;

    protected $fillable = ['code_postal', 'numero', 'province', 'rue', 'ville', 'type'];

    public function comptes(): BelongsToMany
    {
        return $this->belongsToMany(Compte::class, 'adresses_comptes');
    }
}
