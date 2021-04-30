<div class="w-full relative overflow-x-auto">
    <table class="table w-full table-auto">
        <thead>
        <tr>
            <th>@sortablelink('character_id', 'Class')</th>
            <th>@sortablelink('name')</th>
            <th>@sortablelink('creator.name', 'Author')</th>
            <th>@sortablelink('patch.launched_at', 'Patch')</th>
            <th>Primary</th>
            <th>Secondary</th>
            <th>@sortablelink('votes_count', 'Salutes')</th>
            <th>@sortablelink('updated_at', 'Last Update')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($loadouts as $loadout)
            <tr onclick="window.open('{{ route('preview.show', $loadout->id) }}','_blank')">
                <td><img src="/assets/img/{{ $loadout->character->image }}-hex.png" alt="{{ $loadout->character->name }} icon"></td>
                <td class="text-xl">{{ Str::limit($loadout->name, 50) }}</td>
                <td class="text-xl">{{ $loadout->creator->name ?? 'Anonymous' }}</td>
                <td class="text-xl">{{ $loadout->patch->patch_num ?? '' }}</td>
                <td class="weapon">
                    @if($loadout->primaryGun)
                        <img src="/assets/{{ $loadout->primaryGun->image }}.svg" alt="{{ $loadout->primaryGun->name }} icon">
                    @endif
                </td>
                <td class="weapon">
                    @if($loadout->secondaryGun)
                        <img src="/assets/{{ $loadout->secondaryGun->image }}.svg" alt="{{ $loadout->secondaryGun->name }} icon">
                    @endif
                </td>
                <td class="text-right">{{ $loadout->votes_count }}</td>
                <td class="text-sm">{{ $loadout->updated_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2 mb-2 text-lg">
    {{ $loadouts->withQueryString()->render() }}
</div>
