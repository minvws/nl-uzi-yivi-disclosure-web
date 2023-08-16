@extends('layouts.guest')

@section('content')
    @if (session()->has('error'))
        <section role="alert" class="error no-print" aria-label="{{__('error') }}">
            <div>
                <p>
                    <span>{{ __('Error') }}:</span> {{ session('error') }}
                </p>
            </div>
        </section>
    @endif

    <section>
        <div class="layout-authentication">
            <h1>@lang('Login')</h1>
            <ul class="external-login">
                <li><a href="{{ route('oidc.login', ['login_hint' => 'digid']) }}"><img src="{{ asset('img/logo_digid_rgb.svg') }}" alt="DigiD logo">@lang('Login with') DigiD</a></li>
                <li><a href="{{ route('oidc.login', ['login_hint' => 'uzipas']) }}"><img src="{{ asset('img/uzipas.png') }}" alt="UZI-Pas chip">@lang('Login with') UZI-pas</a></li>
            </ul>
        </div>
    </section>
@endsection
