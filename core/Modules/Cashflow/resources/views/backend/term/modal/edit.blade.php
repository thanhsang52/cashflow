<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('TermController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="col-12">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Term code') }}</label>						
					<input type="text" class="form-control" name="code" value="{{ $term->code }}" required>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Term name') }}</label>						
					<input type="text" class="form-control" name="name" value="{{ $term->name }}" required>
				</div>
			</div>
			
			
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Credit Account Number') }}</label>						
					<input type="text" class="form-control float-field" name="credit_acc_no" value="{{ $term->credit_acc_no }}">
				</div>
			</div>
			
	
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{{ _lang('Credit Account Number') }}</label>						
					<input type="text" class="form-control" name="dedit_acc_no" value="{{ $term->dedit_acc_no }}">
				</div>
			</div>
			
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Category') }}</label>						
					<select class="form-control select2 auto-select" data-selected="{{  $term->category_id }}" name="category_id" id="category_id" required>
					   <option value="">{{ _lang('Select One') }}</option>
					   @foreach(\App\Models\TermCategory::all() as $termCategory)
						 <option {{ ($termCategory->id == $term->category_id)?'selected':'' }} value="{{ $termCategory->id }}">{{ $termCategory->name }}</option>
					   @endforeach
					</select>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Attachment') }}</label>						
					<input type="file" class="form-control dropify" name="attachment" data-default-file="{{ $term->attachment != "" ? asset('public/uploads/terms/'.$term->attachment) : "" }}">
				</div>
			</div>
			
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">{{ _lang('Note') }}</label>						
					<textarea class="form-control" name="note">{{ $term->note }}</textarea>
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