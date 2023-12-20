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
            <h1>@lang('Welcome to the YIVI login route')</h1>
            <p class="emphasized">@lang('On this website, you can load cards into your YIVI app for all healthcare platforms for which you gain access from the UZI register.')</p>
            <p>@lang('Loading these cards into a wallet app offers healthcare providers efficient identity verification, direct access to relevant medical information, improved data accuracy, and simplified administrative processes.')</p>
            <p>@lang('This results in time savings, improved decision-making, increased efficiency, and a better healthcare experience for both healthcare providers and patients.')</p>
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
                        @lang('Login with') UZI-online
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endsection
