<?php

namespace App\Models;

use App\Algorithms\Roles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Représente un membre du personnel connecté au système de triage.
 */
class Personnel extends Model
{
    protected $fillable = ['compte_id', 'role'];

    protected $casts = [
        'role' => Roles::class,
    ];

    /**
     * Retourne le compte associé à ce personnel.
     *
     * @return BelongsTo<Compte, Personnel>
     */
    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    /**
     * Retourne les éléments de triage assignés à ce personnel.
     *
     * @return HasMany<Triage>
     */
    public function triages(): HasMany
    {
        return $this->hasMany(Triage::class, 'personnel_id');
    }
}
