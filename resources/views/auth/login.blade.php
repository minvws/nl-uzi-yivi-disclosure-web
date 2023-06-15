@extends('layouts.guest')

@section('content')
<section>
    @if (session()->has('error'))
    <section role="alert" class="error no-print" aria-label="{{__('error') }}">
        <div>
            <h4>{{ session('error') }}</h4>
            <p>{{ session('error_description') }}</p>
        </div>
    </section>
    @endif
    <div>
        <h1>Welkom bij YIVI</h1>
        <p class="emphasized">Op deze website kan je kaartjes inladen in je YIVI-app voor alle zorgplatformen waar je toegang voor krijgt vanuit het UZI-register.</p>
        <p>Het laden van deze kaartjes in een wallet-app biedt zorgverleners efficiënte identiteitsverificatie, directe toegang tot relevante medische informatie, verbeterde gegevensnauwkeurigheid en vereenvoudigde administratieve processen. </p>
        <p>Dit resulteert in tijdbesparing, verbeterde besluitvorming, verhoogde efficiëntie en een betere zorgervaring voor zowel zorgverleners als patiënten.</p>

        <a href="{{ route('uzi.login') }}">{{ __('Login with DigiD') }}</a>
    </div>
</section>
@endsection