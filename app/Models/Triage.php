<?php

namespace App\Models;

use App\Algorithms\Roles;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Triage extends Model
{
    protected $fillable = ['compte_id', 'position', 'priority', 'personnel_id', 'file_attente'];

    protected $casts = [
        'file_attente' => Roles::class,
    ];

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }
}
