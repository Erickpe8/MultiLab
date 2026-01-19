<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassroomWorkstation extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_code',
        'code',
        'label',
        'status',
        'seat_number',
        'specs',
        'notes',
    ];

    protected $casts = [
        'specs' => 'array',
    ];

    public function loans(): BelongsToMany
    {
        return $this->belongsToMany(ClassroomLoan::class, 'classroom_loan_workstations')
            ->withPivot(['status', 'metrics', 'assigned_user', 'notes'])
            ->withTimestamps();
    }
}
