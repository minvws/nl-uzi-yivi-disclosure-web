@extends('layouts.app')
@section('content')
<section role="alert" class="explanation no-print" aria-label="{{ __('Explanation') }}">
    <div>
        <p><span>@lang('Explanation'):</span> @lang('Please note, this view is part of the pilot environment.')</p>
    </div>
</section>

@if (session()->has('error'))
    <section role="alert" class="error no-print" aria-label="{{ __('Error') }}">
        <div>
            <p><span>{{ __('Error') }}:</span> {{ session('error') }} </p>
        </div>
    </section>
@endif

<section class="disclosure">
    <div>
        <h1>@lang('Yivi login route')</h1>
        <p>@lang('Below you will find an overview of organizations you work for, along with your associated role as registered in the UZI register. For each organization, you can load the details into the Yivi app by clicking the "Show QR code" button. A QR code will then be generated, which you can scan with the Yivi app on your mobile phone using the "Scan QR" function. After that, the Yivi card will be available in the Yivi app and you can use it.')</p>
        <div id="yivi-web-form" class="external-component"></div>

        <div class="horizontal-scroll">
            <table>
                <caption class="visually-hidden">@lang('Overview of available organizations:')</caption>
                <thead>
                    <tr>
                        <th scope="col">@lang('Organisation')</th>
                        <th scope="col">@lang('Roles')</th>
                        <th scope="col">@lang('Load with Yivi')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($userUras as $ura)
                        <tr>
                            <td>{{ $ura->entityName }}</td>
                            <td lang="nl">{{ $ura->getVisibleRoleNames() }}</td>
                            <td>
                                <div>
                                    <button data-yivi-start-button data-csrf-token="{{ csrf_token() }}" data-yivi-ura="{{ $ura->ura }}" type="button" class="ghost">@lang('Show QR Code')</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">@lang('No associated organizations found.')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
