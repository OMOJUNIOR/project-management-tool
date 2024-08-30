<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProjectModal extends Component
{
    public $showModal;

    public $editingProjectId;

    public $name;

    public $description;

    public function __construct($showModal, $editingProjectId, $name, $description)
    {
        $this->showModal = $showModal;
        $this->editingProjectId = $editingProjectId;
        $this->name = $name;
        $this->description = $description;
    }

    public function render()
    {
        return view('components.project-modal');
    }
}
