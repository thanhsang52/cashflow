
@extends('inventory::pos.layouts.modal')
@push("after-styles")
	<link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
	
@endpush
@section('content')


	    
		
		<div class="">
			<span class="d-none panel-title">{{ __('Orders') }}</span>

			<div class="card-body">
				
				<table id="orders-table" class="table table-bordered">
					<thead>
						<tr>
							<th></th>
							<th>{{ __('ID') }}</th>
							<th>{{ __('Payment') }}</th>
							<th>{{ __('Amount') }}</th>
							<th>{{ __('Created by') }}</th>
							<th class="action-col">{{ __('Options') }}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
			  </table>
			</div>
		</div>

		



@endsection

@push("after-scripts")

<script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('core/Modules/Inventory/Resources/assets/js/order.js') }}"></script>
@endpush
