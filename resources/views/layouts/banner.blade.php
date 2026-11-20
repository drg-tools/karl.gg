{{-- Site-wide notice, shown on the dashboard only. Dismissing it hides this message in this browser until the message changes. --}}
@if (config('app.banner.enabled') && request()->is('/'))
    @php($bannerKey = 'karl:banner-dismissed:'.md5((string) config('app.banner.message')))
    {{-- Runs before the notice is drawn, so a dismissed notice never flashes on screen. --}}
    <script>try { if (localStorage.getItem(@js($bannerKey))) document.documentElement.classList.add('is-banner-dismissed'); } catch (e) {}</script>
    <div class="site-banner" role="status">
        <div>
            <p>
                {{ config('app.banner.message') }}
                @if (config('app.banner.link'))
                    <a href="{{ config('app.banner.link') }}">{{ config('app.banner.link_label', 'Learn more') }} <span aria-hidden="true">&rarr;</span></a>
                @endif
            </p>
            <button type="button" aria-label="Dismiss notice"
                    onclick="document.documentElement.classList.add('is-banner-dismissed'); try { localStorage.setItem(@js($bannerKey), '1') } catch (e) {}">&times;</button>
        </div>
    </div>
@endif
