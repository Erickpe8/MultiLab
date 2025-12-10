<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanMaterial extends Model
{
    public $timestamps = false;
    protected $fillable = ['loan_id','material_id','loan_qty','returned_qty'];
}