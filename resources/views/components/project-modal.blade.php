@if ($showModal)
    <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-opacity-50" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white dark:bg-gray-800 sm:p-6 sm:pb-4">
                    <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-gray-100 sm:text-2xl">
                        {{ $editingProjectId ? __('Update Project') : __('Create New Project') }}
                    </h3>
                    <form wire:submit.prevent="createProject">
                        <div class="mb-4">
                            <label for="projectName" class="block mb-2 font-bold text-gray-700 dark:text-gray-300">{{ __('Project Name') }}:</label>
                            <input type="text" id="projectName" wire:model="name" class="w-full px-3 py-2 text-gray-700 bg-white border rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500" placeholder="{{ __('Enter project name') }}">
                            @error('name')
                                <span class="text-sm text-red-500 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="projectDescription" class="block mb-2 font-bold text-gray-700 dark:text-gray-300">{{ __('Project Description') }}:</label>
                            <textarea id="projectDescription" wire:model="description" class="w-full px-3 py-2 text-gray-700 bg-white border rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500" rows="3" placeholder="{{ __('Enter project description') }}"></textarea>
                            @error('description')
                                <span class="text-sm text-red-500 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col justify-end mt-4 space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                            <button type="button" wire:click="cancelEdit" class="w-full px-4 py-2 text-gray-700 transition-colors duration-300 bg-gray-300 rounded-lg sm:w-auto dark:bg-gray-600 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="w-full px-4 py-2 text-white transition-colors duration-300 bg-blue-500 rounded-lg sm:w-auto dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600">
                                {{ $editingProjectId ? __('Update Project') : __('Create Project') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
