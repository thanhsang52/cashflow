@extends('dashboard.layouts.master')

@section('content')
<div class="padding">
	<div class="box m-b-0">
		<div class="box-header dker">
			<h3><i class="material-icons"></i> {{ __('Edit Currency') }} <span class="label primary text-sm">{{ $currency->name }}</span></h3>
				<small>
					<a href="https://cashflow.local/">Home</a> /
					<a>Cashflow</a> /
					<a>{{ __('Currency') }}</a>
				</small>
		</div>
	</div>
	<div class="box b-info">
		<div class="box-body p-a-2">
			<form method="post" class="validate" autocomplete="off" action="{{ route('cashflow.currency.update', $id) }}" enctype="multipart/form-data">
				{{ csrf_field()}}
				<input name="_method" type="hidden" value="PATCH">				
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label class="control-label">{{ __('Name') }}</label>						
							<select class="form-control auto-select select2" data-selected="{{ $currency->name }}" name="name" required>
								<option value="">{{ __('Select One') }}</option>
								{{ get_currency_list($currency->name ) }}
							</select>
						</div>
					</div>

					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label class="control-label">{{ __('Base Currency') }}</label>						
							<select class="form-control auto-select" data-selected="{{ $currency->base_currency }}" name="base_currency">
								<option value="">{{ __('Select One') }}</option>
								<option {{$currency->base_currency==0?'selected':''}} value="0">{{ __('No') }}</option>
								<option {{$currency->base_currency==1?'selected':''}} value="1">{{ __('Yes') }}</option>
							</select>
						</div>
					</div>

					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label class="control-label">{{ __('Exchange Rate') }}</label>						
							<input type="text" class="form-control" name="exchange_rate" value="{{ $currency->exchange_rate }}" required>
						</div>
					</div>

					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label class="control-label">{{ __('Status') }}</label>						
							<select class="form-control auto-select" data-selected="{{ $currency->status }}" name="status" required>
								<option {{$currency->status==1?'selected':''}} value="1">{{ __('Active') }}</option>
								<option {{$currency->status==0?'selected':''}} value="0">{{ __('InActive') }}</option>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
							&#xe31b;</i>{!! __('backend.update') !!}</button>
							<a href="{{ route('cashflow.currency.index') }}"
								class="btn btn-lg btn-default m-t"><i class="material-icons">
									&#xe5cd;</i> {!! __('backend.cancel') !!}</a>
						</div>
					</div>
				</div>	
			</form>
		</div>
	</div>
</div>
@endsection


