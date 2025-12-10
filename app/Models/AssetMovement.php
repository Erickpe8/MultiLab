<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMovement extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id','from_location_id','to_location_id','by_user_id','moved_at','notes'];

    protected $casts = ['moved_at' => 'datetime'];

    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
    public function from(): BelongsTo { return $this->belongsTo(Location::class, 'from_location_id'); }
    public function to(): BelongsTo { return $this->belongsTo(Location::class, 'to_location_id'); }
    public function byUser(): BelongsTo { return $this->belongsTo(User::class, 'by_user_id'); }
}