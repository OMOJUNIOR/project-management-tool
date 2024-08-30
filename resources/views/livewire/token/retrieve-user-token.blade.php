<div>
    <!-- Download API Documentation Link -->
    <div
        class="flex flex-col items-center justify-center p-4 space-y-2 rounded-lg sm:flex-row sm:space-y-0 sm:space-x-2">
        <a href="#" wire:click.prevent="downloadPostmanCollection"
            class="flex items-center text-sm font-medium text-blue-500 hover:underline dark:text-blue-300">
            <svg class="w-5 h-5 mr-2 text-blue-500 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6M9 11h6m-6 4h6" />
            </svg>
            {{ __('Download API Postman Collection') }}
        </a>
    </div>
    
    <div
        class="flex flex-col items-center justify-center p-4 space-y-2 rounded-lg sm:flex-row sm:space-y-0 sm:space-x-2">
        <label for="apiToken" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ __('API Key') }}
            <button onclick="copyToClipboard()" class="ml-2 focus:outline-none">
                <svg class="w-5 h-5 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                    </path>
                </svg>
            </button>
        </label>

        <div class="relative w-full sm:w-64">
            <div id="copySuccessMessage"
                class="items-center justify-center hidden mb-4 text-sm text-green-600 dark:text-green-400">
                {{ __('API Token copied to clipboard!') }}
            </div>
            <input id="apiToken" type="password" value="{{ $token }}"
                class="w-full px-4 py-2 pr-16 text-gray-700 bg-gray-200 border-none rounded-lg dark:bg-gray-700 dark:text-gray-300"
                readonly>
            <button onclick="toggleVisibility()"
                class="absolute text-sm text-blue-500 transform -translate-y-1/2 right-2 top-1/2 focus:outline-none">
                {{ __('Show') }}
            </button>
        </div>

        <button wire:click="$set('showModal', true)"
            class="px-4 py-2 font-semibold text-white transition-colors duration-300 bg-purple-600 rounded-lg sm:w-auto hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-900">
            {{ __('Create New Token') }}
        </button>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white dark:bg-gray-800 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New Token') }}
                        </h3>
                        <div class="mt-2">
                            <label for="tokenName"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Token Name') }}</label>
                            <input type="text" id="tokenName" name="tokenName" wire:model="tokenName"
                                class="w-full px-4 py-2 mt-1 text-gray-700 bg-gray-200 border-none rounded-lg dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            @error('tokenName')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="createToken" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Create Token') }}
                        </button>
                        <button wire:click="$set('showModal', false)" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300 sm:mt-0 sm:w-auto sm:text-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function copyToClipboard() {
        var copyText = document.getElementById("apiToken");

        // temporary textarea element to copy the content
        var textArea = document.createElement("textarea");
        textArea.value = copyText.value;
        document.body.appendChild(textArea);

        textArea.select();
        textArea.setSelectionRange(0, 99999);

        try {
            document.execCommand("copy");
            document.body.removeChild(textArea);

            document.getElementById("copySuccessMessage").classList.remove("hidden");
            setTimeout(function() {
                document.getElementById("copySuccessMessage").classList.add("hidden");
            }, 3000);
        } catch (err) {
            console.error("Failed to copy text:", err);
        }
    }

    function toggleVisibility() {
        var tokenField = document.getElementById("apiToken");
        var toggleButton = event.target;

        if (tokenField.type === "password") {
            tokenField.type = "text";
            toggleButton.textContent = "Hide";
        } else {
            tokenField.type = "password";
            toggleButton.textContent = "Show";
        }
    }
</script>
