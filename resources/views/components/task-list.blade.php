<div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 sm:p-6">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 dark:bg-green-800 dark:text-green-100 dark:border-green-600"
            role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif
    <div class="flex flex-col justify-between mb-4 space-y-4 sm:flex-row sm:space-y-0">
        <button wire:click="showProjects"
            class="px-4 py-2 text-white transition-colors duration-300 bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            {{ __('Back to Projects') }}
        </button>
        <button wire:click="$set('showTaskModal', true)"
            class="w-full px-4 py-2 font-semibold text-white transition-colors duration-300 bg-purple-600 rounded-lg sm:w-auto hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-900">
            {{ __('Create Task') }}
        </button>
    </div>

    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 sm:text-2xl">
        {{ __('Tasks for') }} {{ $currentProject ? $currentProject->name : '' }}
    </h3>

    @if ($currentProject && $currentProject->description)
        <span class="mt-2 text-sm text-gray-600 dark:text-gray-400 sm:text-base">
            {{ $currentProject->description }}
        </span>
    @endif

    <!-- Task Filter -->
    <div class="flex justify-end mb-4">
        <div class="w-full sm:w-1/3 md:w-1/4">
            <label for="taskFilter" class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                {{ __('Filter Tasks') }}:
            </label>
            <select id="taskFilter" wire:model.live="taskFilter"
                class="w-full px-3 py-2 text-gray-700 bg-white border rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                <option value="all">{{ __('All') }}</option>
                <option value="todo">{{ __('To Do') }}</option>
                <option value="in-progress">{{ __('In Progress') }}</option>
                <option value="done">{{ __('Done') }}</option>
            </select>
        </div>
    </div>

    @if ($tasks)
        <div class="mb-4 space-y-4 overflow-y-auto h-96">
            @forelse ($tasks as $task)
                <div
                    class="p-4 rounded-lg 
                            {{ auth()->user()->id === $task->user_id || auth()->user()->isAdmin() ? 'bg-green-100 dark:bg-green-800 dark:text-gray-100' : 'bg-gray-100 dark:bg-gray-800 dark:text-gray-300' }}">
                    <div wire:poll.visible class="flex flex-col justify-between space-y-2 sm:flex-row sm:space-y-0">
                        <div class="flex flex-col">
                            <span class="text-gray-800 dark:text-gray-200">{{ $task->name }}</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Created by: ') }}
                                <span
                                    class="font-semibold text-blue-600 dark:text-blue-400">{{ $task->user->name }}</span>
                            </span>
                        </div>

                        <div class="flex space-x-2">
                            <div x-data="{ isDisabled: {{ auth()->user()->id !== $task->user_id && !auth()->user()->isAdmin() ? 'true' : 'false' }} }">
                                <div x-data="{ status: '{{ $task->status->value }}' }">
                                    <select x-model="status"
                                        x-bind:class="{
                                            'bg-yellow-500': status === 'todo',
                                            'bg-blue-500': status === 'in-progress',
                                            'bg-green-500': status === 'done'
                                        }"
                                        x-bind:disabled="isDisabled"
                                        x-bind:title="isDisabled ? '{{ __('You cannot change the status of this task.') }}' : ''"
                                        wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                        class="px-2 py-1 text-xs text-white rounded">
                                        @foreach (App\Enums\TaskStatus::cases() as $status)
                                            <option value="{{ $status->value }}"
                                                {{ $task->status === $status ? 'selected' : '' }}>
                                                {{ __($status->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <button wire:click="editTask({{ $task->id }})" wire:click.stop
                                class="flex-1 px-3 py-1 text-sm text-white transition-colors duration-300 bg-yellow-500 rounded-md sm:flex-none hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-offset-gray-800">
                                {{ __('Edit') }}
                            </button>
                            <button wire:click="confirmDelete('task', {{ $task->id }})" wire:click.stop
                                class="flex-1 px-3 py-1 text-sm text-white transition-colors duration-300 bg-red-500 rounded-md sm:flex-none hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-offset-gray-800">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                    @if ($task->description)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $task->description }}</p>
                    @endif

                    <div
                        class="flex flex-wrap items-center mt-3 space-x-4 text-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-1 text-blue-500 dark:text-blue-400 sm:w-4 sm:h-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z">
                                </path>
                            </svg>
                            <span class="text-sm">{{ __('Created') }}:
                                {{ $task->created_at->format('M d, Y') }}</span>
                        </span>
                        <span class="flex items-center mt-2 sm:mt-0">
                            <svg class="w-5 h-5 mr-1 text-green-500 dark:text-green-400 sm:w-4 sm:h-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">{{ __('Updated') }}:
                                {{ $task->updated_at->format('M d, Y') }}</span>
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-600 dark:text-gray-400">{{ __('No tasks yet.') }}</p>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
