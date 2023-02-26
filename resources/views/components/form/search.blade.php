
@props([
    'name' => 'search',
    'id' => 'search',
    'placeholder' => 'Search ...'
])

<div>
    <label for="{{ $id }}" class="sr-only">Search</label>
    <div class="relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <!-- Heroicon name: search -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input type="text" value="{{ \Request::get($name) }}" name="{{ $name }}" id="{{ $id }}"
               placeholder="{{ $placeholder }}"
               class="focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md">
    </div>
</div>
