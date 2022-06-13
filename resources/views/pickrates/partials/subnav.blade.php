<div class="mb-4">
    <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select id="tabs" name="tabs" class="block w-full focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
            <option>By Class</option>

            <option>By Gun</option>

            <option selected>By Mod</option>
        </select>
    </div>
    <div class="hidden sm:block">
        <nav class="flex space-x-4" aria-label="Tabs">
            <a href="{{ route('pickrates.classes') }}"
               class="{{ Route::is('pickrates.classes') ? 'bg-gray-700 text-gray-300' : 'text-gray-200 hover:text-white' }} px-3 py-2 font-medium text-sm rounded-md"
            >
                By Class
            </a>

            <a href="{{ route('pickrates.guns') }}"
               class="{{ Route::is('pickrates.guns') ? 'bg-gray-700 text-gray-300' : 'text-gray-200 hover:text-white' }} px-3 py-2 font-medium text-sm rounded-md"
            >
                By Guns
            </a>

            <a href="{{ route('pickrates.mods') }}"
               class="{{ Route::is('pickrates.mods')  ? 'bg-gray-700 text-gray-300' : 'text-gray-200 hover:text-white' }} px-3 py-2 font-medium text-sm rounded-md"
            >
                By Mod
            </a>
        </nav>
    </div>
</div>
