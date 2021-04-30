<!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->

@props(['link' => '#', 'title', 'active' => false])

<a href="{{ $link }}"
   class="{{ $active ? 'bg-gray-100' : 'hover:bg-gray-300' }} block px-4 py-2 text-sm text-gray-700">
    {{ $title }}
</a>
