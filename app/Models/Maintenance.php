<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id','type','start_at','end_at','provider','cost','status','result'];
    protected $casts = ['start_at'=>'datetime','end_at'=>'datetime','cost'=>'decimal:2'];

    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
    public function tasks(): HasMany { return $this->hasMany(MaintenanceTask::class); }
}