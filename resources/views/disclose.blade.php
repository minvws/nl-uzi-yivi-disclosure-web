@inject('ctrl', 'App\Http\Controllers\IrmaDisclosureController')

@extends('layouts.app')
@section('content')
<section class="disclosure">
<div class="no-manon">
<div id="irma-web-form"></div>
</div>

</section>
@push('scripts')
<script type="text/javascript" src="irmajs-v0.4.2/irma.js"></script>
<script type="text/javascript" src="js/disclosure.js"></script>
@endpush
@endsection