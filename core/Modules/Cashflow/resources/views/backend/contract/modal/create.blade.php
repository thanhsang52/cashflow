<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{route('contract.store')}}" enctype="multipart/form-data">
{{ csrf_field() }}
	
	<div class="col-12">
		<div class="row">
			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Effect from') }}</label>						
				<input type="text" class="form-control datepicker" name="effect_from" value="{{ old('effect_from') }}" required>
			  </div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				  <label class="control-label">{{ _lang('Effect to') }}</label>						
				  <input type="text" class="form-control datepicker" name="effect_to" value="{{ old('effect_to') }}" required>
				</div>
			  </div>
			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Name') }}</label>						
				<input type="text" class="form-control" name="name" value="{{ old('name') }}">
			  </div>
			</div>
			
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Contract type') }}</label>						
					<select class="form-control select2" name="contract_type">
						<option value="">{{ _lang('Select One') }}</option>
						@foreach($contract_types as $contract_type)
						<option value="{{ $contract_type }}">{{ $contract_type }}</option>
						@endforeach
					</select>
			  	</div>
			</div>

			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Contract number') }}</label>						
				<input type="text" class="form-control" name="number" value="{{ old('number') }}">
			  </div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
				  <label class="control-label">{{ _lang('Contract code') }}</label>						
				  <input type="text" class="form-control" name="code" value="{{ old('code') }}">
				</div>
			</div>

			<div class="col-md-6">
			  <div class="form-group">
				<label class="control-label">{{ _lang('Base value') }}</label>						
				<input type="text" class="form-control float-field" name="base_value" value="{{ old('base_value') }}" required>
			  </div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
				  <label class="control-label">{{ _lang('Term') }}</label>						
				  <select class="form-control select2" name="term_id">
					 <option value="">{{ _lang('Select One') }}</option>
					 {{ create_option("terms","id","name",old('term_id')) }}
				  </select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Included VAT') }}</label>						
					<input type="checkbox" class="" name="included_vat" value="{{ old('included_vat') }}">
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Reference to main contract') }}</label>						
					<select name="reference" id="reference" class="select2 form-control" width="100%">
						<option value="">{{ _lang('None') }}</option>
						@foreach (\App\Models\Contract::all() as $reference)
						<option value="{{ $reference->code }}">{{ $reference->display_name}}</option>
						@endforeach
					</select>
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
					<label class="control-label">{{ _lang('Terms') }}</label>
					<select name="terms[]" id="terms" multiple class="selectpicker form-control">
						@foreach (\App\Models\Term::all() as $term)
						<option value="{{ $term->id }}">{{ $term->code.' '.$term->name }}</option>
						@endforeach
					</select>
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
