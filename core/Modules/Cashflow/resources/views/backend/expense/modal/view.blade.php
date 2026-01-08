<table class="table table-bordered">
	<tr><td>{{ _lang('Trans Date') }}</td><td>{{ $transaction->paid_at }}</td></tr>
	<tr><td>{{ _lang('Account') }}</td><td>{{ $transaction->account->account_title }}</td></tr>
	<tr><td>{{ _lang('Category') }}</td><td>{{ $transaction->expense_type->name }}</td></tr>
	<tr><td>{{ _lang('Amount') }}</td><td>{!! xss_clean(decimalPlace($transaction->amount, currency($transaction->account->currency->name))) !!}</td></tr>
	<tr><td>{{ _lang('Vendor') }}</td><td>{{ $transaction->vendor->name }}</td></tr>
	<tr><td>{{ _lang('Payment Method') }}</td><td>{{ $transaction->payment_method->name }}</td></tr>
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

