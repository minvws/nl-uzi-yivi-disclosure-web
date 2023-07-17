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
                <p>@lang('You are currently not in the UZI-pilot.<br>Do you want to join the UZI-pilot? Send an email with the request that you want to join the UZI-pilot to') <a href="mailto:info-uzi@minvws.nl">info-uzi@minvws.nl</a>.</p>

                <form method="post" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <input type="submit" value="{{ __('Logout') }}" />
                </form>
            </div>
        </div>
    </section>
@endsection
