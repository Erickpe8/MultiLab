<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_kind','asset_id','material_id','type','moved_at','quantity','unit_id',
        'reason','ref_type','ref_id'
    ];

    protected $casts = ['moved_at' => 'datetime'];

    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
    public function material(): BelongsTo { return $this->belongsTo(Material::class); }
    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
}