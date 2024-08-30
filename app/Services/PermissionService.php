<?php
namespace App\Services;

use App\Models\Task;
use App\Models\Project;

class PermissionService
{
    /**
     * Check if the user is authorized to manage a task.
     *
     * @param Task $task
     * @return bool
     */
    public function canManageTask(Task $task): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->id === $task->user_id;
    }

    /**
     * Check if the user is authorized to manage a project.
     *
     * @param Project $project
     * @return bool
     */
    public function canManageProject(Project $project): bool
    {
        return auth()->user()->isAdmin();
    }
}
