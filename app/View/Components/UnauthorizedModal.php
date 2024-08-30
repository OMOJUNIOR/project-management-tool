<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UnauthorizedModal extends Component
{
    public $showUnauthorizedModal;

    public function __construct($showUnauthorizedModal)
    {
        $this->showUnauthorizedModal = $showUnauthorizedModal;
    }

    public function render()
    {
        return view('components.unauthorized-modal');
    }
}
