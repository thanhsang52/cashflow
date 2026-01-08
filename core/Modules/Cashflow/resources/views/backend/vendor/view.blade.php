@extends('dashboard.layouts.master')
@section('title', $vendor->name)
@section('content')
<div class="padding">
	<div class="box m-b-0">
		    <div class="box-header dker">
				<h3>{{ __('View Vendor') }} <span class="label primary text-sm">{{ $vendor->code }}</span></h3>
				<small>
                    <a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
                    <a>{{ __('Cashflow') }}</a> /
					<a href="{{route('cashflow.vendors.index')}}">{{ __('Vendors') }}</a>
                </small>
			</div>
	</div>	
	<div class="box">	
			<div class="box-body" style="padding: 13px">
			    <table class="table table-bordered">
					<tr><td>{{ __('Code') }}</td><td>{{ $vendor->code }}</td></tr>
				    <tr><td>{{ __('Name') }}</td><td>{{ $vendor->name }}</td></tr>
					<tr><td>{{ __('Company Name') }}</td><td>{{ $vendor->company_name }}</td></tr>
					<tr><td>{{ __('Email') }}</td><td>{{ $vendor->email }}</td></tr>
					<tr><td>{{ __('Registration No') }}</td><td>{{ $vendor->registration_no }}</td></tr>
					<tr><td>{{ __('Vendor Cat') }}</td><td>{{ $vendor->vendor_cat }}</td></tr>
					<tr><td>{{ __('Phone') }}</td><td>{{ $vendor->phone }}</td></tr>
					<tr><td>{{ __('Country') }}</td><td>{{ $vendor->country }}</td></tr>
					<tr><td>{{ __('City') }}</td><td>{{ $vendor->city }}</td></tr>
					<tr><td>{{ __('State') }}</td><td>{{ $vendor->state }}</td></tr>
					{{-- <tr><td>{{ __('Zip') }}</td><td>{{ $vendor->zip }}</td></tr> --}}
					<tr><td>{{ __('Address') }}</td><td>{{ $vendor->address }}</td></tr>
					<tr><td>{{ __('Note') }}</td><td>{{ $vendor->note }}</td></tr>
			    </table>
			</div>
	</div>
</div>
@endsection


