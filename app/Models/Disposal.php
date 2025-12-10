<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_kind','asset_id','material_id','approved_by','disposed_at','type','reason',
        'document_path','status','qty'
    ];
    protected $casts = ['disposed_at'=>'datetime','qty'=>'integer'];

    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
    public function material(): BelongsTo { return $this->belongsTo(Material::class); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}