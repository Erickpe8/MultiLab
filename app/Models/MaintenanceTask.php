<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceTask extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['maintenance_id','description','done'];

    public function maintenance(): BelongsTo { return $this->belongsTo(Maintenance::class); }
}