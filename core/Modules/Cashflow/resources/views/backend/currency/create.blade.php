@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-header bg-primary text-white">
				<span class="panel-title">{{ __('Add Currency') }}</span>
			</div>
			<div class="card-body">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('currency.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ __('Name') }}</label>						
							        <select class="form-control auto-select select2" data-selected="{{ old('name') }}" name="name" required>
						                <option value="">{{ __('Select One') }}</option>
										{{ get_currency_list() }}
									</select>
								</div>
						    </div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ __('Base Currency') }}</label>						
							        <select class="form-control auto-select" data-selected="{{ old('base_currency') }}" name="base_currency">
						                <option value="">{{ __('Select One') }}</option>
										<option value="0">{{ __('No') }}</option>
										<option value="1">{{ __('Yes') }}</option>
									</select>
								</div>
						    </div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ __('Exchange Rate') }}</label>						
							        <input type="text" class="form-control" name="exchange_rate" value="{{ old('exchange_rate') }}" required>
						        </div>
						    </div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ __('Status') }}</label>						
							        <select class="form-control auto-select" data-selected="{{ old('status',1) }}" name="status" required>
										<option value="1">{{ __('Active') }}</option>
										<option value="0">{{ __('InActive') }}</option>
									</select>
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


