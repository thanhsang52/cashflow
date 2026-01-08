<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{route('income.store')}}" enctype="multipart/form-data">
{{ csrf_field() }}
	
	<div class="col-12">
		<div class="row">
			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Date') }}</label>						
				<input type="text" class="form-control datepicker" name="trans_date" value="{{ old('trans_date') }}" required>
			  </div>
			</div>

			{{-- <div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Account') }}</label>						
				<select class="form-control select2" name="account_id" id="account_id" required>
				   <option value="">{{ _lang('Select One') }}</option>
				   @foreach(\App\Models\Account::all() as $account)
						<option value="{{ $account->id }}">{{ $account->name.' - '.$account->currency->name }}</option>
				   @endforeach
				</select>
			  </div>
			</div> --}}

			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Income Type') }}</label>						
				<select class="form-control select2" name="category_id" required>
				   <option value="">{{ _lang('Select One') }}</option>
				   {{ create_option("transaction_categories","id","name",old('category_id'), array('type=' => 'income')) }}
				</select>
			  </div>
			</div>

			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Amount') }}</label>						
				<input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
			  </div>
			</div>

			{{-- <div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Customer') }}</label>						
				<select class="form-control select2" name="customer_id">
				   <option value="">{{ _lang('Select One') }}</option>
				   {{ create_option("customers","id","name",old('customer_id')) }}
				</select>
			  </div>
			</div> --}}
			{{-- <div class="col-md-6">
				<div class="form-group">
				  <label class="control-label">{{ _lang('Term') }}</label>						
				  <select class="form-control select2" name="term_id">
					 <option value="">{{ _lang('Select One') }}</option>
					 {{ create_option("terms","id","name",old('term_id')) }}
				  </select>
				</div>
			</div> --}}
			<div class="col-md-6">
				<div class="form-group">
				  <label class="control-label">{{ _lang('Term') }}</label>						
				  <select class="form-control select2" name="contract_term_id">
					 <option value="">{{ _lang('Select One') }}</option>
					@foreach($contractTermGrouped as $group => $value)
						<optgroup label="{{ $group }}">
						@foreach ($value as $contract_term)
							<option value="{{ $contract_term[0] }}">{{ $contract_term[1] }}</option>
						@endforeach
						</optgroup>
					@endforeach
				  </select>
				  <button class="btn btn-primary btn-xs ajax-modal" data-title="{{ _lang('Select a Term') }}" data-href="{{ route('contract_term.index') }}" ><i class="ti-plus"></i> {{ _lang('Select') }}</button>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Payment Method') }}</label>						
					<select class="form-control select2" name="payment_method_id" required>
					   <option value="">{{ _lang('Select One') }}</option>
					   {{ create_option("payment_methods","id","name",old('payment_method_id')) }}
					</select>
				</div>
			</div>

			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Reference') }}</label>						
				<input type="text" class="form-control" name="reference" value="{{ old('reference') }}">
			  </div>
			</div>

			<div class="col-md-12">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Attachment') }}</label>						
				<input type="file" class="dropify" name="attachment">
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
		</div>
	</div>
</form>