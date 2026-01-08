


{{ csrf_field() }}
	
	<div class="box-body clear">
	
			<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label">{{ __('Term Code') }}</label>						
					<input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
				</div>
			</div>

			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ __('Term name') }}</label>						
				<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
			  </div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ __('Credit account number') }}</label>						
					<input type="text" class="form-control" name="credit_acc_no" value="{{ old('credit_acc_no') }}">
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ __('Dedit account number') }}</label>						
					<input type="text" class="form-control" name="dedit_acc_no" value="{{ old('dedit_acc_no') }}">
				</div>
			</div>
			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ __('Category') }}</label>						
				<select class="form-control select2" name="category_id">
				   <option value="">{{ __('Select One') }}</option>
				   {{ create_option("cashflow_term_categories","id","name",old('category_id')) }}
				</select>
			  </div>
			</div>

			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ __('Attachment') }}</label>						
				<input type="file" class="form-control dropify" name="attachment">
			  </div>
			</div>

			<div class="col-md-12">
			  <div class="form-group">
				<label class="control-label">{{ __('Note') }}</label>						
				<textarea class="form-control" name="note">{{ old('note') }}</textarea>
			  </div>
			</div>
		
			
		
	</div>

		
