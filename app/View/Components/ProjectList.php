<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProjectList extends Component
{
    public $projects;

    public function __construct($projects)
    {
        $this->projects = $projects;
    }

    public function render()
    {
        return view('components.project-list');
    }
}
