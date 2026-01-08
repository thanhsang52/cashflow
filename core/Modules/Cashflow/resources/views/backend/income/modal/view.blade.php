<table class="table table-bordered">
	<tr><td>{{ _lang('Trans Date') }}</td><td>{{ $transaction->paid_at }}</td></tr>
	{{-- <tr><td>{{ _lang('Account') }}</td><td>{{ $transaction->account->name . ' ' .$transaction->account->account_no }}</td></tr> --}}
	<tr><td>{{ _lang('Category') }}</td><td>{{ isset($transaction->income_type->name) ? $transaction->income_type->name : _lang('Transfer') }}</td></tr>
	<tr><td>{{ _lang('Amount') }}</td><td>{!! xss_clean(decimalPlace($transaction->amount, currency($transaction->account->currency->name))) !!}</td></tr>
	{{-- <tr><td>{{ _lang('Payer') }}</td><td>{{ isset($transaction->payer->contact_name) ? $transaction->payer->contact_name : '' }}</td></tr> --}}
	<tr><td>{{ _lang('Term') }}</td><td>{{ isset($transaction->contract_term->display_name) ? $transaction->contract_term->display_name : '' }}</td></tr>
	<tr><td>{{ _lang('Payment Method') }}</td><td>{{ $transaction->payment_method->name }}</td></tr>
	<tr><td>{{ _lang('Status') }}</td><td>{{ isset($transaction->status) ? $transaction->status: '' }}</td></tr>
	<tr><td>{{ _lang('Reference') }}</td><td>{{ $transaction->reference }}</td></tr>
	<tr>
		<td>{{ _lang('Attachment') }}</td>
			<td>
			  @if($transaction->attachment != "")
			   <a href="{{ asset('public/uploads/transactions/'.$transaction->attachment) }}" target="_blank" class="btn btn-primary btn-xs">{{ _lang('View Attachment') }}</a>
			  @else
				  <label class="badge badge-warning">
					<strong>{{ _lang('No Atachment Availabel !') }}</strong>
				  </label>
			  @endif
			</td>
		</tr>
	<tr><td>{{ _lang('Note') }}</td><td>{{ $transaction->note }}</td></tr>
</table>
@php $permissions = permission_list(); @endphp
@if (Auth::user()->user_type =='admin' || in_array('income.update_status',$permissions))
<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{action('IncomeController@update_status', $id)}}">
	{{csrf_field()}}
	<input name="id" type="hidden" value="{{$id}}">
	<input name="_method" type="hidden" value="POST">
	@if($transaction->status == "pending")
	<input name="status" type="hidden" value="approved">
	<button value="approved" class="btn btn-primary btn-xs" type="submit"><i class="ti-check"></i> {{ _lang('Approve') }}</button>
	<button value="rejected" class="btn btn-danger btn-xs" type="submit" onClick="javascript:$('input[name=status]').val('rejected')"><i class="ti-hand-stop"></i> {{ _lang('Reject') }}</button>
	@endif
	@if($transaction->status == "approved")
	<input name="status" type="hidden" value="completed">
	<button class="btn btn-primary btn-xs" type="submit"><i class="ti-upload"></i> {{ _lang('Completed') }}</button>
	@endif
	{{-- @if($transaction->status == "submited")
	<input name="status" type="hidden" value="processed">
	<button class="btn btn-primary btn-xs" type="submit"><i class="ti-book"></i> {{ _lang('Mark as processed') }}</button>
	@endif --}}
</form>
@endif