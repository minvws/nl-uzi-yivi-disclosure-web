@extends('layouts.app')
@section('content')
<section role="alert" class="explanation no-print" aria-label="{{__('explanation') }}">
    <div>
        <p><span>Toelichting:</span> Let op, deze weergave is onderdeel van de pilot-omgeving.</p>
    </div>
</section>

@if (session()->has('error'))
    <section role="alert" class="error no-print" aria-label="{{__('error') }}">
        <div>
            <h4>{{ session('error') }}</h4>
            <p>{{ session('error_description') }}</p>
        </div>
    </section>
@endif

<section class="disclosure">
    <div>
        <h1>YIVI inlogroute</h1>
        <p>
            Hieronder ziet u een overzicht van instanties waar u kunt inloggen met YIVI. Per instantie ziet u de rollen waarmee u bij deze instatie bekend bent volgens het UZI-register. 
            U kunt deze gegevens inladen in de YIVI wallet met de gegeven QR-code.
            Per QR-code wordt in de YIVI app vervolgens een kaartje gegenereerd, waarmee u kunt inloggen bij de gekoppelde instantie.
            Om de gegevens in te laden, klikt u op de knop "ToonQR-code". U scant deze vervolgens met de "Scan QR" functie in de YIVI app op uw mobiele telefoon.</p>
        <div id="yivi-web-form" class="external-component"></div>

        <div class="horizontal-scroll">
            <table>
                <caption class="visually-hidden">Overzicht van beschikbare instanties:</caption>
                <thead>
                    <tr>
                        <th scope="col">Instantie</th>
                        <th scope="col">Rollen</th>
                        <th scope="col">Inladen via YIVI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userUras as $ura)
                    <tr>
                        <td>{{ $ura->entityName }}</td>
                        <td>{{ $ura->getVisibleRoleNames() }}</td>
                        <td>
                            <div>
                                <button data-yivi-start-button data-csrf-token="{{ csrf_token() }}" data-yivi-ura="{{ $ura->ura }}" type="button" class="ghost">Toon QR-code</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
