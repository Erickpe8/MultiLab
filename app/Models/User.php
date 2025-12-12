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
     * Importante para Spatie Permission.
     */
    protected string $guard_name = 'web';

    /**
     * Campos asignables.
     * MultiLab NO usa position, department, role_name, area del POA.
     */
    protected $fillable = [
        // Nombre compuesto
        'first_name',
        'middle_name',
        'first_surname',
        'second_surname',

        // Datos base
        'email',
        'password',

        // Campos institucionales opcionales
        'gender',
        'document_type',
        'document_number',
        'phone',
        'phone_extension',
        'mobile',

        // Estado
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    /**
     * Atributo virtual: name.
     */
    protected $appends = ['name', 'display_role_label'];

    /**
     * ---------------------------
     * ACCESOR name
     * ---------------------------
     */
    public function getNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->first_surname,
            $this->second_surname,
        ])));
    }

    /**
     * ---------------------------
     * MUTATOR name
     * ---------------------------
     * Divide “Nombre Apellido” en partes.
     */
    public function setNameAttribute($value): void
    {
        $value = trim((string) $value);

        if ($value === '') { return; }

        $parts = preg_split('/\s+/', $value);
        $count = count($parts);

        $this->attributes['first_name']    = $parts[0] ?? '';
        $this->attributes['middle_name']   = $parts[1] ?? '';

        if ($count === 1) {
            $this->attributes['first_surname']  = '';
            $this->attributes['second_surname'] = '';
            return;
        }

        if ($count === 2) {
            $this->attributes['first_surname']  = $parts[1];
            $this->attributes['second_surname'] = '';
            return;
        }

        if ($count === 3) {
            $this->attributes['first_surname']  = $parts[2];
            $this->attributes['second_surname'] = '';
            return;
        }

        // 4 o más palabras
        $this->attributes['first_surname']  = $parts[2] ?? '';
        $this->attributes['second_surname'] = $parts[3] ?? '';
    }

    /**
     * ---------------------------
     * SCOPES para UserManagementController
     * ---------------------------
     */

    public function scopePending($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper rápido.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Etiqueta legible del rol principal para mostrar en vistas.
     */
    public function getDisplayRoleLabelAttribute(): ?string
    {
        $roleName = $this->roles->first()?->name;

        $labels = [
            'superadmin' => 'Superadministrador del Sistema',
            'aux_admin'  => 'Auxiliar Administrativo del Laboratorio',
            'docente'    => 'Docente',
            'estudiante' => 'Estudiante',
        ];

        return $roleName ? ($labels[$roleName] ?? ucfirst($roleName)) : 'Sin rol asignado';
    }
}
