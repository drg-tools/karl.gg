<div class="mb-4">
    <div>
        <label for="search" class="sr-only">Search</label>
        <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- Heroicon name: search -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" value="{{ \Request::get('search') }}" name="search" id="search"
                   placeholder="Search by Loadout Name"
                   class="focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md">
        </div>
    </div>

    @php
        $showFilters = request()->hasAny(['primaries', 'secondaries', 'overclocks', 'characters']) ||
            request()->filled('hasGuide') || request()->filled('hasOverclock')
                ? 'true' : 'false';
    @endphp
    <div x-data="{ show: {{ $showFilters }} }">
        <div x-show="show" style="display:none;" class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-4">

            <div>
                <select
                    class="focus:ring-orange-500 focus:border-orange-500 relative block w-full rounded-none rounded-t-md focus:z-10 sm:text-sm border-gray-300"
                    data-trigger name="characters[]" id="characters" multiple>
                    <option value="">Characters</option>
                    @foreach($characters as $id => $character)
                        <option value="{{ $id }}">{{ $character }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select class="form-control" name="primaries[]" id="primaries"
                        multiple>
                    <option value="">Primaries</option>
                    @foreach ($primaries as $key => $weapon)
                        <option value="{{ $key }}">{{ $weapon }}</option>
                    @endforeach
                </select>

            </div>
            <div>
                <select class="form-control" name="secondaries[]" id="secondaries"
                        multiple>
                    <option value="">Secondaries</option>
                    @foreach ($secondaries as $key => $gun)
                        <option value="{{ $key }}">{{ $gun }}</option>
                    @endforeach
                </select>

            </div>
            <div>
                <select class=" mt-1 block w-full text-gray-800" name="hasOverclock" id="hasOverclock">
                    <option value="">Has Overclock</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div>
                <select class="form-select mt-1 block w-full text-gray-800" name="hasGuide" id="hasGuide">
                    <option value="">Has Guide</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div>
                <select class="form-control block" data-trigger name="overclocks[]" id="overclocks" multiple>
                    <option value="">Overclocks</option>
                    @foreach ($overclocks as $oc)
                        <option value="{{ $oc->id }}" data-custom-properties="{{ $oc->character->name }}">{{ $oc->overclock_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-center gap-2 mt-2">
            <button type="button" x-on:click.prevent="show=!show"
                    class="inline-flex items-center text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-500 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 w-full md:w-auto">
                Show/Hide Filters
            </button>
            <button
                class="inline-flex items-center text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-500 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 w-full md:w-auto"
                type="submit">
                Search
            </button>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">

        document.addEventListener('DOMContentLoaded', function () {


            var primarySelectMultiple = new Choices(
                '#primaries',
                {
                    removeItemButton: true,
                }
            );
            var secondarySelectMultiple = new Choices(
                '#secondaries',
                {
                    removeItemButton: true,
                }
            );
            var overclocksSelectMultiple = new Choices(
                '#overclocks',
                {
                    removeItemButton: true,
                    searchFields: ['label', 'value', 'customProperties'],
                }
            );
            var characterSelectMultiple = new Choices(
                '#characters',
                {
                    removeItemButton: true,
                }
            );
            var hasOverclockSelectSingle = new Choices(
                '#hasOverclock',
                {
                    searchEnabled: false,
                    removeItemButton: true,
                }
            );
            var hasGuideSelectSingle = new Choices(
                '#hasGuide',
                {
                    searchEnabled: false,
                    removeItemButton: true,
                }
            );
            // Pre-fill selected values conditionally
            @if( request()->get('characters') )
            characterSelectMultiple.setChoiceByValue({!! json_encode(request()->get('characters'))!!});
            @endif
            @if( request()->get('overclocks') )
            overclocksSelectMultiple.setChoiceByValue({!! json_encode(request()->get('overclocks'))!!});
            @endif
            @if( request()->get('hasOverclock') )
            hasOverclockSelectSingle.setChoiceByValue({!! json_encode(request()->get('hasOverclock'))!!});
            @endif
            @if( request()->get('hasGuide') )
            hasGuideSelectSingle.setChoiceByValue({!! json_encode(request()->get('hasGuide'))!!});
            @endif
            @if( request()->get('primaries') )
            primarySelectMultiple.setChoiceByValue({!! json_encode(request()->get('primaries'))!!});
            @endif
            @if( request()->get('secondaries') )
            secondarySelectMultiple.setChoiceByValue({!! json_encode(request()->get('secondaries'))!!});
            @endif
        });
    </script>

@endsection
