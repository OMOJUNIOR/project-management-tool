<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use App\Services\PermissionService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    protected $projectService;

    protected $permissionService;

    public function __construct(ProjectService $projectService,PermissionService $permissionService)
    {
        $this->projectService = $projectService;
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            $projects = $this->projectService->getAllProjects()
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return ProjectResource::collection($projects);
        } catch (Exception $e) {
            Log::error('Error fetching projects: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while fetching projects.'], 500);
        }
    }

    public function store(CreateProjectRequest $request)
    {
        try {

            $project = $this->projectService->createProject($request->all());

            return new ProjectResource($project);
        } catch (Exception $e) {
            Log::error('Error creating project: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while creating the project.'], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $validated = $request->merge(['id' => $id])->validate([
            'id' => 'required|integer|exists:projects,id',
        ]);

        try {
            $project = $this->projectService->getProjectById($validated['id']);

            return new ProjectResource($project);
        } catch (Exception $e) {
            Log::error('Error fetching project: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while fetching the project.'], 500);
        }
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        try {

            $project = $this->projectService->getProjectById($id);

            if (! $project) {
                return response()->json(['error' => 'Project not found.'], 404);
            }

            $updatedProject = $this->projectService->updateProject($project, $request->all());

            return new ProjectResource($updatedProject);
        } catch (Exception $e) {
            Log::error('Error updating project: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while updating the project.'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $validated = $request->merge(['id' => $id])->validate([
            'id' => 'required|integer',
        ]);

        try {
            $project = $this->projectService->getProjectById($validated['id']);

            if (!$this->permissionService->canManageProject($project)) {
                return response()->json(['error' => 'Unauthorized access to this task.'], 403);
            }

            $this->projectService->deleteProject($project);

            return response()->json(['message' => 'Project and related tasks deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Project not found: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Project not found. Please check the project ID and try again.'], 404);
        } catch (Exception $e) {
            Log::error('Error deleting project: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred while deleting the project.'], 500);
        }
    }
}
