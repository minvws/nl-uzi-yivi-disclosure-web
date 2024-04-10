<div class="page-meta no-print">
    @auth
        <div class="login-meta">
            <p>{{ __("Logged in as") }}: {{ Auth::user()->getName() }}</p>
        </div>
    @endauth
</div>
