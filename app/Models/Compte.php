<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'date_naissance',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function personnel(): HasOne
    {
        return $this->hasOne(Personnel::class);
    }

    public function triage(): HasOne
    {
        return $this->hasOne(Triage::class);
    }

    public function adresses(): BelongsToMany
    {
        return $this->belongsToMany(Adresse::class, 'adresses_comptes')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function telephones(): BelongsToMany
    {
        return $this->belongsToMany(Telephone::class, 'telephones_comptes');
    }

    public function visites(): HasMany
    {
        return $this->hasMany(Visite::class, 'patient_id');
    }
}
