<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

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

    public function loans(): BelongsToMany
    {
        return $this->belongsToMany(Loan::class, 'loan_materials')
            ->withPivot(['loan_qty', 'returned_qty']);
    }

    public function getQuantityOnLoanAttribute(): int
    {
        return Loan::whereHas('materials', function (Builder $query) {
            $query->where('materials.id', $this->id);
        })
        ->whereNotIn('status', ['devuelto', 'devuelto_con_multa', 'cancelado', 'rechazado'])
        ->get()
        ->sum(function ($loan) {
            $pivot = $loan->materials->firstWhere('id', $this->id)->pivot;
            return $pivot->loan_qty - $pivot->returned_qty;
        });
    }
}