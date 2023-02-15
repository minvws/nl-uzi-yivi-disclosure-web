@extends('layouts.guest')

@section('content')
<section class="auth">
    @if (session()->has('error'))
    <section role="alert" class="error no-print" aria-label="{{__('error') }}">
        <div>
            <h4>{{ session('error') }}</h4>
            <p>{{ session('error_description') }}</p>
        </div>
    </section>
    @endif
    <div>
        <h1>hier inloggen</h1>
        <p>Veel text</p>
        <a href="{{ route('uzi.login') }}">{{ __('Login with DigiD') }}</a>
    </div>
</section>
@endsection