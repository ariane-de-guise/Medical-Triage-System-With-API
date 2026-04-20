<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Telephone extends Model
{
    use HasFactory;

    protected $fillable = ['numero'];
    public function comptes(): BelongsToMany
    {
        return $this->belongsToMany(Compte::class, 'telephones_comptes');
    }
}
