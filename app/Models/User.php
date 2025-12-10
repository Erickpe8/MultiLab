<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        // Nombre segmentado
        'first_name',
        'middle_name',
        'first_surname',
        'second_surname',

        // Identificación académica
        'email',
        'password',

        // Datos opcionales
        'document_type',
        'document_number',
        'phone',

        // Estado
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    protected $appends = ['name'];

    /**
     * Nombre completo del usuario
     */
    public function getNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->first_surname,
            $this->second_surname
        ])));
    }

    /**
     * Setter opcional para asignar nombre completo desde 1 input
     */
    public function setNameAttribute($value): void
    {
        $parts = preg_split('/\s+/', trim($value));

        $this->attributes['first_name']     = $parts[0] ?? '';
        $this->attributes['middle_name']    = $parts[1] ?? '';
        $this->attributes['first_surname']  = $parts[2] ?? '';
        $this->attributes['second_surname'] = $parts[3] ?? '';
    }

    /**
     * Texto legible del rol
     */
    public function getDisplayRoleLabelAttribute(): string
    {
        if ($this->roles->isNotEmpty()) {
            return ucfirst($this->roles->first()->name);
        }

        return 'Usuario';
    }
}