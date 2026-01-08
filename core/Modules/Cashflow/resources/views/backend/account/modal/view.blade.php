<table class="table table-bordered">
	<tr><td>{{ _lang('Name') }}</td><td>{{ $account->name }}</td></tr>
	<tr><td>{{ _lang('Account No') }}</td><td>{{ $account->account_no }}</td></tr>
	<tr><td>{{ _lang('Account Currency') }}</td><td>{{ $account->currency->name }}</td></tr>
	<tr>
		<td>{{ _lang('Openning Balance') }}</td>
		<td>{!! xss_clean(decimalPlace($account->openning_balance,$account->currency->name)) !!}</td>
	</tr>
	<tr><td>{{ _lang('Contact Person') }}</td><td>{{ $account->contact_person }}</td></tr>
	<tr><td>{{ _lang('Contact Email') }}</td><td>{{ $account->contact_email }}</td></tr>
	<tr><td>{{ _lang('Note') }}</td><td>{{ $account->note }}</td></tr>
</table>

