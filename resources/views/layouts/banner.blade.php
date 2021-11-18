@if(config('app.banner.enabled'))
<div class="relative bg-gray-500 mt-2">
    <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
      <div class="pr-16 sm:text-center sm:px-16">
        <p class="font-medium text-white">
          <span class="md:hidden">
            {{ config('app.banner.message') }}
          </span>
          <span class="hidden md:inline">
            {{ config('app.banner.message') }}
          </span>
          <span class="block sm:ml-2 sm:inline-block">
            <a href="{{ config('app.banner.link') }}" class="text-white font-bold underline"> Learn more <span aria-hidden="true">&rarr;</span></a>
          </span>
        </p>
      </div>
      {{-- <div class="absolute inset-y-0 right-0 pt-1 pr-1 flex items-start sm:pt-1 sm:pr-2 sm:items-start">
        <button x-on:click="bannerClosed = true" type="button" class="flex p-2 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-white">
          <span class="sr-only">Dismiss</span>
          <!-- Heroicon name: outline/x -->
          <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div> --}}
    </div>
  </div>

@endif
