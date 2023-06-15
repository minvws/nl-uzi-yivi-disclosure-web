<nav
    data-open-label="Menu"
    data-close-label="Sluit menu"
    data-media="(min-width: 42rem)"
    aria-label="{{ __('Main navigation') }}"
    class="collapsible">
    <div class="collapsing-element">
        <ul>
            <li>
                <a href="/" aria-current="page"><span class="icon icon-home"></span>Start</a>
            </li>
        </ul>
        @auth
        <ul>
            <li>
                <x-header-logout-button />
            </li>
        </ul>
        @endauth
    </div>
</nav>
