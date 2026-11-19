@props(['loadout', 'hasVoted', 'voteCount'])
<div>
    <div class="previewHeaderBackground text-gray-300">
        <div class="flex justify-between items-center">
            @if ($loadout->creator)
                <h2 class="p-4">by <a class="text-karl-orange"
                        href="/profile/{{ $loadout->creator->id }}">{{ $loadout->creator->name }}</a> on
                    {{ \Carbon\Carbon::parse($loadout->updated_at)->format('d/m/Y') }}
                </h2>
            @else
                <h2 class="p-4">by <a class="text-karl-orange" href="/profile/0">Anonymous</a> on
                    {{ \Carbon\Carbon::parse($loadout->updated_at)->format('Y-m-d') }}
                </h2>
            @endif

            <div class="flex">
                @can('update', $loadout)
                <a class="inline-flex items-center text-center px-4 py-2 mr-3 border border-transparent text-sm font-medium rounded-md shadow-xs text-white bg-orange-500 hover:bg-orange-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 w-full md:w-auto"
                    href="/build/{{ $loadout->id }}">
                        EDIT
                </a>
                @endcan

                <!-- Trigger -->
                <a class="p-3 text-karl-orange" href="{{ route('build.show', $loadout->id) }}">Use this build</a>

            </div>
        </div>

        <div class="previewHeaderContainer p-4">

            <div class="previewFooter mt-4">

                @auth
                    <form action="{{ route('loadout.vote', $loadout) }}" method="post">
                        @csrf
                        <button class="rounded-sm bg-gray-900 p-3 font-bold" type="submit" aria-pressed="{{ $hasVoted ? 'true' : 'false' }}">
                            {{ $hasVoted ? 'Remove salute' : 'Salute' }} · {{ $voteCount }}
                        </button>
                    </form>
                @else
                    <p>Salutes · {{ $voteCount }}</p>
                @endauth


            </div>
            <div class="flex">
                <img src="{{ $loadout->character->potrait }}" />
            </div>
        </div>


    </div>
</div>
