<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasAppLayout;
use Filament\Resources\Pages\EditRecord;

abstract class AppEditRecord extends EditRecord
{
    use HasAppLayout;
}
