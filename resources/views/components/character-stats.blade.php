@props([
    'character',
    'comparison',
    'patch',
    'totalLoadouts',
    'previousTotal'
])

<div class="">
    <div class="flex justify-around items-center">
        <h2 class="text-xl leading-6 font-medium text-gray-300">{{ $character->name }}</h2>
        <img src="{{ $character->potrait }}"/>
    </div>
    <dl class="mt-5 grid grid-cols-1 rounded-lg bg-gray-700 text-gray-200 overflow-hidden shadow divide-y divide-gray-900 md:grid-cols-2 md:divide-y-0 md:divide-x">
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-100">Total Loadouts</dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-orange-600">
                    {{ $character->loadouts_count }}
                    <span class="ml-2 text-sm font-medium text-gray-400"> from {{ $comparison->loadouts_count }} </span>
                </div>

                <div
                    class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                    {{ $calculateDifference($character->loadouts_count, $comparison->loadouts_count) }}%
                </div>
            </dd>
        </div>

        @php

            $currentPickRate = $calculatePercentage($character->loadouts_count, $totalLoadouts);
            $previousPickRate = $calculatePercentage($comparison->loadouts_count, $previousTotal);

            $difference = $calculateDifference($currentPickRate, $previousPickRate);

        @endphp

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
                        bg-{{ $difference >= 0 ? 'green' : 'red'}}-100
                        text-{{ $difference >= 0 ? 'green' : 'red'}}-800
                        md:mt-2
                        lg:mt-0"
                >
                    {{ $difference }}%
                </div>
            </dd>
        </div>
    </dl>
</div>
