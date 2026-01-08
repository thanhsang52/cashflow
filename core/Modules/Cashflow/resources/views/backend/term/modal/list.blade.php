@extends('dashboard.layouts.modal_layout')
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
@endpush
@section('content')

<div class="text-center">		
	<button class="btn btn-lg primary" id="terms-select" data-dismiss="modal"><i class="ti-plus"></i> {{ __('Select') }}</button>
</div>
<div class="table-responsive">
	<table id="term-table" class="table table-bordered">
		<thead>
			<tr>
				{{-- <th><input type="checkbox" class="check-all"></th> --}}
				<th>{{ __('Code') }}</th>
				<th>{{ __('Name') }}</th>
				<th>{{ __('Category') }}</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>


@endsection



@push("after-scripts")
<script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/ajax-datatables/term-modal.js') }}"></script>
@endpush