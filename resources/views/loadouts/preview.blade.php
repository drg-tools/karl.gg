@extends('layouts.app')

@section('header')
    {{ $loadout->name }}
@endsection

@section('content')

    <div class="bg-gray-700 text-gray-300 px-3 py-2 shadow sm:rounded-md">
        <x-loadout-outdated :loadout="$loadout"></x-loadout-outdated>
        <x-preview-top :loadout="$loadout" />

        <loadout-preview-page
            :loadout-data="{!! $loadout !!}"
            @if($loadout->primary_gun)
            :primary="{{ $loadout->primary_gun }}"
            :primary-mods="{{ $loadout->primary_gun->mods }}"
            @endif
            @if($loadout->secondary_gun)
            :secondary="{{ $loadout->secondary_gun }}"
            :secondary-mods="{{ optional($loadout->secondary_gun)->mods }}"
            @endif
            :overclocks="{{ $loadout->overclocks }}"
            :available-equipment="{{ $availableEquipment }}"
            @if($loadout->equipments)
            :equipment-mods="{{ $loadout->equipments }}"
            @endif
        >
        </loadout-preview-page>

        <div class="p-4">
            <h2 class="text-karl-orange text-xl uppercase">Comments</h2>
            <div id="disqus_thread"></div>
        </div>


    </div>

@endsection

@section('scripts')
    <script>
        /**
         *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
         *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
         */
        var disqus_config = function () {
            this.page.url = "{{ url()->current() }}";  // Replace PAGE_URL with your page's canonical URL variable
            this.page.identifier = "{{ $loadout->id }}"; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
        };

        (function() { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            s.src = 'https://karlgg.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
@endsection
