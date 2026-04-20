<?php

namespace App\Models;

use App\Algorithms\Roles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personnel extends Model
{
    protected $fillable = ['compte_id', 'role'];

    protected $casts = [
        'role' => Roles::class,
    ];

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    public function triages() : HasMany
    {
        return $this->hasMany(Triage::class, 'personnel_id');
    }
}
