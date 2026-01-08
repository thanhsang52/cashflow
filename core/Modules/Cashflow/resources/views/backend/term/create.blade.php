@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		<span class="d-none panel-title">{{ _lang('Add Term') }}</span>

			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ route('term.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					
					<div class="row">
						<div class="col-md-6">
						  	<div class="form-group">
								<label class="control-label">{{ _lang('Term Code') }}</label>						
								<input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
							</div>
						</div>


						<div class="col-md-6">
						  <div class="form-group">
							<label class="control-label">{{ _lang('Term name') }}</label>						
							<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
						  </div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Credit account number') }}</label>						
								<input type="text" class="form-control" name="credit_acc_no" value="{{ old('credit_acc_no') }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Dedit account number') }}</label>						
								<input type="text" class="form-control" name="dedit_acc_no" value="{{ old('dedit_acc_no') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label class="control-label">{{ _lang('Category') }}</label>						
							  <select class="form-control select2" name="category_id">
								 <option value="">{{ _lang('Select One') }}</option>
								 {{ create_option("term_categories","id","name",old('category_id')) }}
							  </select>
							</div>
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label class="control-label">{{ _lang('Attachment') }}</label>						
							<input type="file" class="form-control trickycode-file" name="attachment">
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
				</form>
			</div>
		</div>
	</div>
</div>
@endsection


