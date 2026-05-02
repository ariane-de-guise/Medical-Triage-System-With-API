<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Représente un numéro de téléphone relié à un ou plusieurs comptes.
 */
class Telephone extends Model
{
    use HasFactory;

    protected $fillable = ['numero'];

    /**
     * Retourne les comptes liés à ce numéro.
     *
     * @return BelongsToMany<Compte>
     */
    public function comptes(): BelongsToMany
    {
        return $this->belongsToMany(Compte::class, 'telephones_comptes');
    }
}
