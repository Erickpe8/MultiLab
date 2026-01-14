<?php

namespace App\Filament\Concerns;

trait HasAppLayout
{

    public function getLayout(): string
    {
        return $this->resolveAppLayout();
    }

    protected function resolveAppLayout(): string
    {
        return 'layouts.app';
    }
}
