<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '') }}</title>
    <link rel="preload" media="screen and (min-width: 768px)" href="/huisstijl/img/ro-logo.svg" as="image">
    <link rel="preload" href="/huisstijl/fonts/RO-SansWebText-Regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/huisstijl/fonts/RO-SansWebText-Bold.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ url('huisstijl/css/app.css') }}">
    <link href="/huisstijl/img/favicon.ico" rel="shortcut icon">
    <script defer src="/js/form-help.min.js"></script>
    <script src="{{ url('js/app.js') }}"></script>
</head>
<body>
<header>
    <a href="#main-content" class="button focus-only">{{__('Skip to content')}}</a>

    <section class="page-meta">
        <x-language />
    </section>

    <a href="/" class="ro-logo" aria-label="{{__('Rijksoverheid logo, go to the KES homepage')}}">
        <img src="/huisstijl/img/ro-logo.svg" alt="Logo Rijksoverheid">
        Rijksoverheid
    </a>
  <section>
    <div>
      <nav aria-label="Hoofdnavigatie">
        <ul>
            <li><a href="/" aria-current="page">IRMA Disclosure website</a></li>
        </ul>
      </nav>
      <form method="post" action="{{ route('logout') }}" class="line-form">
        @csrf
        <button type="submit">Logout</button>
      </form>
    </div>
</section>
</header>
<main id="main-content" tabindex="-1">
    @yield('content')
</main>
<footer>
    <div>
        <span>De Rijksoverheid. Voor Nederland</span>

        <div class="meta">
            <p>
                {{ __('Version')}}
                {{ App\Http\Kernel::applicationVersion() }}
            </p>
        </div>
    </div>
</footer>
@stack('scripts')
</body>
</html>
