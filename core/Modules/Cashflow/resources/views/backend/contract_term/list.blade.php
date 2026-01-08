@extends('dashboard.layouts.modal_layout')
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/plugins/select2/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
	
@endpush
@section('content')
<div class="padding">
	<div class="">
	    
		
		<div class="">
			<span class="d-none panel-title">{{ __('All Term') }}</span>

			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label><strong>Contract :</strong></label>
							<select class="form-control" id="filter-contract-id" name="contract_id">
								<option value="">{{ __('All') }}</option>
								@foreach(\Modules\Cashflow\App\Models\Contract::all() as $contract)
								<option value="{{ $contract->id }}">{{ $contract->display_name }}</option>
								@endforeach
				
							</select>
						</div>
					</div>
				</div>
				<table id="term-table" class="table table-bordered">
					<thead>
						<tr>
							<th>{{ __('Contract') }}</th>
							<th>{{ __('Term Code') }}</th>
							<th>{{ __('Term Name') }}</th>
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

@push("after-scripts")
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/ajax-datatables/contract-term.js') }}"></script>
@endpush

