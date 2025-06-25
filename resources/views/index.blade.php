@extends('layouts.guest')

@section('content')
    @if (session()->has('error'))
        <section role="alert" class="error no-print" aria-label="{{ __('Error') }}">
            <div>
                <p><span>{{ __('Error') }}:</span> {{ session('error') }} </p>
            </div>
        </section>
    @endif

    <section>
        <div>
            <h1>@lang('Welcome to the Yivi login route')</h1>
            <p class="emphasized">@lang('On this website, you can load cards into your Yivi app for all organizations you work for; this information comes from the UZI register.')</p>
            <p>@lang('The use of these cards through a wallet app offers healthcare providers efficient identity verification, direct access to relevant medical information, improved data accuracy, and simplified administrative processes.')</p>
        </div>
    </section>

    <section class="background-color-offset">
        <div>
            <h2>@lang('Login')</h2>
            <p>@lang('Login using the option below:')</p>
            <ul class="external-login">
                <li>
                    <a href="{{ route('oidc.login') }}">
                        <img src="{{ asset('img/signin-method-logo.png') }}" alt="" rel="external">
                        @lang('Login with') Dezi-online
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endsection
