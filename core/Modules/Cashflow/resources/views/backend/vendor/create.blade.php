@extends('dashboard.layouts.master')

@section('content')
<div class="padding">
	<div class="box m-b-0"">
		<div class="box-header dker">
			<h3>{{ __('Add Vendor') }}</h3>
			<small>
				<a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
				<a>{{ __('Cashflow') }}</a> /
				<a href="{{route('cashflow.vendors.index')}}">{{ __('Vendors') }}</a>
			</small>
		</div>
	</div>
	<div class="box">
		<div class="box-body" style="padding: 13px">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('cashflow.vendors.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Code') }}</label>						
								<input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Name') }}</label>						
								<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Company Name') }}</label>						
								<input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Email') }}</label>						
								<input type="text" class="form-control" name="email" value="{{ old('email') }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Registration No') }}</label>						
								<input type="text" class="form-control" name="registration_no" value="{{ old('registration_no') }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Vendor Cat') }}</label>						
								<input type="text" class="form-control" name="vendor_cat" value="{{ old('vendor_cat') }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Phone') }}</label>						
								<input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Country') }}</label>						
								<select class="form-control auto-select select2" data-selected="{{ old('country') }}" name="country">
									<option value="">{{ __('Select One') }}</option>
									{{ get_country_list(old('country')) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('City') }}</label>						
								<input type="text" class="form-control" name="city" value="{{ old('city') }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Province/city') }}</label>						
								<input type="text" class="form-control" name="state" value="{{ old('state') }}">
							</div>
						</div>

						{{-- <div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Zip') }}</label>						
								<input type="text" class="form-control" name="zip" value="{{ old('zip') }}">
							</div>
						</div> --}}

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Address') }}</label>						
								<textarea class="form-control" name="address">{{ old('address') }}</textarea>
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
								</i>{{ __('Save') }}</button>
								<a href="{{route('cashflow.vendors.index')}}" class="btn btn-lg btn-default m-t"><i class="material-icons">
                                        </i> {{ __('Cancel') }}</a>
							</div>
						</div>
					</div>			
			    </form>
			</div>
		</div>
    </div>
</div>
@endsection


