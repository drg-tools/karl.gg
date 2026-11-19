@props([
    'adSlot',
    'format' => 'auto',
])

@if (config('app.enable_ads'))
    <div {{ $attributes->class(['ad-unit', 'ad-unit--'.$format]) }}>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ config('app.adsense_client') }}"
             data-ad-slot="{{ $adSlot }}"
             data-ad-format="{{ $format }}"
             @if ($format === 'auto') data-full-width-responsive="true" @endif></ins>
        <script>
            (function (ad) {
                {{-- A unit hidden by CSS (e.g. the browse sidebar on mobile) can't be sized,
                     and adsbygoogle fills the first empty <ins>, so drop it instead. --}}
                if (ad.offsetWidth === 0) {
                    ad.parentElement.remove();

                    return;
                }

                (window.adsbygoogle = window.adsbygoogle || []).push({});
            })(document.currentScript.previousElementSibling);
        </script>
    </div>
@endif
