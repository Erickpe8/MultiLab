<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasAppLayout;
use Filament\Resources\Pages\ViewRecord;

abstract class AppViewRecord extends ViewRecord
{
    use HasAppLayout;
}
