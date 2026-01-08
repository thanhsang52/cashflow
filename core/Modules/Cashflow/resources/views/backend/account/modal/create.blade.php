<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('accounts.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	
    <div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Name') }}</label>						
			<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Account No') }}</label>						
			<input type="text" class="form-control" name="account_no" value="{{ old('account_no') }}">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Account Currency') }}</label>						
			<select class="form-control auto-select select2" data-selected="{{ old('currency_id') }}" name="currency_id"  required>
				<option value="">{{ _lang('Select One') }}</option>
				{{ create_option('currency','id','name',old('currency_id'), array('status='=>1)) }}
			</select>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Openning Balance') }}</label>						
			<input type="text" class="form-control float-field" name="openning_balance" value="{{ old('openning_balance') }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Contact Person') }}</label>						
			<input type="text" class="form-control" name="contact_person" value="{{ old('contact_person') }}">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Contact Email') }}</label>						
			<input type="text" class="form-control" name="contact_email" value="{{ old('contact_email') }}">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Note') }}</label>						
			<textarea class="form-control" name="note">{{ old('note') }}</textarea>
		</div>
	</div>

	
	<div class="col-md-12">
	    <div class="form-group">
		    <button type="submit" class="btn btn-primary">{{ _lang('Save') }}</button>
	    </div>
	</div>
</form>
