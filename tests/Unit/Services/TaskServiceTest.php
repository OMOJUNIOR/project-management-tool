<?php

namespace Tests\Unit\Services;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $taskService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = app(TaskService::class);
    }

    public function test_create_task()
    {
        $project = Project::factory()->create();

        $taskData = Task::factory()->make(['project_id' => $project->id])->toArray();

        $task = $this->taskService->createTask($taskData);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($taskData['name'], $task->name);
        $this->assertEquals($project->id, $task->project_id);
        $this->assertEquals($taskData['status'], $task->status->value);
    }

    public function test_update_task()
    {
        $task = Task::factory()->create();

        $updatedData = [
            'name' => 'Updated Task Name',
            'status' => TaskStatus::IN_PROGRESS->value,
        ];

        $updatedTask = $this->taskService->updateTask($task, $updatedData);

        $this->assertEquals('Updated Task Name', $updatedTask->name);
        $this->assertEquals(TaskStatus::IN_PROGRESS->value, $updatedTask->status->value);
    }

    public function test_delete_task()
    {
        $task = Task::factory()->create();

        $this->taskService->deleteTask($task);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_get_tasks_by_project_id()
    {
        $project = Project::factory()->create();
        Task::factory()->count(3)->create(['project_id' => $project->id]);

        $tasks = $this->taskService->getTasksByProjectId($project->id)->get();

        $this->assertCount(3, $tasks);
    }

    public function test_get_task_by_id()
    {
        $task = Task::factory()->create();

        $foundTask = $this->taskService->getTaskById($task->id);

        $this->assertEquals($task->id, $foundTask->id);
    }
}
