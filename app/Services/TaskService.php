<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;

class TaskService
{
    public function createTask(array $data)
    {
        $project = Project::find($data['project_id']);

        if (! $project) {
            throw new \Exception('Project does not exist.');
        }

        if (! isset($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        return $project->tasks()->create($data);
    }

    public function updateTask(Task $task, array $data)
    {
        $task->update($data);

        return $task;
    }

    public function deleteTask(Task $task)
    {
        return $task->delete();
    }

    public function getTasksByProjectId($projectId)
    {
        return Task::where('project_id', $projectId);
    }

    public function getTaskById($id)
    {
        return Task::findOrFail($id);
    }

    public function getAllTasks()
    {
        return Task::with('project')->orderBy('created_at', 'desc');
    }
}
