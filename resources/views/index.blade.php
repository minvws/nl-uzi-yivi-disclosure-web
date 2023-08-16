@extends('layouts.guest')

@section('content')
    @if (session()->has('error'))
        <section role="alert" class="error no-print" aria-label="{{ __('error') }}">
            <div>
                <strong>{{ session('error') }}</strong>
                <p>{{ session('error_description') }}</p>
            </div>
        </section>
    @endif

    <section>
        <div>
            <h1>@lang('Welcome to the YIVI login route')</h1>
            <p class="emphasized">@lang('On this website, you can load cards into your YIVI app for all healthcare platforms for which you gain access from the UZI register.')</p>
            <p>@lang('Loading these cards into a wallet app offers healthcare providers efficient identity verification, direct access to relevant medical information, improved data accuracy, and simplified administrative processes.')</p>
            <p>@lang('This results in time savings, improved decision-making, increased efficiency, and a better healthcare experience for both healthcare providers and patients.')</p>
        </div>
    </section>

    <section class="background-color-offset">
        <div>
            <h2>@lang('Login')</h2>
            <p>@lang('Login using one of the options below:')</p>
            <ul class="external-login">
                <li>
                    <a href="{{ route('oidc.login', ['login_hint' => 'digid']) }}">
                        <img src="{{ asset('img/logo_digid_rgb.svg') }}" alt="DigiD logo" rel="external">
                        @lang('Login with') DigiD
                    </a>
                </li>
                <li>
                    <a href="{{ route('oidc.login', ['login_hint' => 'uzipas']) }}">
                        <img src="{{ asset('img/uzipas.png') }}" alt="" rel="external">
                        @lang('Login with') UZI-pas
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endsection
