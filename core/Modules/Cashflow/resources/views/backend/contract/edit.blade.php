@extends('dashboard.layouts.master')
@section('title', __('cashflow::backend.contract'))
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/style.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/themify-icons.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/plugins/sweet-alert2/sweetalert2.min.css') }}">
@endpush
<?php
        $tab_1 = "active";
        $tab_2 = "";
        $tab_3 = "";
        $tab_4 = "";
        $tab_5 = "";
        $tab_6 = "";
        $tab_7 = "";
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "seo") {
                $tab_1 = "";
                $tab_2 = "active";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "";
            }
            if (Session::get('activeTab') == "photos") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "active";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "";
            }
            if (Session::get('activeTab') == "comments") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "active";
                $tab_5 = "";
                $tab_6 = "";
                $tab_7 = "";
            }
            if (Session::get('activeTab') == "maps") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "active";
                $tab_6 = "";
                $tab_7 = "";
            }
            if (Session::get('activeTab') == "files") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
                $tab_4 = "";
                $tab_5 = "";
                $tab_6 = "active";
                $tab_7 = "";
            }
           
        }
        ?>
@section('content')
<div class="padding">
	<div class="box">
		<div class="">
			<div class="box-header dker">
				<h3>{!! __('cashflow::backend.contract') !!}</h3>
				<small>
					<a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
					<a href="{{route('cashflow.contract.index')}}">{!! __('cashflow::backend.contract') !!}</a>
				</small>
			</div>

			<div class="box nav-active-border b-info">
				<ul class="nav nav-md">
					<li class="nav-item inline">
						<a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_details" href="#">
							<span class="text-md"><i class="material-icons">
									&#xe31e;</i> {{ __('cashflow::backend.contractTabDetails') }}</span>
						</a>
					</li>
					@if(!empty($Topic))
					<li class="nav-item inline">
                        <a class="nav-link  {{ $tab_6 }}" data-toggle="tab" data-target="#tab_files" href="#">
                    <span class="text-md"><i class="material-icons">
                            &#xe226;</i> {{ __('backend.additionalFiles') }}
							@if(count($Topic->attachFiles)>0)
								<span class="label rounded">{{ count($Topic->attachFiles) }}</span>
							@endif
                    </span>
                        </a>
                    </li>
					@if($WebmasterSection->multi_images_status)
                    <li class="nav-item inline">
							<a class="nav-link  {{ $tab_3 }}" data-toggle="tab" data-target="#tab_photos" href="#">
						<span class="text-md"><i class="material-icons">
								&#xe251;</i>
							{{ __('backend.topicAdditionalPhotos') }}
							@if(count($Topic->photos)>0)
								<span class="label rounded">{{ count($Topic->photos) }}</span>
							@endif
						</span>
							</a>
						</li>
					@endif
					@if($WebmasterSection->comments_status)
						<li class="nav-item inline">
							<a class="nav-link  {{ $tab_4 }}" data-toggle="tab" data-target="#tab_comments" href="#">
						<span class="text-md"><i class="material-icons">
								&#xe0b9;</i> {{ __('backend.comments') }}
							@if(count($Topic->comments)>0)
								<span class="label rounded">{{ count($Topic->comments) }}</span>
							@endif
						</span>
							</a>
						</li>
					@endif
					@endif
					
				</ul>
		
				<div class="tab-content clear b-t">
					<div class="tab-pane  {{ $tab_1 }}" id="tab_details">
						<div class="box-body p-a-2">
							<form method="post" id="contract-edit-from" class="validate" autocomplete="off" action="{{action('\Modules\Cashflow\App\Http\Controllers\ContractController@update', $id)}}" enctype="multipart/form-data" novalidate>
								{{ csrf_field()}}
								<input name="_method" type="hidden" value="PATCH">				
								<input name="id" type="hidden" id="contract_id" value="{{ $contract->id }}">		
								<div class="row items">
									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Start date') }}</label>						
											<input type="text" class="form-control datepicker" name="effect_from" value="{{ $contract->effect_from }}" required>
										</div>
									</div>
						
									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('End date') }}</label>						
											<input type="text" class="form-control datepicker" name="effect_to" value="{{ $contract->effect_to }}" required>
										</div>
									</div>
									
									
									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Code') }}</label>						
											<input type="text" class="form-control" name="code" value="{{ $contract->code }}" required>
										</div>
									</div>
						
									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Name') }}</label>						
											<input type="text" class="form-control" name="name" value="{{ $contract->name }}" required>
										</div>
									</div>
						
									

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Vendor') }}</label>						
											<select class="form-control select2" name="vendor_id" required>
											<option value="">{{ __('Select One') }}</option>
												@foreach (\Modules\Cashflow\App\Models\Vendor::all() as $vendor)
												<option {{ ($vendor->id == $contract->vendor_id)?'selected':'' }} value="{{ $vendor->id }}">{{ $vendor->display_name }}</option>
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
												<option value="{{ $key }}" {{ $contract->type  == $key?'selected':'' }}>{{ $contract_type }}</option>
												@endforeach
											</select>
										</div>
									</div>
									
									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Base value') }}</label>						
											<!-- <input type="text" class="form-control float-field" name="base_value" value="{{ $contract->base_value }}" required> -->
											<select name="base_value" class="form-control select2" required>
												<option value="">{{ __('Select One') }}</option>
												<option {{ $contract->base_value  == 'COGS'?'selected':'' }} value="COGS">COGS</option>
												<option {{ $contract->base_value  == 'Receipt-Return'?'selected':'' }} value="Receipt-Return">Receipt-Return</option>
											</select>
										</div>
									</div>
									
									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Reference to main contract') }}</label>						
											<!-- <input type="text" class="form-control" name="reference" value="{{ $contract->reference }}"> -->
											<select name="reference" id="reference" class="select2 form-control" width="100%">
												<option value="">{{ __('None') }}</option>
												@foreach (\Modules\Cashflow\App\Models\Contract::all() as $reference)
												<option {{ $reference->code== $contract->reference?'selected':'' }} value="{{ $reference->code }}">{{ $reference->code .' '.$reference->name}}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Payment term') }}</label>						
											<input type="text" class="form-control" name="payment_term" value="{{ $contract->payment_term }}">
										</div>
									</div>

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Agreed new store/SKU payment days') }}</label>						
											<input type="text" class="form-control" name="new_store_sku_payment_days" value="{{ $contract->new_store_sku_payment_days }}">
										</div>
									</div>

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Included VAT') }}</label>						
											<input type="checkbox" class="" {{$contract->included_vat?'checked':''}} name="included_vat" value="1">
										</div>
									</div>
						
									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Return') }}</label>						
											<input type="checkbox" class="" name="return" {{$contract->return?'checked':''}} value="1">
										</div>
									</div>
									
									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Near expiry product') }}</label>						
											<input type="text" class="form-control" name="near_expiry_product" value="{{ $contract->near_expiry_product }}">
										</div>
									</div>

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Stock slow selling') }}</label>						
											<input type="text" class="form-control" name="stock_slow_selling" value="{{ $contract->stock_slow_selling }}">
										</div>
									</div>

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Discontinued items') }}</label>						
											<input type="text" class="form-control" name="discontinued_items" value="{{ $contract->discontinued_items }}">
										</div>
									</div>

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Customer return') }}</label>						
											<input type="text" class="form-control" name="customer_return" value="{{ $contract->customer_return }}">
										</div>
									</div>

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Minimun shelf life') }}</label>						
											<input type="text" class="form-control" name="minimun_shelf_life" value="{{ $contract->minimun_shelf_life }}">
										</div>
									</div>

									<div class="col-md-6 item">
										<div class="form-group">
											<label class="control-label">{{ __('Cost price changes (days)') }}</label>						
											<input type="text" class="form-control" name="cost_price_changes" value="{{ $contract->cost_price_changes }}">
										</div>
									</div>
									<div class="buttonToogle col-md-12" style="display: none;"><a href="javascript:;" class="showMore btn-secondary"><span>{{ __('View More') }}</span> <i class="fa fa-angle-down"></i></a></div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ __('Attachment') }}</label>			
											<input type="file" class="form-control dropify" name="attachment" data-default-file="{{ $contract->attachment != "" ? asset('public/uploads/contracts/'.$contract->attachment) : "" }}">
											@if($contract->attachment!="")
												<div class="row">
													<div class="col-sm-12">
														<div id="topic_photo" class="col-sm-4 box p-a-xs">
															<a target="_blank"
															href="{{ asset('uploads/contracts/'.$contract->attachment) }}"><img
																	src="{{ asset('uploads/contracts/'.$contract->attachment) }}"
																	class="img-responsive">
																{{ $contract->attachment }}
															</a>
															<br>
															<a onclick="document.getElementById('topic_photo').style.display='none';document.getElementById('photo_delete').value='1';document.getElementById('undo').style.display='block';"
															class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
														</div>
														<div id="undo" class="col-sm-4 p-a-xs" style="display: none">
															<a onclick="document.getElementById('topic_photo').style.display='block';document.getElementById('photo_delete').value='0';document.getElementById('undo').style.display='none';">
																<i class="material-icons">
																	&#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
														</div>

														{!! Form::hidden('photo_delete','0', array('id'=>'photo_delete')) !!}
													</div>
												</div>
											@endif		
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{!!  __('cashflow::backend.linkTopic') !!} </label>
											<select name="topic_id" class="form-control c-select">
												@foreach($WebmasterSection->topics as $topic)
												<option value="{{$topic->id}}" {{ (@$contract->topic_id == $topic->id)?"selected":"" }}>{!!  $topic->title_en !!}</option>
												@endforeach
												
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">{{ __('Content') }}</label>						
											<!-- <textarea class="form-control" name="note">{{ $contract->note }}</textarea> -->
											{!! Form::textarea('note',$contract->note, array('ui-jp'=>'summernote','placeholder' => '','class' => 'form-control','ui-options'=>'{height: 250}')) !!}
										</div>
									</div>
									
									<div class="col-md-12">
										<div class="form-group">
											
											<button class="btn btn-secondary btn-md primary ajax-modal" data-title="{{ __('Add Term') }}" data-href="{{ route('cashflow.term.list_modal') }}" data-excludes="1,2,3"><i class="ti-plus"></i> {{ __('Add Term') }}</button>
											<input type="hidden" id="selected-terms-ids" value="" />
											<br/>
											<br/>
											<table class="table table-bordered" id="table-selected-terms">
												<thead>
													<tr><th width="10px">#</th><th width="150px">{{ __('Ref num.') }}</th><th width="200px">{{ __('Term code') }}</th><th>{{ __('Term name') }}</th><th>{{ __('Note') }}</th><th>{{ __('Action') }}</th></tr>
												</thead>
												<tbody>
													@foreach ($contract->terms as $i => $term)
													<tr>
														<td width="10px">{{ $i +1 }} <input type="hidden" name="termIDs[]" value="{{ $term->id }}" /></td>
														<td><input type="text" name="params[ref_num][{{ $term->id }}]" class="form-control" value="{{ $term->pivot->ref_num }}" /></td></td>
														<td width="200px">{{ $term->code }}</td>
														<td>{{ $term->name }}</td>
														<td><input type="text" name="params[note][{{ $term->id }}]" class="form-control" value="{{ $term->pivot->note }}" /></td></td>
														<td>
															<a class="btn btn-danger btn-xs btn-remove-2" href="{{ route('cashflow.contract.destroyContractTerm',['term_id'=>$term->pivot->term_id, 'contract_id'=>$term->pivot->contract_id]) }}"><i class="ti-eraser"></i> {{ __('Destroy') }}</a>
															<a class="btn btn-primary btn-xs" href="#" data-toggle="modal" data-title="Update Settings {{ $term->code }}"  data-target="#setting-modal-{{ $term->id }}"><i class="ti-settings"></i> {{ __('Settings') }}</a>
															<x-cashflow::TermSettings :attrs="['term'=>$term,'date_from'=>$contract->effect_from,'date_to'=>$contract->effect_to]"/>
														</td>
													</tr>
													@endforeach

												</tbody>
											</table>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<button type="submit" class="btn btn-lg btn-primary m-t" name="action" value="save_and_close"><i class="material-icons">
											&#xe31b;</i>{!! __('cashflow::backend.update_exit') !!}</button>
											<button type="submit" class="btn btn-lg btn-primary m-t" name="action" value="save">{!! __('backend.update') !!}</button>
											<a href="{{ route('cashflow.contract.index') }}"
											class="btn btn-lg btn-default m-t"><i class="material-icons">
													&#xe5cd;</i> {!! __('backend.cancel') !!}</a>
										</div>
									</div>	
								</div>
							</form>
						</div>
					</div>
					@if(!empty($Topic))
					@include('cashflow::backend.contract.tabs.files')
					@include('cashflow::backend.contract.tabs.photos')
                	@include('cashflow::backend.contract.tabs.comments')
					@endif
					
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@push("after-scripts")
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/app.js') }}"></script>
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/contract-edit.js') }}"></script>
<script src="https://unpkg.com/currency.js@~2.0.0/dist/currency.min.js"></script>

<!-- <script src="{{ asset('public/backend/plugins/keditor/plugins/ckeditor-4.11.4/ckeditor.js') }}"></script>
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
</script> -->
@endpush