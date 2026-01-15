<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasAppLayout;
use Filament\Pages\Page;

abstract class AppPage extends Page
{
    use HasAppLayout;
}
