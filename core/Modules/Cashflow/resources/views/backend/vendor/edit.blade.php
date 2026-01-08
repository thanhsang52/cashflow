@extends('dashboard.layouts.master')

@section('content')
<div class="padding">
	<div class="">
		<div class="">
			<div class="box m-b-0">
				<div class="box-header dker">
					<h3><i class="material-icons"></i> Edit Vendor</h3>
						<small>
							<a href="https://cashflow.local/">Home</a> /
							<a>Cashflow</a> /
							<a>Vendor</a>
						</small>
				</div>
			</div>
			<div class="box b-info">
				<div class="box-body p-a-2">
				<form method="post" class="validate" autocomplete="off" action="{{ action('\Modules\Cashflow\App\Http\Controllers\VendorController@update', $id) }}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">				
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Code') }}</label>						
								<input type="text" class="form-control" name="code" value="{{ $vendor->code }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Name') }}</label>						
								<input type="text" class="form-control" name="name" value="{{ $vendor->name }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Company Name') }}</label>						
								<input type="text" class="form-control" name="company_name" value="{{ $vendor->company_name }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Email') }}</label>						
								<input type="text" class="form-control" name="email" value="{{ $vendor->email }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Registration No') }}</label>						
								<input type="text" class="form-control" name="registration_no" value="{{ $vendor->registration_no }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Vendor Cat') }}</label>						
								<input type="text" class="form-control" name="vendor_cat" value="{{ $vendor->vendor_cat }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Phone') }}</label>						
								<input type="text" class="form-control" name="phone" value="{{ $vendor->phone }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Country') }}</label>						
								<select class="form-control auto-select select2" data-selected="{{ $vendor->country }}" name="country">
									<option value="">{{ __('Select One') }}</option>
									{{ get_country_list($vendor->country) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('City') }}</label>						
								<input type="text" class="form-control" name="city" value="{{ $vendor->city }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Province/city') }}</label>						
								<input type="text" class="form-control" name="state" value="{{ $vendor->state }}">
							</div>
						</div>

						{{-- <div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Zip') }}</label>						
								<input type="text" class="form-control" name="zip" value="{{ $vendor->zip }}">
							</div>
						</div> --}}

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Address') }}</label>						
								<textarea class="form-control" name="address">{{ $vendor->address }}</textarea>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ __('Note') }}</label>						
								<textarea class="form-control" name="note">{{ $vendor->note }}</textarea>
							</div>
						</div>

							
						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
								&#xe31b;</i>{!! __('backend.update') !!}</button>
								<a href="{{ route('cashflow.vendors.index') }}"
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


