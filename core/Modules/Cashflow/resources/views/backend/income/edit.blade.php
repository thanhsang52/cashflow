@extends('dashboard.layouts.master')
@section('title', __('backend.income'))
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/style.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/themify-icons.css') }}">
@endpush
@section('content')
<div class="padding">
	<div class="box">
		<div>
			<div class="box m-b-0">
				<div class="box-header dker">
					<h3><i class="material-icons"></i> Update Income</h3>
						<small>
							<a href="{{route('adminHome')}}">Home</a> /
							<a>Cashflow</a> /
							<a href="{{route('cashflow.income.index')}}">{{__('cashflow::backend.incomes')}}</a>
						</small>
				</div>
			</div>

			<div class="box b-info">
				<div class="box-body p-a-2">
				<form method="post" class="validate" autocomplete="off" action="{{action('\Modules\Cashflow\App\Http\Controllers\IncomeController@update', $id)}}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">				
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Transaction date') }}</label>
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
								{!! Form::text('trans_date',Helper::formatDate($transaction->trans_date), array('placeholder' => '','class' => 'form-control','id'=>'trans_date')) !!}
								<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
								</span>
								</div>
							</div>
						</div>

						
						
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Income Type') }}</label>						
								<select class="form-control select2" name="category_id" required>
								   <option value="">{{ __('Select One') }}</option>
								   {{ create_option("cashflow_transaction_categories","id","name",$transaction->category_id, array('type=' => 'income')) }}
								</select>
							</div>
						</div>


						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Amount') }}</label>						
								<input type="text" class="form-control float-field" name="amount" value="{{ $transaction->amount }}" required>
							</div>
						</div>
						
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Payment Method') }}</label>						
								<select class="form-control select2" name="payment_method_id" required>
								   <option value="">{{ __('Select One') }}</option>
								   {{ create_option("cashflow_payment_methods", "id", "name", $transaction->payment_method_id) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Attachment') }}</label>						
								<input type="file" class="form-control trickycode-file" data-value="{{ $transaction->attachment }}" name="attachment">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Reference') }}</label>						
								<input type="text" class="form-control" name="reference" value="{{ $transaction->reference }}">
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
										<option {{ $contract_term[0]==$transaction->contract_term_id?'selected':'' }} value="{{ $contract_term[0] }}">{{ $contract_term[1] }}</option>
									@endforeach
									</optgroup>
								@endforeach
							  </select>
							  <button class="btn btn-info btn-md ajax-modal select-modal-btn" data-title="{{ __('Click on a Term to select') }}" data-href="{{ route('cashflow.contract_term.index') }}" ><i class="ti-plus"></i> {{ __('Select') }}</button>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Note') }}</label>						
								<textarea class="form-control" name="note">{{ $transaction->note }}</textarea>
							</div>
						</div>
			
						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
								&#xe31b;</i>{!! __('backend.update') !!}</button>
								<a href="{{ route('cashflow.income.index') }}"
								class="btn btn-lg btn-default m-t"><i class="material-icons">
										&#xe5cd;</i> {!! __('backend.cancel') !!}</a>
							</div>
						</div>	
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@push("after-scripts")
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/app.js') }}"></script>
@endpush