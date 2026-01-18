<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomObservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_loan_id',
        'recorded_by',
        'type',
        'description',
        'severity',
        'metadata',
        'evidence_path',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(ClassroomLoan::class, 'classroom_loan_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
