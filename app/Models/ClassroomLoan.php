<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassroomLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_code',
        'requested_by',
        'approved_by',
        'subject',
        'purpose',
        'status',
        'scheduled_start_at',
        'scheduled_end_at',
        'actual_start_at',
        'actual_end_at',
        'pc_required',
        'pc_in_use',
        'pc_unavailable',
        'workstations_snapshot',
        'incidents_count',
        'access_instructions',
        'notes',
    ];

    protected $casts = [
        'scheduled_start_at' => 'datetime',
        'scheduled_end_at' => 'datetime',
        'actual_start_at' => 'datetime',
        'actual_end_at' => 'datetime',
        'workstations_snapshot' => 'array',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function observations(): HasMany
    {
        return $this->hasMany(ClassroomObservation::class);
    }

    public function workstations(): BelongsToMany
    {
        return $this->belongsToMany(ClassroomWorkstation::class, 'classroom_loan_workstations')
            ->withPivot(['status', 'metrics', 'assigned_user', 'notes'])
            ->withTimestamps();
    }
}
