<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-900 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-900">
                    <thead class="bg-gray-600">
                    <tr>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"
                        >
                            @sortablelink('character_id', 'Class')</th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"
                        >
                            @sortablelink('name')
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"
                        >
                            @sortablelink('patch.launched_at', 'Patch')
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"
                        >
                            Primary
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"
                        >
                            Secondary
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"
                        >
                            @sortablelink('votes_count', 'Salutes')
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"
                        >
                            @sortablelink('updated_at', 'Last Update')
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-gray-700 divide-y divide-gray-900">
                        @foreach($loadouts as $loadout)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img src="/assets/img/{{ $loadout->character->image }}-hex.png" alt="{{ $loadout->character->name }} icon">
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-md text-white">
                                <a href="{{ route('preview.show', $loadout->id) }}" class="cursor-pointer">
                                    <div class="text-sm text-white hover:underline ">{{ Str::limit($loadout->name, 50) }}</div>
                                    <div class="text-sm text-gray-300">{{ $loadout->creator->name ?? 'Anonymous' }}</div>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $loadout->patch->patch_num ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap w-6 filter invert">
                                @if($loadout->primaryGun)
                                    <img src="/assets/{{ $loadout->primaryGun->image }}.svg" alt="{{ $loadout->primaryGun->name }} icon">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap w-6 filter invert">
                                @if($loadout->secondaryGun)
                                    <img src="/assets/{{ $loadout->secondaryGun->image }}.svg" alt="{{ $loadout->secondaryGun->name }} icon">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-right">
                                {{ $loadout->votes_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $loadout->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
