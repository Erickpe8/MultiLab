<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentResolution extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['incident_id','resolution','resolved_by','resolved_at'];
    protected $casts = ['resolved_at'=>'datetime'];

    public function incident(): BelongsTo { return $this->belongsTo(Incident::class); }
    public function resolver(): BelongsTo { return $this->belongsTo(User::class, 'resolved_by'); }
}