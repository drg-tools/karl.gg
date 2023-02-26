
@props([
    'entities'
])

<div class="my-4 py-4 sm:border-t-2 sm:border-gray-300">
    {{ $entities->withQueryString()->render() }}
</div>
