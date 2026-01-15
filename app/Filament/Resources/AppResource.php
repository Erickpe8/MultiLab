<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;

abstract class AppResource extends Resource
{
    protected static string $appLayout = 'layouts.app';

    public static function getAppLayout(): string
    {
        return static::$appLayout;
    }
}
