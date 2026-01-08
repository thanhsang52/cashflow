@extends('dashboard.layouts.master')

@section('content')
<div class="padding">
	<div class="box m-b-0">
		<div class="box-header dker">
			<h3>{{ __('View transaction') }} <span class="label primary text-sm">{{ $transaction->id }}</span></h3>
			<small>
				<a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
				<a>{{ __('Cashflow') }}</a> /
				<a href="{{route('cashflow.income.index')}}">{{ __('Income') }}</a>
			</small>
		</div>
	</div>	
	<div class="box">
		<div class="">

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr><td>{{ __('Trans Date') }}</td><td>{{ $transaction->paid_at }}</td></tr>
					<tr><td>{{ __('Account') }}</td><td>{{ $transaction->account->account_title }}</td></tr>
					<tr><td>{{ __('Category') }}</td><td>{{ isset($transaction->income_type->name) ? $transaction->income_type->name : __('Transfer') }}</td></tr>
					@php $currency = !empty($transaction->account->currency->name)?$transaction->account->currency->name:'VND';	 @endphp
					<tr><td>{{ __('Amount').($transaction->dr_cr=='cr'?' +':' -') }}</td><td>{!! xss_clean(decimalPlace($transaction->amount, $currency )) !!} </td></tr>
					<tr><td>{{ __('Account') }}</td><td>{{ isset($transaction->account->name) ? $transaction->account->name : '' }}</td></tr>
					<tr><td>{{ __('Contract') }}</td><td>{{ (isset($transaction->contract_term->contract->display_name))?$transaction->contract_term->contract->display_name:'' }}</td></tr>
					<tr><td>{{ __('Payment Method') }}</td><td>{{ $transaction->payment_method->name }}</td></tr>
					<tr><td>{{ __('Reference') }}</td><td>{{ $transaction->reference }}</td></tr>
					
					<tr>
						<td>{{ __('Attachment') }}</td>
						<td>
							@if($transaction->attachment != "")
							<a href="{{ asset('public/uploads/transactions/'.$transaction->attachment) }}" target="_blank" class="btn btn-primary btn-xs">{{ __('View Attachment') }}</a>
							@else
								<label class="badge badge-warning">
								<strong>{{ __('No Atachment Availabel !') }}</strong>
								</label>
							@endif
						</td>
					</tr>
					<tr><td>{{ __('Created by') }}</td><td>{{ $transaction->created_by->name }}</td></tr>
					<tr><td>{{ __('Note') }}</td><td>{{ $transaction->note }}</td></tr>
				</table>
			</div>
			@if (Auth::user()->user_type =='admin' || (@Auth::user()->permissionsGroup->cashflow_status && Helper::GeneralWebmasterSettings("cashflow_status")))
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="form-group">
					<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{action('\Modules\Cashflow\App\Http\Controllers\IncomeController@update_status', $id)}}">
						{{csrf_field()}}
						<input name="id" type="hidden" value="{{$id}}">
						<input name="_method" type="hidden" value="POST">
						@if($transaction->status == "pending")
						<input name="status" type="hidden" value="approved">
						<button value="approved" class="btn btn-primary btn-md" type="submit"><i class="ti-check"></i> {{ __('Approve') }}</button>
						<button value="rejected" class="btn btn-danger btn-md" type="submit" onClick="javascript:$('input[name=status]').val('rejected')"><i class="ti-hand-stop"></i> {{ __('Reject') }}</button>
						@endif
						@if($transaction->status == "approved")
						<input name="status" type="hidden" value="completed">
						<button class="btn btn-primary btn-md" type="submit"><i class="ti-upload"></i> {{ __('Completed') }}</button>
						@endif

					</form>
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
