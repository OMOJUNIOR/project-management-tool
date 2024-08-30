<div class="flex flex-col items-center justify-between mb-8 space-y-4 sm:flex-row sm:space-y-0">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 sm:text-2xl">
        Your Projects
    </h2>
    <div class="flex flex-col w-full space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2 sm:w-auto">
        <input type="text" wire:model.live="search" placeholder="Search projects..."
            class="w-full px-4 py-2 text-gray-800 placeholder-gray-500 bg-white border border-gray-300 rounded-lg dark:text-gray-300 dark:placeholder-gray-400 dark:bg-gray-700 dark:border-gray-600 sm:w-64 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">

        <button
            class="w-full px-4 py-2 font-semibold text-white transition-colors duration-300 bg-purple-600 rounded-lg sm:w-auto hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-900"
            wire:click="$set('showModal', true)">
            Create Project
        </button>
    </div>
</div>

@if (session()->has('message'))
    <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 dark:bg-green-800 dark:text-green-100 dark:border-green-600"
        role="alert">
        <p>{{ session('message') }}</p>
    </div>
@endif

<div wire:ignore.self class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 sm:p-6">
    @if ($projects->isEmpty())
        <p class="text-base text-center text-gray-600 dark:text-gray-400 sm:text-lg">
            {{ __("You don't have any projects yet. Click 'Create Project' to get started!") }}
        </p>
    @else
        <div class="space-y-4 overflow-y-auto h-96 sm:space-y-6">
            @foreach ($projects as $project)
                <div class="p-4 transition-all duration-300 bg-white border border-gray-200 rounded-lg cursor-pointer dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-xl dark:hover:shadow-gray-700/50"
                    wire:poll.visible wire:click="showTasks({{ $project->id }})">
                    <div class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:space-y-0">
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 sm:text-xl">
                                {{ $project->name }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 sm:text-base">
                                {{ $project->description }}
                            </p>
                            <div
                                class="flex flex-wrap items-center mt-3 space-x-4 text-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-1 text-blue-500 dark:text-blue-400 sm:w-4 sm:h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm">{{ __('Created') }}:
                                        {{ $project->created_at->format('M d, Y') }}</span>
                                </span>
                                <span class="flex items-center mt-2 sm:mt-0">
                                    <svg class="w-5 h-5 mr-1 text-green-500 dark:text-green-400 sm:w-4 sm:h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm">{{ __('Updated') }}:
                                        {{ $project->updated_at->format('M d, Y') }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="flex w-full space-x-2 sm:w-auto">
                            <button wire:click="editProject({{ $project->id }})" wire:click.stop
                                class="flex-1 px-3 py-1 text-sm text-white transition-colors duration-300 bg-yellow-500 rounded-md sm:flex-none hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-offset-gray-800">
                                {{ __('Edit') }}
                            </button>
                            <button wire:click="confirmDelete('project', {{ $project->id }})" wire:click.stop
                                class="flex-1 px-3 py-1 text-sm text-white transition-colors duration-300 bg-red-500 rounded-md sm:flex-none hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-offset-gray-800">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    @endif
</div>
