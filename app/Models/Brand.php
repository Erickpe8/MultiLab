<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name'];

    public function deviceModels(): HasMany { return $this->hasMany(DeviceModel::class, 'brand_id'); }
}