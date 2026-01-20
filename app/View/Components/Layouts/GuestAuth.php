<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;

class GuestAuth extends Component
{
    public ?string $title;
    public ?string $subtitle;

    public function __construct(?string $title = null, ?string $subtitle = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
    }

    public function render()
    {
        return view('components.layouts.guest-auth');
    }
}
