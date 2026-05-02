<?php

namespace App\Models;

use App\Algorithms\Roles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Représente une entrée de file d'attente de triage pour un patient.
 */
class Triage extends Model
{
    protected $fillable = ['compte_id', 'position', 'priority', 'personnel_id', 'file_attente'];

    protected $casts = [
        'file_attente' => Roles::class,
    ];

    /**
     * Retourne le compte patient associé à cette entrée de triage.
     *
     * @return BelongsTo<Compte, Triage>
     */
    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    /**
     * Retourne le personnel assigné à cette entrée de triage.
     *
     * @return BelongsTo<Personnel, Triage>
     */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }
}
