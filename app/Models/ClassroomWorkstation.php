<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Computer;

class ClassroomWorkstation extends Model
{
    use HasFactory;

    public const STATUS_LABELS = [
        'disponible' => 'Disponible',
        'mantenimiento' => 'No Disponible',
        'fuera_servicio' => 'No Disponible',
    ];

    protected $fillable = [
        'classroom_code',
        'code',
        'label',
        'status',
        'seat_number',
        'specs',
        'notes',
    ];

    protected $casts = [
        'specs' => 'array',
    ];

    public function loans(): BelongsToMany
    {
        return $this->belongsToMany(ClassroomLoan::class, 'classroom_loan_workstations')
            ->withPivot(['status', 'metrics', 'assigned_user', 'notes'])
            ->withTimestamps();
    }

    public static function syncFromComputers(): void
    {
        $computers = Computer::query()->get();

        if ($computers->isEmpty()) {
            return;
        }

        $syncedCodes = [];

        foreach ($computers as $computer) {
            $code = static::makeComputerCode($computer);
            $label = $computer->name ?? $code;

            $syncedCodes[] = $code;

            static::updateOrCreate(
                ['code' => $code],
                [
                    'classroom_code' => 'B202',
                    'label' => $label,
                    'status' => $computer->status === 'disponible' ? 'disponible' : 'fuera_servicio',
                    'notes' => $computer->notes,
                ]
            );
        }

        static::query()
            ->whereNotIn('code', $syncedCodes)
            ->whereDoesntHave('loans')
            ->delete();
    }

    public static function computerCodes(): array
    {
        return Computer::query()
            ->get()
            ->map(fn(Computer $computer) => static::makeComputerCode($computer))
            ->all();
    }

    public static function scopeFromComputerInventory(Builder $query): Builder
    {
        $codes = static::computerCodes();

        if (empty($codes)) {
            return $query->whereRaw('0 = 1');
        }

        return $query->whereIn('code', $codes);
    }

    public function defaultLoanStatus(): string
    {
        return match ($this->status) {
            'disponible' => 'liberado',
            'fuera_servicio' => 'inactivo',
            default => 'reservado',
        };
    }

    public function availabilityBadgeColor(): string
    {
        return $this->status === 'disponible' ? 'success' : 'danger';
    }

    public function availabilityLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? 'No Disponible';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->availabilityLabel();
    }

    public function getAvailabilityLabelAttribute(): string
    {
        return $this->availabilityLabel();
    }

    protected static function makeComputerCode(Computer $computer): string
    {
        return $computer->serial_number ?: 'computer-' . $computer->id;
    }
}
