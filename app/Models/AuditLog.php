<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'audit_log';
    protected $fillable = [
        'table_name','row_pk','action','before_json','after_json','user_id','ip','user_agent','created_at'
    ];
    protected $casts = [
        'before_json'=>'array',
        'after_json'=>'array',
        'created_at'=>'datetime',
    ];
}