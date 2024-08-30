<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="container px-4 py-8 mx-auto">
        <div class="mb-8 text-center">
            <h1 class="mb-2 text-2xl font-bold text-gray-800 dark:text-gray-100 sm:text-3xl md:text-4xl">
                {{ __('Welcome to Project Management Tool') }}
            </h1>
        </div>

        @livewire('token.retrieve-user-token')

        @if ($showProjectsList)
            <!-- Search and Create Project -->
            <x-project-list :projects="$projects" />

            <!-- Create or Update Modal -->
            <x-project-modal :showModal="$showModal" :editingProjectId="$editingProjectId" :name="$name" :description="$description" />
        @else
            <!-- Task List -->
            <x-task-list :tasks="$tasks" :currentProject="$currentProject" />

            <!-- Create Task Modal -->
            <x-task-modal :showTaskModal="$showTaskModal" :newTaskName="$newTaskName" :newTaskDescription="$newTaskDescription" />

            <!-- Edit Task Modal -->
            <x-edit-task-modal :showEditTaskModal="$showEditTaskModal" :taskName="$taskName" :taskDescription="$taskDescription" />
        @endif

        <!--  Delete Modal -->
        <x-delete-confirmation-modal :showDeleteModal="$showDeleteModal" />

        <!-- Unauthorized Access Modal -->
        <x-unauthorized-modal :showUnauthorizedModal="$showUnauthorizedModal" />

    </div>
</div>
