<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDocument extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id','doc_type','path'];

    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
}