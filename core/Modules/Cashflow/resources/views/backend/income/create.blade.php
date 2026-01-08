@extends('dashboard.layouts.master')
@section('title', __('backend.income'))
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/style.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/themify-icons.css') }}">
@endpush
@section('content')
<div class="padding">
	<div class="box m-b-0"">
		<div class="box-header dker">
			<h3>{{ __('Add Income') }}</h3>
			<small>
				<a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
				<a>{{ __('Cashflow') }}</a> /
				<a href="{{route('cashflow.income.index')}}">{{ __('Income') }}</a>
			</small>
		</div>
	</div>
	<div class="">
		<div class="box b-info">
			<div class="box-body p-a-2">
				<form method="post" class="validate" autocomplete="off" action="{{ route('cashflow.income.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					
					<div class="row">
						<div class="col-md-6">
						  <div class="form-group">
							<label class="control-label">{{ __('Date') }}</label>
							<div class='input-group date' ui-jp="datetimepicker" ui-options="{
								format: '{{ Helper::jsDateFormat() }}',
								icons: {
								time: 'fa fa-clock-o',
								date: 'fa fa-calendar',
								up: 'fa fa-chevron-up',
								down: 'fa fa-chevron-down',
								previous: 'fa fa-chevron-left',
								next: 'fa fa-chevron-right',
								today: 'fa fa-screenshot',
								clear: 'fa fa-trash',
								close: 'fa fa-remove'
								},
							allowInputToggle: true,
							locale:'{{ @Helper::currentLanguage()->code }}'
							}">
							{!! Form::text('trans_date',Helper::formatDate(old('trans_date')), array('placeholder' => '','class' => 'form-control','id'=>'trans_date')) !!}
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
							</div>						
							<!-- <input type="text" class="form-control datepicker" name="trans_date" value="{{ old('trans_date') }}" required> -->
						  </div>
						</div>

						

						<div class="col-md-6">
						  <div class="form-group">
							<label class="control-label">{{ __('Income Type') }}</label>						
							<select class="form-control select2" name="category_id" required>
							   <option value="">{{ __('Select One') }}</option>
							   {{ create_option("cashflow_transaction_categories","id","name",old('category_id'), array('type=' => 'income')) }}
							</select>
						  </div>
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label class="control-label">{{ __('Amount') }}</label>						
							<input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
						  </div>
						</div>

						
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Payment Method') }}</label>						
								<select class="form-control select2" name="payment_method_id" required>
								   <option value="">{{ __('Select One') }}</option>
								   {{ create_option("cashflow_payment_methods","id","name",old('payment_method_id')) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
							  <label class="control-label">{{ __('Attachment') }}</label>						
							  <input type="file" class="form-control trickycode-file" name="attachment">
							</div>
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label class="control-label">{{ __('Reference') }}</label>						
							<input type="text" class="form-control" name="reference" value="{{ old('reference') }}">
						  </div>
						</div>

					</div>
					<div class="row">	

						<div class="col-md-6">
							<div class="form-group">
							  <label class="control-label">{{ __('Term') }}</label>						
							  <select class="form-control " name="contract_term_id" required>
								 <option value="">{{ __('Select One') }}</option>
								 <option value="0">{{ __('Non Contract') }}</option>
								@foreach($contractTermGrouped as $group => $value)
									<optgroup label="{{ $group }}">
									@foreach ($value as $contract_term)
										<option value="{{ $contract_term[0] }}">{{ $contract_term[1] }}</option>
									@endforeach
									</optgroup>
								@endforeach
							  </select>
							  <button class="btn btn-info btn-sm ajax-modal select-modal-btn" data-title="{{ __('Click on a Term to select') }}" data-href="{{ route('cashflow.contract_term.index') }}" ><i class="ti-plus"></i> {{ __('Select') }}</button>
							</div>
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label class="control-label">{{ __('Note') }}</label>						
							<textarea class="form-control" name="note">{{ old('note') }}</textarea>
						  </div>
						</div>
					
						<div class="col-md-12">
						  <div class="form-group">
							<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
						  </div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection


@push("after-scripts")
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/app.js') }}"></script>
@endpush