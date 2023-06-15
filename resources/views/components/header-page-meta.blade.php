<div class="page-meta no-print">
    @auth
        <div class="login-meta">
            <p>{{ __("Logged in as") }}: {{ Auth::user()->getName() }}</p>
        </div>
    @endauth
    <div class="language">
        <a href="{{route('changelang', ['locale' => 'nl'])}}">Nederlands</a>
        <a href="{{route('changelang', ['locale' => 'en'])}}">English</a>
    </div>
</div>
