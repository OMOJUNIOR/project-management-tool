<?php

namespace Tests\Unit\Services;

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $projectService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectService = app(ProjectService::class);
    }

    public function test_create_project()
    {
        $projectData = Project::factory()->make()->toArray();

        $project = $this->projectService->createProject($projectData);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals($projectData['name'], $project->name);
    }

    public function test_update_project()
    {
        $project = Project::factory()->create();

        $updatedData = [
            'name' => 'Updated Project Name',
        ];

        $updatedProject = $this->projectService->updateProject($project, $updatedData);

        $this->assertEquals('Updated Project Name', $updatedProject->name);
    }

    public function test_delete_project()
    {
        $project = Project::factory()->create();

        $this->projectService->deleteProject($project);

        $deletedProject = Project::withTrashed()->find($project->id);

        $this->assertNotNull($deletedProject->deleted_at, 'Project was not soft-deleted.');

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_get_all_projects()
    {
        Project::factory()->count(3)->create();

        $projects = $this->projectService->getAllProjects()->get();

        $this->assertCount(3, $projects);
    }

    public function test_get_project_by_id()
    {
        $project = Project::factory()->create();

        $foundProject = $this->projectService->getProjectById($project->id);

        $this->assertEquals($project->id, $foundProject->id);
    }
}
