<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteConfirmationModal extends Component
{
    public $showDeleteModal;

    public function __construct($showDeleteModal)
    {
        $this->showDeleteModal = $showDeleteModal;
    }

    public function render()
    {
        return view('components.delete-confirmation-modal');
    }
}
