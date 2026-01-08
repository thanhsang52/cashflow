@extends('dashboard.layouts.master')
@section('title', __('cashflow::backend.orders'))
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
					<h3>{{ __('View order') }} <span class="label primary text-sm">{{ $order->number }}</span> </h3>
					<small>
						<a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
						<a>{{ __('Website') }}</a> /
						<a href="{{route('cashflow.website.order.index')}}">{{ __('Orders') }}</a>
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
						<span class="text-md"><i class="fa fa-leanpub "></i> {{ __('cashflow::backend.customer') }}</span>
					</a>
				</li>
				<li class="nav-item inline">
					<a class="nav-link {{ $tab_3 }}" data-toggle="tab" data-target="#tab_notes" href="#">
						<span class="text-md"><i class="material-icons">&#xe0b9;</i> {{ __('cashflow::backend.delivery') }}</span>
					</a>
				</li>
				<li class="nav-item inline">
					<a class="nav-link {{ $tab_4 }}" data-toggle="tab" data-target="#tab_sign" href="#">
						<span class="text-md"><i class="material-icons">&#xe0b9;</i> {{ __('cashflow::backend.payment') }}</span>
					</a>
				</li>
				
			</ul>
			<div class="tab-content clear b-t">
				<div class="tab-pane {{ $tab_1 }} tab_summary" id="tab_summary">
					<div class="box-body" style="padding: 13px">
						
						
						
						<table class="table table-bordered">
							<thead class="dker">
								<tr><th width="10px">#</th><th>{{ __('Product name') }}</th><th>{{ __('Price') }}</th><th>{{ __('Quantity') }}</th><th>{{ __('Sub total') }}</th></tr>
							</thead>
							<tbody>
							@foreach($order->orderLines as $key => $detail)
								<tr><td>{{$key+1}}</td><td>{{ $detail->name }}</td><td>{{ $detail->base_price }}</td><td>{{ $detail->quantity }}</td><td>{{ $detail->sub_total }}</td></tr>
							@endforeach
							</tbody>
							<tfoot>
								<tr><td colspan="3"></td><td>{{ __('Subtotal') }}</td><td>{{$order->total}}</td></tr>
								<tr><td colspan="3"></td><td>{{ __('Discount') }}</td><td>{{$order->discount_total}}</td></tr>
								<tr><td colspan="3"></td><td>{{ __('Delivery fee') }}</td><td>{{$order->delivery_total}}</td></tr>
								<tr><td colspan="3"></td><td>{{ __('Total') }}</td><td>{{$order->total}}</td></tr>
							</tfoot>
						</table>
						
					</div>
		
				</div>
				<div class="tab-pane {{ $tab_2 }} tab_content" id="tab_content">
					<div class="box-body" style="padding: 13px">
						<div class="row">
							<div class="col-md-6 col-lg-6 col-xl-6">
								<p><i class="material-icons">&#xE7FB;</i> {{$order->customer->full_name}}</p>
								<p><i class="material-icons">&#xE0BE;</i> {{$order->customer->email}}</p>
								<p><i class="material-icons">&#xE0CD;</i> {{$order->customer->mobile}}</p>
								<p>
									{!! $qrCode !!}<br/>
									<span style="text-transform: uppercase;">{{$order->customer->import_platform}}_{{$order->customer->import_platform_id}}</span>
								</p>
							</div>
							<div class="col-md-6 col-lg-6 col-xl-6">
								<p><i class="material-icons">&#xE88A;</i> {{$order->address->address}}</p>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane {{ $tab_3 }} tab_notes" id="tab_notes">
					<div class="box-body" style="padding: 13px">
						<div id="comment">
							<h5 class="fs-16 mt-2">{{__($order->shipping->code)}}</h5>
							<p>{{$order->shipping->bill_response->ORDER_NUMBER}}</p>
							@php 
							$lastDeliveryDetail = $order->shipping->delivery_details[count($order->shipping->delivery_details)-1];
							@endphp
							<p>{{$lastDeliveryDetail->STATUS_NAME}}	</p>
							<div class="text-center">
								<lord-icon src="https://cdn.lordicon.com/uetqnvvg.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:80px;height:80px"></lord-icon>
								@php $shippingNumber = $order->shipping->bill_response->ORDER_NUMBER @endphp
								
												
								
								
							</div>
							<p><i class="material-icons">&#xE0CD;</i> {{$order->shipping->address->phone}}</p>
							<p><i class="material-icons">&#xE7FB;</i> {{$order->shipping->address->full_name}}</p>
							<p><i class="material-icons">&#xE88A;</i> {{$order->shipping->address->address}}</p>
						</div>	
					</div>
				</div>
				<div class="tab-pane {{ $tab_4 }} tab_sign" id="tab_sign">
					<div class="box-body" style="padding: 13px" id="wrapper">
						
					</div>
				</div>
			</div>
		</div>
</div>
@endsection

@push("after-scripts")

 @endpush