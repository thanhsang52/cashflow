@extends('dashboard.layouts.master')
@section('title', __('backend.contract'))

@section('content')
<div class="padding">
	<div class="box">
		<div class="">
			<div class="box-header dker">
				<h3>{!! __('cashflow::backend.contract') !!}</h3>
				<small>
					<a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
					<a>{!! __('cashflow::backend.contract') !!}</a>
				</small>
			</div>

			<div class="box b-info">
				<div class="box-body p-a-2">
				<form method="post" class="validate" autocomplete="off" action="{{ route('cashflow.contract.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					
					<div class="row items">
						<div class="col-md-6 item">
							<div class="form-group">
							  	<label class="control-label">{{ __('Start date') }}</label>						
							  
							  	<div class="input-group date" ui-jp="datetimepicker" ui-options="{
											format: 'DD/MM/YYYY',
											icons: {
											time: 'fa fa-clock-o',
											date: 'fa fa-calendar',
											up: 'fa fa-chevron-up',
											down: 'fa fa-chevron-down',
											previous: 'fa fa-chevron-left',
											next: 'fa fa-chevron-right',
											today: 'fa fa-screenshot',
											clear: 'fa fa-trash',
											close: 'fa fa-remove'
											},
										allowInputToggle: true,
										locale:'en'
										}">
									<input type="text" class="form-control datepicker" name="effect_from" value="{{ old('effect_from') }}" required>
									<span class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('End date') }}</label>						
								
								<div class="input-group date" ui-jp="datetimepicker" ui-options="{
											format: 'DD/MM/YYYY',
											icons: {
											time: 'fa fa-clock-o',
											date: 'fa fa-calendar',
											up: 'fa fa-chevron-up',
											down: 'fa fa-chevron-down',
											previous: 'fa fa-chevron-left',
											next: 'fa fa-chevron-right',
											today: 'fa fa-screenshot',
											clear: 'fa fa-trash',
											close: 'fa fa-remove'
											},
										allowInputToggle: true,
										locale:'en'
										}">
									<input type="text" class="form-control datepicker" name="effect_to" value="{{ old('effect_to') }}" required>
									<span class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Code') }}</label>						
								<input type="text" class="form-control" name="code" value="" required>
							</div>
						</div>
						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Name') }}</label>						
								<input type="text" class="form-control" name="name" value="" required>
							</div>
						</div>
						
						

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Vendor') }}</label>						
								<select class="form-control select2" name="vendor_id" required>
									<option value="">{{ __('Select One') }}</option>
									@foreach (\Modules\Cashflow\App\Models\Vendor::all() as $vendor)
									<option value="{{ $vendor->id }}">{{ $vendor->display_name }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Contract type') }}</label>						
								<select class="form-control select2" name="type" required>
								<option value="">{{ __('Select One') }}</option>
								@foreach((array)$contract_types as $key=> $contract_type)
								<option value="{{ $key }}">{{ $contract_type }}</option>
								@endforeach
								</select>
							</div>
						</div>
			
						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Base value') }}</label>						
								<!-- <input type="text" class="form-control" name="base_value" value="{{ old('base_value') }}" required> -->
								<select name="base_value" class="form-control select2" required>
									<option value="">{{ __('Select One') }}</option>
									<option value="COGS">COGS</option>
									<option value="Receipt-Return">Receipt-Return</option>
								</select>
							</div>
						</div>
			
						
						<div class="col-md-6 reference-container item">
							<div class="form-group">
								<label class="control-label">{{ __('Reference to main contract') }}</label>						
								<select name="reference" id="reference" class="select2 form-control" width="100%">
									<option value="">{{ __('None') }}</option>
									@foreach (\Modules\Cashflow\App\Models\Contract::all() as $reference)
									<option value="{{ $reference->code }}">{{ $reference->code .' '.$reference->name}}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Payment term') }}</label>						
								<input type="text" class="form-control" name="payment_term" value="">
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Agreed New Store/SKU Payment Days') }}</label>						
								<input type="text" class="form-control" name="new_store_sku_payment_days" value="">
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Included VAT') }}</label>						
								<input type="checkbox" class="" name="included_vat" value="1">
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Return') }}</label>						
								<input type="checkbox" class="" name="return" value="1">
							</div>
						</div>
						
						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Near Expiry Product') }}</label>						
								<input type="text" class="form-control" name="near_expiry_product" value="">
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Stock slow selling') }}</label>						
								<input type="text" class="form-control" name="stock_slow_selling" value="">
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Discontinued Items') }}</label>						
								<input type="text" class="form-control" name="discontinued_items" value="">
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Customer Return') }}</label>						
								<input type="text" class="form-control" name="customer_return" value="">
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Minimun shelf life') }}</label>						
								<input type="text" class="form-control" name="minimun_shelf_life" value="">
							</div>
						</div>

						<div class="col-md-6 item">
							<div class="form-group">
								<label class="control-label">{{ __('Cost price changes (days)') }}</label>						
								<input type="text" class="form-control" name="cost_price_changes" value="">
							</div>
						</div>
						<div class="buttonToogle col-md-12" style="display: none;"><a href="javascript:;" class="showMore"><span>{{ __('View More') }}</span> <i class="fa fa-angle-down"></i></a></div>
					</div>
					<div class="row">
						
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ __('Attachment') }}</label>						
								<input type="file" class="dropify" name="attachment">
							</div>
						</div>
			
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ __('Content') }}</label>						
								{!! Form::textarea('note',old('note'), array('ui-jp'=>'summernote','placeholder' => '','class' => 'form-control','ui-options'=>'{height: 250}')) !!}
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								
								
								<table class="table table-bordered" id="table-selected-terms">
									<thead>
										<tr>
											<th width="80px">{{ __('Select') }}</th>
											<th width="150px">{{ __('Ref num.') }}</th>
											<th width="200px">{{ __('Term code') }}</th>
											<th>{{ __('Term name') }}</th>
											<th>{{ __('Note') }}</th>
										</tr>
									</thead>
									<tbody>
									@foreach($terms as $i => $term)
									<tr>
										<td><input type="checkbox" name="termIDs[]" value="{{$term->id}}" class="form-control"></td>
										<td><input type="textbox" class="form-control" name="params[ref_num][{{$term->id}}]" value=""/></td>
										<td>{{$term->code}}</td>
										<td>{{$term->name}}</td>
										<td><input type="textbox" class="form-control" name="params[note][{{$term->id}}]" value=""/></td>
									</tr>
									@endforeach

									</tbody>
								</table>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
								&#xe31b;</i>{!! __('backend.update') !!}</button>
								<a href="{{ route('cashflow.contract.index') }}"
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


@section('js-script')
<script src="{{ asset('public/backend/plugins/keditor/plugins/ckeditor-4.11.4/ckeditor.js') }}"></script>
<script>
	  CKEDITOR.editorConfig = function (config) {
            config.language = 'en';
            config.height = 500;
            config.uiColor = '#ffffff';
            config.toolbarCanCollapse = true;
            config.filebrowserImageBrowseUrl = '/file-manager/ckeditor';
           
            config.extraPlugins = ['youtube','toc','codesnippet'];
        };
  CKEDITOR.replace( 'note', {filebrowserImageBrowseUrl: '/file-manager/ckeditor'});
</script>
@endsection