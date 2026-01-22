<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'user_id',
        'quantity',
        'needed_at',
        'planned_return_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'needed_at' => 'datetime',
        'planned_return_at' => 'datetime',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
