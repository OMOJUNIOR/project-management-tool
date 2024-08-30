<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use App\Services\PermissionService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;

    protected $permissionService;

    public function __construct(TaskService $taskService, PermissionService $permissionService)
    {
        $this->taskService = $taskService;
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $tasks = $this->taskService->getAllTasks()
                ->with('project')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return TaskResource::collection($tasks);
        } catch (Exception $e) {
            Log::error('Error fetching tasks: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while fetching tasks.'], 500);
        }
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            $task = $this->taskService->createTask($request->all());

            return new TaskResource($task);
        } catch (Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while creating the task.'], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $validated = $request->merge(['id' => $id])->validate([
            'id' => 'required|integer|exists:tasks,id',
        ]);

        try {

            $task = $this->taskService->getTaskById($validated['id']);

            return new TaskResource($task);
        } catch (Exception $e) {
            Log::error('Error fetching task: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while fetching the task.'], 500);
        }
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $validated = $request->merge(['id' => $id])->validate([
            'id' => 'required|integer',
        ]);

        try {

            $task = $this->taskService->getTaskById($validated['id']);

            if (!$this->permissionService->canManageTask($task)) {
                return response()->json(['error' => 'Unauthorized access to this task.'], 403);
            }

            $updatedTask = $this->taskService->updateTask($task, $request->all());

            return new TaskResource($updatedTask);
        } catch (ModelNotFoundException $e) {
            Log::error('Task not found: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Task not found. Please check the task ID and try again.'], 404);
        } catch (Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while updating the task.'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $validated = $request->merge(['id' => $id])->validate([
            'id' => 'required|integer',
        ]);

        try {
            $task = $this->taskService->getTaskById($validated['id']);

            if (!$this->permissionService->canManageTask($task)) {
                return response()->json(['error' => 'Unauthorized access to this task.'], 403);
            }

            $this->taskService->deleteTask($task);

            return response()->json(['message' => 'Task deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Task not found: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Task not found. Please check the task ID and try again.'], 404);
        } catch (Exception $e) {

            Log::error('Error deleting task: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while deleting the task. Please try again later.'], 500);
        }
    }
}
