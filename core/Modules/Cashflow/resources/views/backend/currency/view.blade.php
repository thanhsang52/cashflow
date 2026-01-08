@extends('dashboard.layouts.master')
@section('title', $currency->name)
@section('content')
<div class="padding">
	<div class="box m-b-0">
			<div class="box-header dker">
				<h3>{{ __('View Currency') }} <span class="label primary text-sm">{{ $currency->name }}</span></h3>
				<small>
					<a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
					<a>{{ __('Cashflow') }}</a> /
					<a href="{{route('cashflow.currency.index')}}">{{ __('Currencies') }}</a>
				</small>
			</div>
	</div>
	<div class="box">	
		<div class="box-body" style="padding: 13px">
		<table class="table table-bordered">
			<tr><td>{{ __('Name') }}</td><td>{{ $currency->name }}</td></tr>
			<tr><td>{{ __('Is Base Currency') }}</td><td>{{ $currency->base_currency == 1 ? __('Yes') : __('No') }}</td></tr>
			<tr><td>{{ __('Exchange Rate') }}</td><td>{{ $currency->exchange_rate }}</td></tr>
			<tr><td>{{ __('Status') }}</td><td>{{ $currency->status == 1 ? __('Active') : __('InActive')  }}</td></tr>
		</table>
		</div>
	</div>
</div>
@endsection


