<?php
use Hazzard\Comments\ScriptVariables;

$polyfills = [
    'Promise',
    'Object.assign',
    'Array.from',
    'Array.prototype.includes',
    'Element.prototype.closest'
];
?>
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name') }}</title>

  <link href="{{ ScriptVariables::mix('admin.css', 'vendor/comments') }}" rel="stylesheet">
  {{-- <link href="http://laravel-comments.app/public/admin.css" rel="stylesheet"> --}}
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium">
</head>
<body>
  <div id="app" v-cloak>
    <nav class="navbar navbar-expand-lg navbar-light bg-faded mb-4">
      <div class="container-lg">
        <button class="navbar-toggler navbar-toggler-right" type="button"
          data-toggle="collapse"
          data-target="#navbar"
          aria-controls="navbar"
          aria-expanded="false"
          @click="toggleNavbar"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <a href="/" class="navbar-brand">
          @lang('comments::admin.nav_item_home')
        </a>

        <div class="collapse navbar-collapse" id="navbar">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a href="{{ route('comments.dashboard') }}" class="nav-link {{ request()->routeIs('comments.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="align-middle" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M12 1c-6.338 0-12 4.226-12 10.007 0 2.05.738 4.063 2.047 5.625.055 1.83-1.023 4.456-1.993 6.368 2.602-.47 6.301-1.508 7.978-2.536 9.236 2.247 15.968-3.405 15.968-9.457 0-5.812-5.701-10.007-12-10.007zm0 14h-6v-1h6v1zm6-3h-12v-1h12v1zm0-3h-12v-1h12v1z"/></svg>
                @lang('comments::admin.nav_item_dashboard')
              </a>
            </li>

            @if (config('comments.settings'))
            <li class="nav-item">
              <a href="{{ route('comments.settings') }}" class="nav-link {{ request()->routeIs('comments.settings') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="align-middle" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M24 13.616v-3.232c-1.651-.587-2.694-.752-3.219-2.019v-.001c-.527-1.271.1-2.134.847-3.707l-2.285-2.285c-1.561.742-2.433 1.375-3.707.847h-.001c-1.269-.526-1.435-1.576-2.019-3.219h-3.232c-.582 1.635-.749 2.692-2.019 3.219h-.001c-1.271.528-2.132-.098-3.707-.847l-2.285 2.285c.745 1.568 1.375 2.434.847 3.707-.527 1.271-1.584 1.438-3.219 2.02v3.232c1.632.58 2.692.749 3.219 2.019.53 1.282-.114 2.166-.847 3.707l2.285 2.286c1.562-.743 2.434-1.375 3.707-.847h.001c1.27.526 1.436 1.579 2.019 3.219h3.232c.582-1.636.75-2.69 2.027-3.222h.001c1.262-.524 2.12.101 3.698.851l2.285-2.286c-.744-1.563-1.375-2.433-.848-3.706.527-1.271 1.588-1.44 3.221-2.021zm-12 2.384c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4z"/></svg>
                @lang('comments::admin.nav_item_settings')
              </a>
            </li>
            @endif
          </ul>
          <ul class="navbar-nav">
            <li class="nav-item">
              <a @click.prevent="logout" href="#" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="align-middle" width="22" height="22" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M16 9v-4l8 7-8 7v-4h-8v-6h8zm-16-7v20h14v-2h-12v-16h12v-2h-14z"/>
                </svg>
                @lang('comments::admin.nav_logout')
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-lg mb-2">
      @yield('content')
    </div>
  </div>

  {{ ScriptVariables::render() }}

  @if (config('comments.emoji'))
    <script src="https://twemoji.maxcdn.com/v/13.1.0/twemoji.min.js" integrity="sha384-gPMUf7aEYa6qc3MgqTrigJqf4gzeO6v11iPCKv+AP2S4iWRWCoWyiR+Z7rWHM/hU" crossorigin="anonymous"></script>
  @endif

  <script src="https://cdn.polyfill.io/v3/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

  <script src="{{ ScriptVariables::mix('admin.js', 'vendor/comments') }}"></script>
  {{-- <script src="http://laravel-comments.app/public/admin.js"></script> --}}
</body>
</html>
