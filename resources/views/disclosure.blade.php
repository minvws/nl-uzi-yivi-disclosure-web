@extends('layouts.app')
@section('content')
<section class="disclosure">
    @if (session()->has('error'))
    <section role="alert" class="error no-print" aria-label="{{__('error') }}">
        <div>
            <h4>{{ session('error') }}</h4>
            <p>{{ session('error_description') }}</p>
        </div>
    </section>
    @endif
    <div>
        <h1>Yivi kaartjes uitgifte</h1>
        <p>Hier ziet u een overzicht van instanties waar u een werkrelatie mee heeft. Per instantie kunt u een yivi kaartje inladen in de yivi app. Klik op "Toon QR-code" om het kaartje in de app te laden.</p>
        <div id="yivi-web-form" class="external-component"></div>

        <div class="horizontal-scroll">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Instantie</th>
                        <th scope="col">Rollen</th>
                        <th scope="col">Inladen via Yivi</th>
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
