<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasAppLayout;
use Filament\Resources\Pages\CreateRecord;

abstract class AppCreateRecord extends CreateRecord
{
    use HasAppLayout;
}
