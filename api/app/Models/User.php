<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modèle utilisateur authentifiable de l'API.
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasApiTokens;

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];


    /**
     * Retourne le compte métier associé à l'utilisateur.
     *
     * @return HasOne|User
     */
    public function compte(): HasOne|User
    {
        return $this->hasOne(Compte::class);
    }
}
