<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serial_number',
        'status',
        'notes',
        'marca',
        'main_card',
        'processor',
        'ram',
        'hard_drive',
        'network_card',
        'graphics_card',
    ];
}
