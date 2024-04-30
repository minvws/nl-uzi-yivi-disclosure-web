@extends('layouts.guest')

@section('content')
    <section class="layout-authentication">
        <div>
            <div class="error" role="group" aria-label="{{__('Error') }}">
                <span>@lang('Error')</span>
                <h1>
                    @lang('Error')
                    @lang(403)
                </h1>
                <p>@lang('You are currently not in the Dezi-pilot.<br>Do you want to join the Dezi-pilot? Send an email with the request that you want to join the Dezi-pilot to') <a href="mailto:info@dezi.nl">info@dezi.nl</a>.</p>
            </div>
        </div>
    </section>
@endsection
