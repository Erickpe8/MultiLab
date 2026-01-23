<?php

namespace App\Http\Responses;

use App\Filament\Pages\MainDashboard;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        return redirect()->to(Filament::getUrl(panel: Filament::getCurrentPanel()->getId(), page: MainDashboard::class));
    }
}
