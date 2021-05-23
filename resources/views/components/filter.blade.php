<div>
    <div class="flex-row">
        <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- Heroicon name: solid/mail -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="search" value="{{ \Request::get('search') }}" name="search" class="focus:ring-gray-500 focus:border-gray-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Search by Loadout Name">
        </div>
    </div>

    <div class="my-2 flex sm:flex-row flex-wrap">
        <div class="w-1/6 mr-3">
            <label class="block">
                <span class="text-gray-300">Overclocks?</span>
                <select class="form-select mt-1 block w-full text-gray-800">
                    <option>--</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </label>
        </div>
        <div class="w-1/6 mr-3">
            <label class="block">
                <span class="text-gray-300">Loadout Guide?</span>
                <select class="form-select mt-1 block w-full text-gray-800">
                    <option>--</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </label>
        </div>
        <div class="w-1/6 mr-3">
            <!-- For defining autocomplete -->
            <label for="choices-multiple-remote-fetch" class="block mb-1.5">Creator Name</label>
            <select class="form-control text-gray-800" name="choices-multiple-remote-fetch" id="choices-multiple-remote-fetch" multiple></select>
                


        </div>
    </div>
    <div class="flex flex-row">
        <div class="w-2/6 mr-3">
            <label for="choices-multiple-primaries">Primaries</label>
            <select class="form-control" name="choices-multiple-primaries" id="choices-multiple-primaries" placeholder="This is a placeholder" multiple>
                @foreach ($primaries as $weapon)
                    <option value="{{$weapon->id}}">{{$weapon->name}}</option>
                @endforeach
            </select>
            

        </div>
        <div class="w-2/6 mr-3">
            <label for="choices-multiple-secondaries">Secondaries</label>
            <select class="form-control" name="choices-multiple-secondaries" id="choices-multiple-secondaries" placeholder="This is a placeholder" multiple>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {

        var multipleFetch = new Choices('#choices-multiple-remote-fetch', {
          placeholder: true,
          placeholderValue: 'Search Creators',
          maxItemCount: 5,
        }).setChoices(function() {
          return fetch(
            '/user/getUsers'
          )
            .then(function(response) {
              return response.json();
            })
            .then(function(data) {
              return data.map(function(user) {
                return { value: user.text, label: user.text };
              });
            });
        });

        var multipleCancelButton = new Choices(
          '#choices-multiple-primaries',
          {
            removeItemButton: true,
          }
        );
        var multipleCancelButton = new Choices(
          '#choices-multiple-secondaries',
          {
            removeItemButton: true,
          }
        );
    });
    </script>

@endsection
