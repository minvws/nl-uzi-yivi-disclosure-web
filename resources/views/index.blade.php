@extends('layouts.guest')

@section('content')
    @if (session()->has('error'))
        <section role="alert" class="error no-print" aria-label="{{__('error') }}">
            <div>
                <h4>{{ session('error') }}</h4>
                <p>{{ session('error_description') }}</p>
            </div>
        </section>
    @endif

    <section>
        <div>
            <h1>Welkom bij de YIVI inlogroute</h1>
            <p class="emphasized">Op deze website kan je kaartjes inladen in je YIVI-app voor alle zorgplatformen waar je toegang voor krijgt vanuit het UZI-register.</p>
            <p>Het laden van deze kaartjes in een wallet-app biedt zorgverleners efficiënte identiteitsverificatie, directe toegang tot relevante medische informatie, verbeterde gegevensnauwkeurigheid en vereenvoudigde administratieve processen. </p>
            <p>Dit resulteert in tijdbesparing, verbeterde besluitvorming, verhoogde efficiëntie en een betere zorgervaring voor zowel zorgverleners als patiënten.</p>
        </div>
    </section>

    <section class="background-color-offset">
        <div>
            <h2>Inloggen</h2>
            <p>Log in via een van de onderstaande opties:</p>
            <ul class="external-login">
                <li><a href="{{ route('oidc.login', ['login_hint' => 'digid']) }}"><img src="{{ asset('img/logo_digid_rgb.svg') }}" alt="DigiD logo">Inloggen met DigiD</a></li>
                <li><a href="{{ route('oidc.login', ['login_hint' => 'uzipas']) }}"><img src="{{ asset('img/uzipas.png') }}" alt="UZI-Pas chip">Inloggen met UZI-pas</a></li>
            </ul>
        </div>
    </section>
@endsection
