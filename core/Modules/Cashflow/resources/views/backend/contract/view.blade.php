@extends('dashboard.layouts.master')
@section('title', __('cashflow::backend.contract'))
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/style.css') }}">
@endpush
@section('content')
<?php
        $tab_1 = "active";
        $tab_2 = "";
        $tab_3 = "";
		$tab_4 = "";
        
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "content") {
                $tab_1 = "";
                $tab_2 = "content";
                $tab_3 = "";
				$tab_4 = "";
                
            }
            if (Session::get('activeTab') == "notes") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "active";
				$tab_4 = "";
                
            }
            if (Session::get('activeTab') == "sign") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "";
				$tab_4 = "active";
                
            }
           
        }
        ?>
<div class="padding">

		<div class="box m-b-0">
				<div class="box-header dker">
					<h3>{{ __('View Contract') }} <span class="label primary text-sm">{{ $contract->name }}</span> @if($contract->signed)<span class="label info text-sm">{{ __('cashflow::backend.Signed') }}</span>@endif</h3>
					<small>
						<a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
						<a>{{ __('Cashflow') }}</a> /
						<a href="{{route('cashflow.contract.index')}}">{{ __('Contracts') }}</a>
					</small>
				</div>
		</div>	
		<div class="box  nav-active-border">	
			<ul class="nav nav-md">
				<li class="nav-item inline">
					<a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_summary" href="#">
						<span class="text-md"><i class="material-icons">&#xe31e;</i> {{ __('cashflow::backend.contractTabSummary') }}</span>
					</a>
				</li>
				<li class="nav-item inline">
					<a class="nav-link {{ $tab_2 }}" data-toggle="tab" data-target="#tab_content" href="#">
						<span class="text-md"><i class="fa fa-leanpub "></i> {{ __('cashflow::backend.contractTabContent') }}</span>
					</a>
				</li>
				<li class="nav-item inline">
					<a class="nav-link {{ $tab_3 }}" data-toggle="tab" data-target="#tab_notes" href="#">
						<span class="text-md"><i class="material-icons">&#xe0b9;</i> {{ __('cashflow::backend.contractTabNotes') }}</span>
					</a>
				</li>
				<li class="nav-item inline">
					<a class="nav-link {{ $tab_4 }}" data-toggle="tab" data-target="#tab_sign" href="#">
						<span class="text-md"><i class="material-icons">&#xe0b9;</i> {{ __('cashflow::backend.contractTabSign') }}</span>
					</a>
				</li>
				
			</ul>
			<div class="tab-content clear b-t">
				<div class="tab-pane {{ $tab_1 }} tab_summary" id="tab_summary">
					<div class="box-body" style="padding: 13px">
						<table class="table table-bordered">
							<tr><td><label>{{ __('Code') }}</label>: {{ $contract->code }}</td><td><label>{{ __('Name') }}</label>: {{ $contract->name }}</td></tr>
							<tr>
								<td><label>{{ __('Type') }}</label>: {{ isset($contract->type) ? $contract->display_contract_type : '' }}</td>
								<td><label>{{ __('Included VAT') }}</label>: {{ $contract->included_vat?'Yes':'No' }}</td>
							</tr>
							<tr><td colspan="2"><label>{{ __('Vendor') }}</label>: {{ isset($contract->vendor->name) ? $contract->vendor->name : '' }}</td></tr>
							<tr><td><label>{{ __('Base value') }}</label>: {!! xss_clean($contract->base_value) !!}</td><td><label>{{ __('Reference') }}</label>: {{ $contract->reference }}</td></tr>
							<tr><td><label>{{ __('Start date') }}</label>: {{ $contract->display_effect_from }}</td><td><label>{{ __('End date') }}</label>: {{ $contract->display_effect_to }}</td></tr>
							<tr><td><label>{{ __('Payment term') }}</label>: {{ $contract->payment_term }}</td><td><label>{{ __('Agreed New Store/SKU Payment Days') }}</label>: {{ $contract->new_store_sku_payment_days }}</td></tr>
							<tr><td><label>{{ __('Near expiry product') }}</label>: {{ $contract->near_expiry_product }}</td><td><label>{{ __('Stock slow selling') }}</label>: {{ $contract->stock_slow_selling }}</td></tr>
							<tr><td><label>{{ __('Discontinued items') }}</label>: {{ $contract->discontinued_items }}</td><td><label>{{ __('Customer return') }}</label>: {{ $contract->customer_return }}</td></tr>
							<tr><td><label>{{ __('Minimun shelf life') }}</label>: {{ $contract->minimun_shelf_life }}</td><td><label>{{ __('Cost price changes (days)') }}</label>: {{ $contract->cost_price_changes }}</td></tr>
							<!-- <tr><td colspan="2"><label>{{ __('Reference') }}</label>: {{ $contract->reference }}</td></tr> -->
							<tr>
								<td colspan="2"><label>{{ __('Attachment') }}</label>:
									@if($contract->attachment != "")
									<a href="{{ asset('uploads/contracts/'.$contract->attachment) }}" target="_blank" class="btn btn-link btn-xs">{{ $contract->attachment }}</a>
									@else
										<label class="badge badge-warning">
										<strong>{{ __('No Atachment Availabel !') }}</strong>
										</label>
									@endif
								</td>
							</tr>
							
						</table>
						
						@if (count($contract->terms))
						<table class="table table-bordered">
							<tr><th width="10px">#</th><th width="100px">{{ __('Ref num.') }}</th><th width="100px">{{ __('Term code') }}</th><th>{{ __('Term name') }}</th><th>{{ __('Term note') }}</th></tr>
							@foreach($contract->terms as $key => $term)
								<tr><td>{{$key+1}}</td><td>{{ $term->pivot->ref_num }}</td><td>{{ $term->code }}</td><td>{{ $term->name }}</td><td>{{ $term->pivot->note }}</td></tr>
							@endforeach
						</table>
						@endif
					</div>
		
				</div>
				<div class="tab-pane {{ $tab_2 }} tab_content" id="tab_content">
					<div class="box-body" style="padding: 13px">
					{!! $contract->note !!}

						@if($contract->signed)
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<p class="text-right">
										<label class="control-label">{{ __('cashflow::backend.Signer Name') }}:</label>	{{ $contract->acceptance_firstname.' '.$contract->acceptance_lastname }}<br/>
										<label class="control-label">{{ __('cashflow::backend.Signed Date') }}:</label>	{{ date('d M Y',strtotime($contract->acceptance_date)) }}<br/>
										<label class="control-label">{{ __('cashflow::backend.IP Address') }}:</label>	{{ $contract->acceptance_ip }}</p>
									<p class="text-right">
										<label class="control-label" id="signatureLabel">{{ __('cashflow::backend.Signature') }}</label><br/>
										<img style="max-width:250px;max-height:100px" id="signatureImage" src="data:image/png;base64,{{$contract->signature}}" class="img-responsive"/>
									</p>
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>
				<div class="tab-pane {{ $tab_3 }} tab_notes" id="tab_notes">
					<div class="box-body" style="padding: 13px">
						<div id="comment">
							<div class="streamline b-l m-b m-l">
								@foreach($notes as $note)
								<div class="sl-item" data-id="{{$note->id}}">
									<div class="sl-left">
										<img src="{{ URL::to('uploads/users/'.(isset($note->created_by->photo)?$note->created_by->photo:'profile.jpg')) }}" class="img-circle">
									</div>
									<div class="sl-content">
										@php $dtformated = date('d M Y h:i A', strtotime($note->created_at)); @endphp
										<div class="sl-date text-muted">{{ $dtformated }}</div>
										<div class="sl-author">
											<strong>{{$note->has('created_by')?$note->created_by->name:''}}</strong>
										</div>
										<div><p>{{$note->content}}</p></div>
										
									</div>
								</div>
								@endforeach
							</div>
							<div class="row">
								<div class="col-sm-12">
									<h6><i class="fa fa-plus"></i> {{ __('cashflow::backend.addNewNote') }}</h6>
									<form method="post" class="validate" autocomplete="off" action="{{ route('cashflow.note.store') }}" enctype="multipart/form-data">
										{{ csrf_field()}}

										<input name="object_id" type="hidden" value="{{ $contract->id }}"/>
										<input name="type" type="hidden" value="contract"/>
										<input name="route_back" type="hidden" value="{{route('cashflow.contract.show',$contract->id)}}"/>
										<div class="form-group">
											{!! Form::textarea('content','', array('placeholder' => '','class' => 'form-control','ui-options'=>'{height: 50}')) !!}
											<button type="submit" class="btn btn-theme m-t" value="add_comment">{!! __('cashflow::backend.addNote') !!}</button>
										</div>
									</form>
								</div>
							</div>
						</div>	
					</div>
				</div>
				<div class="tab-pane {{ $tab_4 }} tab_sign" id="tab_sign">
					<div class="box-body" style="padding: 13px" id="wrapper">
						@if(!$contract->signed)
						<form action="{{ route('cashflow.contract.sign',$contract->id) }}" id="identityConfirmationForm" class="form-horizontal" method="post" accept-charset="utf-8" novalidate="novalidate">
							{{ csrf_field()}}       
							<input name="route_back" type="hidden" value="{{route('cashflow.contract.show',$contract->id)}}"/>                       
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
								<h4 class="modal-title">{!! __('cashflow::backend.signature_title') !!}</h4>
							</div>
							<div class="modal-body">
									
								<input type="hidden" name="action" value="sign_contract">
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ __('First Name') }}</label>						
											<input type="text" name="acceptance_firstname" id="acceptance_firstname" class="form-control" required="true" value="{{$contract->acceptance_firstname}}">
										</div>
									</div>
								
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ __('Last Name') }}</label>						
											<input type="text" name="acceptance_lastname" id="acceptance_lastname" class="form-control" required="true" value="{{$contract->acceptance_lastname}}">
										</div>
									</div>
								</div>
								
								<!-- <div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ __('Email') }}</label>						
											<input type="text" name="acceptance_email" id="acceptance_email" class="form-control" required="true" value="">
										</div>
									</div>
								</div> -->
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<p class="bold" id="signatureLabel">{{ __('cashflow::backend.Signature') }}</p>
											<div class="signature-pad--body">
												<canvas id="signature" height="230" width="550" style="touch-action: none;"></canvas>
											</div>
											<input type="text" style="width:1px; height:1px; border:0px;" tabindex="-1" name="signature" id="signatureInput">
											<div class="dispay-block">
												<button type="button" class="btn btn-default btn-xs clear" tabindex="-1" data-action="clear">{{__('clear'); }}</button>
												<button type="button" class="btn btn-default btn-xs" tabindex="-1" data-action="undo">{{ __('undo'); }}</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<p class="text-left text-muted e-sign-legal-text">{!! __('cashflow::backend.sign_agreement') !!} </p>
								<!-- <hr> -->
								<button type="button" class="btn btn-default" data-dismiss="modal">{!! __('backend.cancel') !!}</button>
								<button type="submit" data-loading-text="Please wait..." autocomplete="off" data-form="#identityConfirmationForm" class="btn btn-success">{!! __('cashflow::backend.sign') !!}</button>
							</div>
						</form>
						@else
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<p><label class="control-label">{{ __('cashflow::backend.Signer Name') }}:</label>	{{ $contract->acceptance_firstname.' '.$contract->acceptance_lastname }}<br/>
									<label class="control-label">{{ __('cashflow::backend.Signed Date') }}:</label>	{{ date('d M Y',strtotime($contract->acceptance_date)) }}<br/>
									<label class="control-label">{{ __('cashflow::backend.IP Address') }}:</label>	{{ $contract->acceptance_ip }}</p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label" id="signatureLabel">{{ __('cashflow::backend.Signature') }}</label>
									<div class="signature-pad--body">
										<img width="550" height="230" id="signatureImage" src="data:image/png;base64,{{$contract->signature}}" class="img-responsive"/>
									</div>
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
</div>
@endsection

