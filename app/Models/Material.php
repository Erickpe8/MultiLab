<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid','category_id','sku','name','unit_id','has_expiry','expiry_date',
        'min_stock','max_stock','current_stock'
    ];

    protected $casts = ['expiry_date' => 'date', 'has_expiry' => 'boolean'];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
}