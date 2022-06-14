
@props(['link' => '#', 'title', 'active' => false])

<a href="{{ $link }}"
   class="{{ $active ? 'bg-gray-700' : 'hover:bg-gray-700' }} block px-4 py-2 text-sm text-gray-300">
    {{ $title }}
</a>
