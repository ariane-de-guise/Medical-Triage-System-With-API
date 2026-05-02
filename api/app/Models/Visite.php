<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Représente une visite médicale liant patient, médecin et diagnostique.
 */
class Visite extends Model
{
    protected $fillable = ['patient_id', 'docteur_id', 'diagnostique_id', 'date_heure'];

    /**
     * Retourne le compte patient concerné par la visite.
     *
     * @return BelongsTo<Compte, Visite>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'patient_id');
    }

    /**
     * Retourne le compte médecin responsable de la visite.
     *
     * @return BelongsTo<Compte, Visite>
     */
    public function docteur(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'docteur_id');
    }

    /**
     * Retourne le diagnostique associé à la visite.
     *
     * @return BelongsTo<Diagnostique, Visite>
     */
    public function diagnostique(): BelongsTo
    {
        return $this->belongsTo(Diagnostique::class);
    }
}
