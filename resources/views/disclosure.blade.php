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
        <h1>hier disclosures</h1>
        <p>Veel text</p>
        <div id="yivi-web-form" class="external-component"></div>

        <div class="horizontal-scroll">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Instantie</th>
                        <th scope="col">Rollen</th>
                        <th scope="col">Inladen IRMA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (Auth::user()->uras as $ura)
                    <tr>
                        <td>{{ $ura->entityName }} ({{ $ura->ura }})</td>
                        <td>@foreach ($ura->roles as $role){{ $role }}<br> @endforeach</td>
                        <td>
                            <div>
                                <button data-yivi-start-button data-csrf-token="{{ csrf_token() }}" data-yivi-ura="{{ $ura->ura }}" type="button">Inladen</button>
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
