<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['ref_type','ref_id','folio','title','pdf_path','created_by','created_at'];
    protected $casts = ['created_at'=>'datetime'];

    public function author(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}