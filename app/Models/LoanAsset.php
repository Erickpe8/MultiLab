<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanAsset extends Model
{
    public $timestamps = false;
    protected $fillable = ['loan_id','asset_id','condition_out','condition_in','evidence_in_path'];
}