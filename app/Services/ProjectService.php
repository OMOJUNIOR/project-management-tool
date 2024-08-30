<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function createProject(array $data)
    {
        return Project::create($data);
    }

    public function updateProject(Project $project, array $data)
    {
        $project->update($data);

        return $project;
    }

    public function deleteProject(Project $project)
    {
        $project->tasks()->delete();

        return $project->delete();
    }

    public function getAllProjects()
    {
        return Project::query();
    }

    public function getProjectById($id)
    {
        return Project::findOrFail($id);
    }
}
