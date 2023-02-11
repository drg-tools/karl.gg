
@if(config('app.enable_ads'))

@once
    @push('styles')
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3237881937260629"
                crossorigin="anonymous"></script>
    @endpush
@endonce

{{ $slot }}

<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>

@endif
