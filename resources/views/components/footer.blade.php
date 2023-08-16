<footer>
    <div class="two-thirds-one-third">
        <span class="slogan" lang="nl">De Rijksoverheid. Voor Nederland</span>

        <nav aria-label="@lang('Footer navigation')">
            <ul>
                <li><a href="{{ route('privacy-statement') }}">@lang('Privacy statement')</a></li>
                <li><a href="{{ route('home') }}">@lang('About') @lang('YIVI login route')</a></li>
            </ul>
        </nav>

        <!-- <div class="meta">
            <p>{{ __('Version')}} {{ App\Http\Kernel::applicationVersion() }}
            </p>
        </div> -->
    </div>
</footer>
