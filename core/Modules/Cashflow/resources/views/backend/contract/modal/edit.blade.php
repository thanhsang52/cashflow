<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{action('ContractController@update', $id)}}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="col-12">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Effect date from') }}</label>						
					<input type="text" class="form-control datepicker" name="effect_from" value="{{ $contract->effect_from }}" required>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Effect date to') }}</label>						
					<input type="text" class="form-control datepicker" name="effect_to" value="{{ $contract->effect_to }}" required>
				</div>
			</div>
			
			
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Code') }}</label>						
					<input type="text" class="form-control" name="code" value="{{ $contract->code }}" required>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Name') }}</label>						
					<input type="text" class="form-control" name="name" value="{{ $contract->name }}" required>
				</div>
			</div>


			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Base value') }}</label>						
					<input type="text" class="form-control float-field" name="base_value" value="{{ $contract->base_value }}" required>
				</div>
			</div>
			
			
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Vendor') }}</label>						
					<select class="form-control select2" name="vendor_id">
					   <option value="">{{ _lang('Select One') }}</option>
					   {{ create_option("vendors","id","name",$contract->vendor_id) }}
					</select>
				</div>
			</div>
			
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Included VAT') }}</label>						
					<input type="checkbox" class="" name="included_vat" {{ $contract->included_vat?"checked":"" }} value="1">
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Reference to main contract') }}</label>						
					<!-- <input type="text" class="form-control" name="reference" value="{{ $contract->reference }}"> -->
					<select name="reference" id="reference" class="select2 form-control" width="100%">
						<option value="">{{ _lang('None') }}</option>
						@foreach (\App\Models\Contract::all() as $reference)
						<option {{ $reference->code== $contract->reference?'selected':'' }} value="{{ $reference->code }}">{{ $reference->display_name }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Attachment') }}</label>						
					<input type="file" class="form-control dropify" name="attachment" data-default-file="{{ $contract->attachment != "" ? asset('public/uploads/contracts/'.$contract->attachment) : "" }}">
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Note') }}</label>						
					<textarea class="form-control" name="note">{{ $contract->note }}</textarea>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Terms') }}</label>
					<select name="terms[]" id="terms" multiple class="selectpicker form-control">
						@foreach (\App\Models\Term::all() as $term)
						<option {{ in_array($term->id, $selectedTermIDs)?'selected':'' }} value="{{ $term->id }}">{{ $term->code.' '.$term->name }}</option>
						@endforeach
					</select>
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


@section('js-script')
<script src="{{ asset('public/backend/assets/js/bootstrap-select.min.js') }}"></script>
@endsection