<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('AccountController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Name') }}</label>						
		   <input type="text" class="form-control" name="name" value="{{ $account->name }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Account No') }}</label>						
		   <input type="text" class="form-control" name="account_no" value="{{ $account->account_no }}">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Account Currency') }}</label>						
			<select class="form-control auto-select select2" data-selected="{{ $account->currency_id }}" name="currency_id"  required>
				<option value="">{{ _lang('Select One') }}</option>
				{{ create_option('currency','id','name',$account->currency_id, array('status='=>1)) }}
			</select>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Openning Balance') }}</label>						
		   <input type="text" class="form-control float-field" name="openning_balance" value="{{ $account->openning_balance }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Contact Person') }}</label>						
		   <input type="text" class="form-control" name="contact_person" value="{{ $account->contact_person }}">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Contact Email') }}</label>						
		   <input type="text" class="form-control" name="contact_email" value="{{ $account->contact_email }}">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Note') }}</label>						
		   <textarea class="form-control" name="note">{{ $account->note }}</textarea>
		</div>
	</div>

	
	<div class="form-group">
	    <div class="col-md-12">
		    <button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
	    </div>
	</div>
</form>

