<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TaskModal extends Component
{
    public $showTaskModal;

    public $newTaskName;

    public $newTaskDescription;

    public function __construct($showTaskModal, $newTaskName, $newTaskDescription)
    {
        $this->showTaskModal = $showTaskModal;
        $this->newTaskName = $newTaskName;
        $this->newTaskDescription = $newTaskDescription;
    }

    public function render()
    {
        return view('components.task-modal');
    }
}
