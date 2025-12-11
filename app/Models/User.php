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

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        // Nombre segmentado
        'first_name',
        'middle_name',
        'first_surname',
        'second_surname',

        // Identificación
        'email',
        'password',
        'document_type',
        'document_number',
        'phone',

        // Estado
        'is_active',
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts automáticos
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Atributos calculados agregados automáticamente
     */
    protected $appends = ['name', 'display_role_label'];

    /**
     * Accesor: nombre completo del usuario
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
     * Mutador: asignar nombre completo desde una sola cadena
     */
    public function setNameAttribute($value): void
    {
        $parts = preg_split('/\s+/', trim($value));

        $this->attributes['first_name']     = $parts[0] ?? null;
        $this->attributes['middle_name']    = $parts[1] ?? null;
        $this->attributes['first_surname']  = $parts[2] ?? null;
        $this->attributes['second_surname'] = $parts[3] ?? null;
    }

    /**
     * Accesor: etiqueta legible del rol
     */
    public function getDisplayRoleLabelAttribute(): string
    {
        if ($this->roles->isNotEmpty()) {
            return ucfirst($this->roles->first()->name);
        }

        return 'Usuario';
    }
}