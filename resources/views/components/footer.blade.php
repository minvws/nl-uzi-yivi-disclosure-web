<footer class="no-print">
	<div>
        @if(config('app.mode') === 'ZKVI')
	    	<span>Ziekenhuizen Kunnen Vaccinaties Invoeren</span>
        @else
            <span>@lang('For the registration of your vaccination')</span>
        @endif

        @if(config('app.mode') !== 'ZKVI')
        <nav aria-label="Juridische documentatie navigatie">
            <ul>
                <li><a href="{{ route('privacy') }}">@lang('Privacy statement')</a></li>
            </ul>
	    </nav>
        @endif

	    <div class="meta">
			<p>
		        {{ __('Version')}}
		        {{ App\Http\Kernel::applicationVersion() }}
		    </p>
		</div>
	</div>
</footer>
