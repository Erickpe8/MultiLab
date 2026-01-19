<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomLoanWorkstation extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_loan_id',
        'classroom_workstation_id',
        'status',
        'metrics',
        'assigned_user',
        'notes',
    ];

    protected $casts = [
        'metrics' => 'array',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(ClassroomLoan::class);
    }

    public function workstation(): BelongsTo
    {
        return $this->belongsTo(ClassroomWorkstation::class);
    }
}
