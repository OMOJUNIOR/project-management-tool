<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TaskList extends Component
{
    public $tasks;

    public $currentProject;

    public function __construct($tasks, $currentProject)
    {
        $this->tasks = $tasks;
        $this->currentProject = $currentProject;
    }

    public function render()
    {
        return view('components.task-list');
    }
}
