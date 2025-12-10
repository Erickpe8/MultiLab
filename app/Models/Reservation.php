<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','location_id','start_at','end_at','status','notes'];
    protected $casts = ['start_at'=>'datetime','end_at'=>'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function location(): BelongsTo { return $this->belongsTo(Location::class); }

    public function assets(): BelongsToMany {
        return $this->belongsToMany(Asset::class, 'reservation_assets')->withPivot([])->withTimestamps(false);
    }
    public function materials(): BelongsToMany {
        return $this->belongsToMany(Material::class, 'reservation_materials')
            ->withPivot(['requested_qty','approved_qty'])->withTimestamps(false);
    }
}