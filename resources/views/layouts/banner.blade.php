@if(config('app.banner.enabled'))
    <div class="site-banner" role="status">
        <p>
            {{ config('app.banner.message') }}
            @if (config('app.banner.link'))
                <a href="{{ config('app.banner.link') }}">{{ config('app.banner.link_label', 'Learn more') }} <span aria-hidden="true">&rarr;</span></a>
            @endif
        </p>
    </div>
@endif
