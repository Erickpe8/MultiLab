<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','issued_by','loan_code','loan_at','due_at','return_at','status','notes'];
    protected $casts = ['loan_at'=>'datetime','due_at'=>'datetime','return_at'=>'datetime'];

    public function borrower(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function issuer(): BelongsTo { return $this->belongsTo(User::class, 'issued_by'); }

    public function assets(): BelongsToMany {
        return $this->belongsToMany(Asset::class, 'loan_assets')
            ->withPivot(['condition_out','condition_in','evidence_in_path']);
    }
    public function materials(): BelongsToMany {
        return $this->belongsToMany(Material::class, 'loan_materials')
            ->withPivot(['loan_qty','returned_qty']);
    }
    public function fines(): HasMany { return $this->hasMany(LoanFine::class); }
}