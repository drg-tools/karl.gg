<div class="mb-4">
    <div class="flex flex-row">
        <div class="mt-1 relative rounded-md shadow-sm flex flex-col w-1/2 mr-4">
            <label for="choices-multiple-characters">Loadout Name</label>
            <div class="absolute inset-y-12 left-0 pl-3 flex items-center pointer-events-none">
                <!-- Heroicon name: solid/mail -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="search" value="{{ \Request::get('search') }}" name="search" class="focus:ring-gray-500 focus:border-gray-500 block pl-10 sm:text-sm border-gray-300 text-gray-900" placeholder="Search by Loadout Name">
        </div>
        <div class="w-1/4 flex flex-col">
            <label for="characters" class="mb-2.5">Characters</label>
            <select class="form-control" data-trigger name="characters[]" id="characters" placeholder="Select Characters" multiple>
                <option value="3">Driller</option>
                <option value="1">Engineer</option>
                <option value="4">Gunner</option>
                <option value="2">Scout</option>
            </select>
        </div>
    </div>

    <div class="my-2 flex sm:flex-row flex-wrap">
        <div class="w-1/6 mr-3">
            <label class="block">
                <span class="text-gray-300">Overclocks?</span>
                <select class="form-select mt-1 block w-full text-gray-800" name="overclocks">
                    <option>--</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </label>
        </div>
        <div class="w-1/6 mr-3">
            <label class="block">
                <span class="text-gray-300" name="guide">Loadout Guide?</span>
                <select class="form-select mt-1 block w-full text-gray-800">
                    <option>--</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </label>
        </div>
        <div class="w-1/6 mr-3">
            <!-- For defining autocomplete -->
            <label for="creator" class="block mb-1.5">Creator Name</label>
            <select class="form-control text-gray-800" name="creator[]" id="creator" multiple></select>
        </div>
    </div>
    <div class="flex flex-row">
        <div class="w-2/6 mr-3">
            <label for="primaries">Primaries</label>
            <select class="form-control" name="primaries[]" id="primaries" placeholder="Select Primary Weapons" multiple>
                @foreach ($primaries as $weapon)
                    <option value="{{$weapon->id}}">{{$weapon->name}}</option>
                @endforeach
            </select>
            

        </div>
        <div class="w-2/6 mr-3">
            <label for="secondaries">Secondaries</label>
            <select class="form-control" name="secondaries[]" id="secondaries" placeholder="Select Secondary Weapons" multiple>
                @foreach ($secondaries as $gun)
                    <option value="{{$gun->id}}">{{$gun->name}}</option>
                @endforeach
            </select>
           
        </div>
        <button class="ml-2 mt-7 px-6 py-1 bg-karl-orange text-gray-800 text-center font-bold sm:rounded max-h-10"
            type="submit">
            Submit
        </button>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
    
        document.addEventListener('DOMContentLoaded', function() {
        
        var multipleFetch = new Choices('#creator', {
          placeholder: true,
          placeholderValue: 'Search Creators',
          maxItemCount: 5,
          @if( request()->get('creator') )  
          items: {!! json_encode(request()->get('creator'))!!},
          @endif
        }).setChoices(function() {
          return fetch(
            '/user/getUsers'
          )
            .then(function(response) {
              return response.json();
            })
            .then(function(data) {
              return data.map(function(user, index) {
                return { value: user.id, label: user.text};
              });
            });
        });
       
        
        // let boolSelected = false;
        //         @if( request()->get('creator') )  
        //         let creatorArray = {!! json_encode(request()->get('creator'))!!};
        //         if(creatorArray.includes(user.id)) {
        //             boolSelected = true;
        //         }
        //         @endif
        
        var multipleCancelButton = new Choices(
          '#primaries',
          {
            removeItemButton: true,
          }
        );
        var multipleCancelButton = new Choices(
          '#secondaries',
          {
            removeItemButton: true,
          }
        );
        var multipleDefault = new Choices(
          document.getElementById('characters')
        );

    });
    </script>

@endsection
