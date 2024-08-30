<?php

namespace App\Livewire\Project;

use App\Enums\TaskStatus;
use App\Services\ProjectService;
use App\Services\TaskService;
use App\Services\PermissionService;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectManagement extends Component
{
    use WithPagination;

    public $name;

    public $description;

    public $editingProjectId;

    public $showModal = false;

    public $newTaskName = '';

    public $newTaskDescription = '';

    public $currentProject = null;

    public $search = '';

    public $taskFilter = 'all';

    public $showProjectsList = true;

    public $showTaskModal = false;

    public $showDeleteModal = false;

    public $showDeleteTaskModal = false;

    public $showUnauthorizedModal = false;

    public $taskName;

    public $taskDescription;

    public $showEditTaskModal = false;

    public $editingTaskId = null;

    public $deleteId;

    public $deleteType;

    public $apiToken = 'cclllsoddosoksoksdokcokdsoko';

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'required|min:10',
    ];

    protected $projectService;

    protected $taskService;

    protected $permissionService;

    public function boot(ProjectService $projectService, TaskService $taskService, PermissionService $permissionService)
    {
        $this->projectService = $projectService;
        $this->taskService = $taskService;
        $this->permissionService = $permissionService;
    }

    public function createProject()
    {
        $this->validate();

        if ($this->editingProjectId) {
            $project = $this->projectService->getProjectById($this->editingProjectId);
            $this->projectService->updateProject($project, [
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Project updated successfully.');
        } else {
            $this->projectService->createProject([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Project created successfully.');
        }

        // Reset the form and modal
        $this->reset(['name', 'description', 'editingProjectId', 'showModal']);
        $this->filterProjects();
    }

    public function showProjects()
    {
        $this->resetPage('tasksPage'); // Reset the pagination for tasks
        $this->reset('currentProject');
        $this->showProjectsList = true; // Show the project list again
    }

    public function editProject($projectId)
    {
        $project = $this->projectService->getProjectById($projectId);
        $this->editingProjectId = $projectId;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->showModal = true; // Show the modal for editing
    }

    public function deleteItem()
    {
        if ($this->deleteType == 'project') {
            $project = $this->projectService->getProjectById($this->deleteId);
            $this->projectService->deleteProject($project);
        } elseif ($this->deleteType == 'task') {
            $task = $this->taskService->getTaskById($this->deleteId);
            $this->taskService->deleteTask($task);
        }

        $this->reset(['deleteType', 'deleteId', 'showDeleteModal', 'deleteType']);
        session()->flash('message', ucfirst($this->deleteType) . ' deleted successfully.');
    }

    public function deleteProject()
    {
        $project = $this->projectService->getProjectById($this->projectIdToDelete);
        $this->projectService->deleteProject($project);

        $this->showDeleteModal = false;
        $this->filterProjects();
        session()->flash('message', 'Project deleted successfully.');
    }

    public function confirmDelete($type, $id)
    {
        $this->deleteType = $type;

        if ($this->deleteType == 'project') {
            $project = $this->projectService->getProjectById($id);

            if (!$this->permissionService->canManageProject($project)) {
                $this->showUnauthorizedModal = true;
                return;
            }
        }

        if ($this->deleteType == 'task') {
            $task = $this->taskService->getTaskById($id);

            if (!$this->permissionService->canManageTask($task)) {
                $this->showUnauthorizedModal = true;
                return;
            }
        }

        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'description', 'editingProjectId', 'showModal']);
    }

    public function filterProjects()
    {
        return $this->projectService->getAllProjects()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(5, ['*'], 'projectsPage');
    }

    public function loadProjects()
    {
        if ($this->search && ! empty($this->search)) {
            return $this->projectService->getAllProjects()
                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy('created_at', 'DESC')
                ->paginate(5, ['*'], 'projectsPage');
        } else {
            return $this->projectService->getAllProjects()
                ->orderBy('created_at', 'DESC')
                ->paginate(5, ['*'], 'projectsPage');
        }
    }

    public function addTask()
    {
        $this->validate([
            'newTaskName' => 'required|min:3',
            'newTaskDescription' => 'nullable',
        ]);

        $this->taskService->createTask([
            'project_id' => $this->currentProject->id,
            'name' => $this->newTaskName,
            'description' => $this->newTaskDescription,
            'user_id' => auth()->user()->id,
            'status' => TaskStatus::TODO,
        ]);

        $this->reset(['newTaskName', 'newTaskDescription', 'showTaskModal']);
        $this->showTasks($this->currentProject->id);
    }

    public function editTask($taskId)
    {
        $task = $this->taskService->getTaskById($taskId);

        if (!$this->permissionService->canManageTask($task)) {
            $this->showUnauthorizedModal = true;
            return;
        }

        $this->editingTaskId = $taskId;
        $this->taskName = $task->name;
        $this->taskDescription = $task->description;
        $this->showEditTaskModal = true;
    }

    public function updateTask()
    {
        $this->validate([
            'taskName' => 'required|min:3',
            'taskDescription' => 'nullable',
        ]);

        $task = $this->taskService->getTaskById($this->editingTaskId);
        $this->taskService->updateTask($task, [
            'name' => $this->taskName,
            'description' => $this->taskDescription,
        ]);

        $this->reset(['taskName', 'taskDescription', 'editingTaskId', 'showEditTaskModal']);
        $this->showTasks($this->currentProject->id);
        session()->flash('message', 'Task updated successfully.');
    }

    public function deleteTask($taskId)
    {
        $task = $this->taskService->getTaskById($taskId);
        $this->taskService->deleteTask($task);
        $this->showTasks($this->currentProject->id);
        session()->flash('message', 'Task deleted successfully.');
    }

    public function showTasks($projectId)
    {
        $this->currentProject = $this->projectService->getProjectById($projectId);

        $query = $this->currentProject->tasks();

        if ($this->taskFilter !== 'all') {
            $query->where('status', $this->taskFilter);
        }

        $this->showProjectsList = false;
    }

    public function updateTaskStatus($taskId, TaskStatus $newStatus)
    {
        $task = $this->taskService->getTaskById($taskId);

        if (!$this->permissionService->canManageTask($task)) {
            $this->showUnauthorizedModal = true;
            return;
        }
        
        $this->taskService->updateTask($task, [
            'status' => $newStatus,
        ]);

        $this->showTasks($this->currentProject->id);

        session()->flash('message', 'Task status updated successfully.');
    }

    public function updatedSearch()
    {
        $this->filterProjects();
    }

    public function updatedTaskFilter()
    {
        if ($this->currentProject) {
            $this->showTasks($this->currentProject->id);
        }
    }

    public function render()
    {
        $projects = $this->loadProjects();

        $tasks = null;
        if ($this->currentProject) {
            $tasks = $this->taskService->getTasksByProjectId($this->currentProject->id)
                ->when($this->taskFilter !== 'all', function ($query) {
                    $query->where('status', $this->taskFilter);
                })
                ->orderBy('created_at', 'DESC')
                ->paginate(5, ['*'], 'tasksPage');
        }

        return view('livewire.project.project-management', [
            'projects' => $projects,
            'tasks' => $tasks,
        ]);
    }
}
