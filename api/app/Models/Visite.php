<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visite extends Model
{
    protected $fillable = ['patient_id', 'docteur_id', 'diagnostique_id', 'date_heure'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'patient_id');
    }

    public function docteur(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'docteur_id');
    }

    public function diagnostique(): BelongsTo
    {
        return $this->belongsTo(Diagnostique::class);
    }
}
