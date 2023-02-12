
@php

    $currentPickRate = $calculatePercentage($entity->loadouts_count, $totalLoadouts);
    $previousPickRate = $calculatePercentage($comparison->loadouts_count, $previousTotal);

    $pickRateDifference = $calculateDifference($currentPickRate, $previousPickRate);

@endphp

<div class="flex items-center md:space-x-4">
    <div class="w-1/4">
        {{ $identifier }}
    </div>
    <dl class="mt-5 rounded-lg bg-gray-700 text-gray-200 overflow-hidden shadow divide-y divide-gray-900 w-3/4">
        <a href="{{ $loadoutLink }}">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-base font-normal text-gray-100">Total Loadouts</dt>
                <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                    <div class="flex items-baseline text-2xl font-semibold text-orange-600">
                        {{ $entity->loadouts_count }}
                    </div>
                </dd>
            </div>
        </a>


        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-100">Pick Rate</dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-orange-600">
                    {{ $currentPickRate }}%
                    <span class="ml-2 text-sm font-medium text-gray-400"> from {{ $previousPickRate }}% </span>
                </div>

                <div
                    class="
                        inline-flex
                        items-baseline
                        px-2.5
                        py-0.5
                        rounded-full
                        text-sm
                        font-medium
                        bg-{{ $pickRateDifference >= 0 ? 'green' : 'red'}}-100
                        text-{{ $pickRateDifference >= 0 ? 'green' : 'red'}}-800
                        md:mt-2
                        lg:mt-0"
                >
                    {{ $pickRateDifference }}%
                </div>
            </dd>
        </div>
    </dl>
</div>