@push("after-scripts")
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/plugins/signature-pad/signature_pad.min.js') }}"></script>
<script>
  $(function(){
   SignaturePad.prototype.toDataURLAndRemoveBlanks = function() {
     var canvas = this._ctx.canvas;
       // First duplicate the canvas to not alter the original
       var croppedCanvas = document.createElement('canvas'),
       croppedCtx = croppedCanvas.getContext('2d');

       croppedCanvas.width = canvas.width;
       croppedCanvas.height = canvas.height;
       croppedCtx.drawImage(canvas, 0, 0);

       // Next do the actual cropping
       var w = croppedCanvas.width,
       h = croppedCanvas.height,
       pix = {
         x: [],
         y: []
       },
       imageData = croppedCtx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height),
       x, y, index;

       for (y = 0; y < h; y++) {
         for (x = 0; x < w; x++) {
           index = (y * w + x) * 4;
           if (imageData.data[index + 3] > 0) {
             pix.x.push(x);
             pix.y.push(y);

           }
         }
       }
       pix.x.sort(function(a, b) {
         return a - b
       });
       pix.y.sort(function(a, b) {
         return a - b
       });
       var n = pix.x.length - 1;

       w = pix.x[n] - pix.x[0];
       h = pix.y[n] - pix.y[0];
       var cut = croppedCtx.getImageData(pix.x[0], pix.y[0], w, h);

       croppedCanvas.width = w;
       croppedCanvas.height = h;
       croppedCtx.putImageData(cut, 0, 0);

       return croppedCanvas.toDataURL();
     };


     function signaturePadChanged() {

       var input = document.getElementById('signatureInput');
       var $signatureLabel = $('#signatureLabel');
       $signatureLabel.removeClass('text-danger');

       if (signaturePad.isEmpty()) {
         $signatureLabel.addClass('text-danger');
         input.value = '';
         return false;
       }

       $('#signatureInput-error').remove();
       var partBase64 = signaturePad.toDataURLAndRemoveBlanks();
       partBase64 = partBase64.split(',')[1];
       input.value = partBase64;
     }

     var canvas = document.getElementById("signature");
     var clearButton = wrapper.querySelector("[data-action=clear]");
     var undoButton = wrapper.querySelector("[data-action=undo]");
     var identityFormSubmit = document.getElementById('identityConfirmationForm');

     var signaturePad = new SignaturePad(canvas, {
      maxWidth: 2,
      onEnd:function(){
        signaturePadChanged();
      }
    });

     clearButton.addEventListener("click", function(event) {
       signaturePad.clear();
       signaturePadChanged();
     });

     undoButton.addEventListener("click", function(event) {
       var data = signaturePad.toData();
       if (data) {
           data.pop(); // remove the last dot or line
           signaturePad.fromData(data);
           signaturePadChanged();
         }
       });

     $('#identityConfirmationForm').submit(function() {
       signaturePadChanged();
     });
   });
 </script>
 @endpush