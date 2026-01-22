<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid','model_id','location_id','asset_tag','serial','status',
        'purchase_date','warranty_until','qr_text','notes', 'is_unique_item'
    ];

    protected $casts = [
        'is_unique_item' => 'boolean'
    ];

    public function model(): BelongsTo { return $this->belongsTo(DeviceModel::class, 'model_id'); }
    public function location(): BelongsTo { return $this->belongsTo(Location::class); }
    public function photos(): HasMany { return $this->hasMany(AssetPhoto::class); }
    public function documents(): HasMany { return $this->hasMany(AssetDocument::class); }

    // Scope para filtrar elementos únicos
    public function scopeUniqueItems($query)
    {
        return $query->where('is_unique_item', true);
    }

    // Scope para filtrar activos regulares
    public function scopeRegularAssets($query)
    {
        return $query->where('is_unique_item', false);
    }
}