<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasAppLayout;
use Filament\Resources\Pages\ManageRecords;

abstract class AppManageRecords extends ManageRecords
{
    use HasAppLayout;
}
