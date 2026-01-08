@extends('dashboard.layouts.master')
@section('title', __('backend.expense'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/plugins/select2/select2.min.css') }}">
@endpush

@section('content')

<div class="row">
	<div class="col-12">
	    <button class="btn btn-primary btn-xs ajax-modal" data-title="{{ __('Add Expense') }}" data-href="{{ route('cashflow.expense.create') }}"><i class="ti-plus"></i> {{ __('Add Expense') }}</button>

		<div class="card mt-2">
			<span class="d-none panel-title">{{ __('All Expense') }}</span>
			
			<div class="card-body">
				<table id="expense-table" class="table table-bordered">
					<thead>
						<tr>
							<th>{{ __('Date') }}</th>
							<th>{{ __('Account') }}</th>
							<th>{{ __('Category') }}</th>
							<th class="text-right">{{ __('Amount') }}</th>
							<th class="action-col">{{ __('Action') }}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
			  </table>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-script')
<script src="{{ asset('public/backend/assets/js/ajax-datatables/expense.js') }}"></script>
@endsection


