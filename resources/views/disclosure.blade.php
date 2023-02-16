@inject('ctrl', 'App\Http\Controllers\IrmaDisclosureController')

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
        <div id="irma-web-form" class="external-component"></div>

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
                        @csrf
                        <input type="button" value="Inladen" onclick="irmaStart({{ $ura->ura }})"></button>
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
    </div>
</section>
@push('scripts')
<script type="text/javascript" src="irmajs-v0.4.2/irma.js"></script>
<script type="text/javascript" src="js/disclosure.js"></script>
<link rel="stylesheet" href="irmajs-v0.4.2/irma.css">
@endpush
@endsection