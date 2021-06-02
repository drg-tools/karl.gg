<div class="mb-4">

    <div class="flex flex-wrap">
        <div class="mt-1 relative rounded-md shadow-sm flex flex-col w-full md:w-1/2 mr-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- Heroicon name: solid/mail -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="search" value="{{ \Request::get('search') }}" name="search"
                class="focus:ring-gray-500 focus:border-gray-500 block pl-10 sm:text-sm border-gray-300 text-gray-900"
                placeholder="Search by Loadout Name">
        </div>
        <div class="w-1/2 flex flex-col mt-3 md:w-1/4 md:mt-1">
            <select class="form-control" data-trigger name="characters[]" id="characters"
                placeholder="Select Characters" multiple>
                <option value="">Characters</option>
                <option value="3">Driller</option>
                <option value="1">Engineer</option>
                <option value="4">Gunner</option>
                <option value="2">Scout</option>
            </select>
        </div>
    </div>
    <div class="flex flex-row">
        <div class="w-1/2 md:w-2/6 mr-3 mt-4">
            <select class="form-control" name="primaries[]" id="primaries" placeholder="Select Primary Weapons"
                multiple>
                <option value="">Primaries</option>
                @foreach ($primaries as $key => $weapon)
                    <option value="{{ $key }}">{{ $weapon }}</option>
                @endforeach
            </select>

        </div>
        <div class="w-1/2 md:w-2/6 mr-3 mt-4">
            <select class="form-control" name="secondaries[]" id="secondaries" placeholder="Select Secondary Weapons"
                multiple>
                <option value="">Secondaries</option>
                @foreach ($secondaries as $key => $gun)
                    <option value="{{ $key }}">{{ $gun }}</option>
                @endforeach
            </select>

        </div>
    </div>
    <div class="my-2 flex sm:flex-row flex-wrap">
        <div class="w-1/3 md:w-1/6 mr-3 mt-3">
                <select class=" mt-1 block w-full text-gray-800" name="hasOverclock" id="hasOverclock">
                    <option value="">Overclocks?</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
        </div>
        <div class="w-1/3 md:w-1/6 mr-3 mt-3">
                <select class="form-select mt-1 block w-full text-gray-800" name="hasGuide" id="hasGuide">
                    <option value="">Loadout Guide?</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
        </div>
        <div class="w-1/2 md:w-2/6 mr-3 mt-3">
            <div class="">
                <select class="form-control block" data-trigger name="overclocks[]" id="overclocks"
                    placeholder="Select Overclocks" multiple>
                <option value="">OC Names</option>
                @foreach ($overclocks as $key => $oc)
                    <option value="{{ $key }}">{{ $oc }}</option>
                @endforeach
            </select> 
        </div>
    </div>
        <button class="ml-2 mt-3 px-6 py-1 bg-karl-orange text-gray-800 text-center font-bold sm:rounded max-h-10"
            type="submit">
            Submit
        </button>
    </div>
</div>

@section('scripts')
<script type="text/javascript">
    
    document.addEventListener('DOMContentLoaded', function() {

        
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
        }
        );
        var characterSelectMultiple = new Choices(
            '#characters',
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
