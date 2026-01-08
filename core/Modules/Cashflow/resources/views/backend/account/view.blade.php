@extends('dashboard.layouts.master')
@section('title', $account->name)
@section('content')
<div class="padding">
	<div class="box m-b-0">
			<div class="box-header dker">
				<h3>{{ __('View Currency') }} <span class="label primary text-sm">{{ $account->name }}</span></h3>
				<small>
					<a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
					<a>{{ __('Cashflow') }}</a> /
					<a href="{{route('cashflow.accounts.index')}}">{{ __('Accounts') }}</a>
				</small>
			</div>
	</div>
	<div class="box">	
		<div class="box-body" style="padding: 13px">
			   <table class="table table-bordered">
					<tr><td>{{ __('Name') }}</td><td>{{ $account->name }}</td></tr>
					<tr><td>{{ __('Account No') }}</td><td>{{ $account->account_no }}</td></tr>
					<tr><td>{{ __('Account Currency') }}</td><td>{{ $account->currency->name }}</td></tr>
					<tr>
						<td>{{ __('Openning Balance') }}</td>
						<td>{!! xss_clean(decimalPlace($account->openning_balance,$account->currency->name)) !!}</td>
					</tr>
					<tr><td>{{ __('Contact Person') }}</td><td>{{ $account->contact_person }}</td></tr>
					<tr><td>{{ __('Contact Email') }}</td><td>{{ $account->contact_email }}</td></tr>
					<tr><td>{{ __('Note') }}</td><td>{{ $account->note }}</td></tr>
				</table>
		</div>
	</div>
</div>
@endsection

