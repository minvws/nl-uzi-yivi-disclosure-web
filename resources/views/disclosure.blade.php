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
        <h1>@lang('YIVI login route')</h1>
        <p>@lang('Below you will find an overview of organizations where you can log in with YIVI. For each organization, you will see the roles associated with your account according to the UZI-register. You can load these details into the YIVI wallet using the provided QR code. For each QR code, a card will be generated in the YIVI app, allowing you to log in to the associated organization. To load the details, click on the "Show QR Code" button. Then scan it using the "Scan QR" function in the YIVI app on your mobile phone.')</p>
        <div id="yivi-web-form" class="external-component"></div>

        <div class="horizontal-scroll">
            <table>
                <caption class="visually-hidden">@lang('Overview of available organizations:')</caption>
                <thead>
                    <tr>
                        <th scope="col">@lang('Organisation')</th>
                        <th scope="col">@lang('Roles')</th>
                        <th scope="col">@lang('Load with YIVI')</th>
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
