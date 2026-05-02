<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Représente une adresse postale associable à un ou plusieurs comptes.
 */
class Adresse extends Model
{
    use HasFactory;

    protected $fillable = ['code_postal', 'numero', 'province', 'rue', 'ville', 'type'];

    /**
     * Retourne les comptes liés à cette adresse.
     *
     * @return BelongsToMany<Compte>
     */
    public function comptes(): BelongsToMany
    {
        return $this->belongsToMany(Compte::class, 'adresses_comptes');
    }
}
