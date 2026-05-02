<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Représente un diagnostique clinique associé à un patient.
 */
class Diagnostique extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'commentaire', 'description', 'date_heure', 'priorite'];

    /**
     * Retourne le compte patient lié au diagnostique.
     *
     * @return BelongsTo<Compte, Diagnostique>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'patient_id');
    }
}
