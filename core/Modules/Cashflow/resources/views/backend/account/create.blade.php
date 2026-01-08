@extends('dashboard.layouts.master')
@section('title', __('cashflow::backend.accounts'))
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/style.css') }}">
@endpush

@section('content')
<div class="padding">
	<div class="box">
		<div class="box m-b-0">
			<div class="box-header dker">
				<h3><i class="material-icons"></i>{{__('Create Account')}} </h3>
					<small>
						<a href="{{route('adminHome')}}">Home</a> /
						<a>Cashflow</a> /
						<a href="{{route('cashflow.accounts.index')}}">{{__('cashflow::backend.accounts')}}</a>
					</small>
			</div>
		</div>
		<div class="box b-info">
			<div class="box-body p-a-2">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('cashflow.accounts.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ __('Name') }}</label>						
						        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
					        </div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ __('Account No') }}</label>						
						        <input type="text" class="form-control" name="account_no" value="{{ old('account_no') }}">
					        </div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ __('Account Currency') }}</label>						
						        <select class="form-control auto-select select2" data-selected="{{ old('currency_id') }}" name="currency_id" required>
					                <option value="">{{ __('Select One') }}</option>
									{{ create_option('cashflow_currency','id','name',old('currency_id'), array('status='=>1)) }}
								</select>
							</div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ __('Openning Balance') }}</label>						
						        <input type="text" class="form-control float-field" name="openning_balance" value="{{ old('openning_balance') }}" required>
					        </div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ __('Contact Person') }}</label>						
						        <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person') }}">
					        </div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ __('Contact Email') }}</label>						
						        <input type="text" class="form-control" name="contact_email" value="{{ old('contact_email') }}">
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
								<button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
								&#xe31b;</i>{!! __('backend.save') !!}</button>
								<a href="{{ route('cashflow.accounts.index') }}"
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
@endsection


