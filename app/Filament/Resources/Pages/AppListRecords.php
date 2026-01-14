<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasAppLayout;
use Filament\Resources\Pages\ListRecords;

abstract class AppListRecords extends ListRecords
{
    use HasAppLayout;
}
