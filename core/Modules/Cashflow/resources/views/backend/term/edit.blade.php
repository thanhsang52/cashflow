@extends('dashboard.layouts.master')
@section('title', __('backend.term').':'.$term->name)
@section('content')
<div class="padding">
	<div class="box">
		<div class="">
			<div class="box-header dker">
			<h3>{!! __('cashflow::backend.term') !!}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! __('cashflow::backend.term') !!}</a>
                </small>
			</div>

			<div class="box b-info">
				<div class="box-body p-a-2">
					<form method="post" class="validate" autocomplete="off" action="{{ action('\Modules\Cashflow\App\Http\Controllers\TermController@update', $id) }}" enctype="multipart/form-data">
						{{ csrf_field()}}
						<input name="_method" type="hidden" value="PATCH">				
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ __('Code') }}</label>						
									<input type="text" class="form-control" name="code" value="{{ $term->code }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ __('Name') }}</label>						
									<input type="text" class="form-control" name="name" value="{{ $term->name }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ __('Credit Account Number') }}</label>						
									<input type="text" class="form-control float-field" name="credit_acc_no" value="{{ $term->credit_acc_no }}">
								</div>
							</div>
							
					
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ __('Credit Account Number') }}</label>						
									<input type="text" class="form-control" name="dedit_acc_no" value="{{ $term->dedit_acc_no }}">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ __('Category') }}</label>						
									<select class="form-control select2 auto-select" data-selected="{{  $term->category_id }}" name="category_id" id="category_id" required>
									<option value="">{{ __('Select One') }}</option>
									@foreach(\Modules\Cashflow\App\Models\TermCategory::all() as $termCategory)
										<option {{($term->category_id==$termCategory->id)?"selected":""}} value="{{ $termCategory->id }}">{{ $termCategory->name }}</option>
									@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ __('Attachment') }}</label>						
									<input type="file" class="form-control trickycode-file" data-value="{{ $term->attachment }}" name="attachment">
								</div>
							</div>

							<div class="col-md-12 clear">
								<div class="form-group">
									<label class="control-label">{{ __('Note') }}</label>						
									<textarea class="form-control" name="note">{{ $term->note }}</textarea>
								</div>
							</div>
				
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
									&#xe31b;</i>{!! __('backend.update') !!}</button>
									<a href="{{ route('cashflow.term.index') }}"
									class="btn btn-lg btn-default m-t"><i class="material-icons">
											&#xe5cd;</i> {!! __('backend.cancel') !!}</a>
								</div>
							</div>	
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


