<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Représente le dossier principal d'une personne dans le système.
 *
 * @property mixed $latestVisite
 */
class Compte extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'date_naissance',
    ];

    /**
     * Retourne l'utilisateur authentifiable lié au compte.
     *
     * @return BelongsTo<User, Compte>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retourne l'enregistrement de personnel actif du compte.
     *
     * @return HasOne<Personnel>
     */
    public function personnel(): HasOne
    {
        return $this->hasOne(Personnel::class);
    }

    /**
     * Retourne l'entrée de triage courante du compte.
     *
     * @return HasOne<Triage>
     */
    public function triage(): HasOne
    {
        return $this->hasOne(Triage::class);
    }

    /**
     * Retourne les adresses associées au compte.
     *
     * @return BelongsToMany<Adresse>
     */
    public function adresses(): BelongsToMany
    {
        return $this->belongsToMany(Adresse::class, 'adresses_comptes');
    }

    /**
     * Retourne les téléphones associés au compte.
     *
     * @return BelongsToMany<Telephone>
     */
    public function telephones(): BelongsToMany
    {
        return $this->belongsToMany(Telephone::class, 'telephones_comptes');
    }

    /**
     * Retourne l'historique des visites comme patient.
     *
     * @return HasMany<Visite>
     */
    public function visites(): HasMany
    {
        return $this->hasMany(Visite::class, 'patient_id');
    }

    /**
     * Retourne la visite la plus récente du patient.
     *
     * @return HasOne<Visite>
     */
    public function latestVisite(): HasOne
    {
        return $this->hasOne(Visite::class, 'patient_id')->latestOfMany('date_heure');
    }
}
