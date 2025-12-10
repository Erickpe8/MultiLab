<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceModel extends EloquentModel
{
    use HasFactory;

    protected $table = 'models';
    public $timestamps = false;
    protected $fillable = ['brand_id','category_id','name'];

    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function assets(): HasMany { return $this->hasMany(Asset::class, 'model_id'); }
}