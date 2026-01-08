<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{action('IncomeController@update', $id)}}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="col-12">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Date') }}</label>						
					<input type="text" class="form-control datepicker" name="trans_date" value="{{ $transaction->trans_date }}" required>
				</div>
			</div>

			
			{{-- <div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Account') }}</label>						
					<select class="form-control select2 auto-select" data-selected="{{  $transaction->account_id }}" name="account_id" id="account_id" required>
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
					   {{ create_option("transaction_categories","id","name",$transaction->category_id, array('type=' => 'income')) }}
					</select>
				</div>
			</div>


			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Amount') }}</label>						
					<input type="text" class="form-control float-field" name="amount" value="{{ $transaction->amount }}" required>
				</div>
			</div>
			
			{{-- <div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Customer') }}</label>						
					<select class="form-control select2" name="customer_id">
					   <option value="">{{ _lang('Select One') }}</option>
					   {{ create_option("customers","id","name",$transaction->customer_id) }}
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
							<option {{ ($transaction->contract_term_id==$contract_term[0] )?'selected':'' }} value="{{ $contract_term[0] }}">{{ $contract_term[1] }}</option>
						@endforeach
						</optgroup>
					@endforeach
				  </select>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Payment Method') }}</label>						
					<select class="form-control select2" name="payment_method_id" required>
					   <option value="">{{ _lang('Select One') }}</option>
					   {{ create_option("payment_methods", "id", "name", $transaction->payment_method_id) }}
					</select>
				</div>
			</div>


			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Reference') }}</label>						
					<input type="text" class="form-control" name="reference" value="{{ $transaction->reference }}">
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Attachment') }}</label>						
					<input type="file" class="form-control dropify" name="attachment" data-default-file="{{ $transaction->attachment != "" ? asset('public/uploads/transactions/'.$transaction->attachment) : "" }}">
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Note') }}</label>						
					<textarea class="form-control" name="note">{{ $transaction->note }}</textarea>
				</div>
			</div>

			<div class="col-md-12">
			  <div class="form-group">
				<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
			  </div>
			</div>
		</div>
	</div>
</form>