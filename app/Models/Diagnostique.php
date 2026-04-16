<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnostique extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'commentaire', 'description', 'date_heure', 'priorite'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'patient_id');
    }
}
