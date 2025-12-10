<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id','material_id','severity','status','reported_at','resolved_at',
        'description','evidence_path'
    ];
    protected $casts = ['reported_at'=>'datetime','resolved_at'=>'datetime'];

    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
    public function material(): BelongsTo { return $this->belongsTo(Material::class); }
    public function resolutions(): HasMany { return $this->hasMany(IncidentResolution::class); }
}