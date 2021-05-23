<div>
<div class="my-2 flex sm:flex-row flex-wrap">
        <div class="w-1/6 mr-3">
            <label class="block">
                <span class="text-gray-300">Overclocks?</span>
                <select class="form-select mt-1 block w-full">
                    <option>--</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </label>
        </div>
        <div class="w-1/6 mr-3">
            <label class="block">
                <span class="text-gray-300">Loadout Guide?</span>
                <select class="form-select mt-1 block w-full">
                    <option>--</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </label>
        </div>
        <div class="w-1/6 mr-3">
            <!-- For defining autocomplete -->
            <label for="choices-multiple-remote-fetch" class="block">Creator Name</label>
            <select class="form-control text-gray-900" name="choices-multiple-remote-fetch" id="choices-multiple-remote-fetch" multiple></select>
                
            
        
            

        </div>
    </div>
    <div class="flex flex-row">
        <div class="w-2/6 mr-3">
            <label class="block">
                <span class="text-gray-300">Primary</span>
                
                
                <select class="form-select mt-1 block w-full js-example-basic-multiple" name="primaries[]" multiple="multiple"  x-ref="primarySelect" data-placeholder="Select a Primary">
                    @foreach ($primaries as $weapon)
                        <option value="{{$weapon->id}}">{{$weapon->name}}</option>
                    @endforeach
                    
                    
                </select>
            </label>

        </div>
        <div class="w-2/6 mr-3">
            <label class="block">
                <span class="text-gray-300">Loadout Guide?</span>
                <select class="form-select mt-1 block w-full js-example-basic-multiple" name="states[]" multiple="multiple">
                    @foreach ($secondaries as $gun)
                        <option value="{{$gun->id}}">{{$gun->name}}</option>
                    @endforeach
                    
                    
                </select>
            </label>
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
    });
    </script>

@endsection
