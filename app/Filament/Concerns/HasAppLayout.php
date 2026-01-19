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
        /**
         * Filament Livewire pages expect to extend one of the core panel layouts
         * so that Alpine stores, hooks, and the Livewire bridge get injected.
         * Returning our custom Blade layout here prevented the Livewire forms
         * from mounting, which in turn caused the POST to hit the route directly
         * and throw MethodNotAllowed. We fall back to Filament's panel layout,
         * and will layer any custom UI through hooks/slots instead.
         */
        return 'filament-panels::components.layout.index';
    }
}
