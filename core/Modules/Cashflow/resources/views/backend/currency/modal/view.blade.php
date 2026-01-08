<table class="table table-bordered">
	<tr><td>{{ __('Name') }}</td><td>{{ $currency->name }}</td></tr>
	<tr><td>{{ __('Is Base Currency') }}</td><td>{{ $currency->base_currency == 1 ? __('Yes') : __('No') }}</td></tr>
	<tr><td>{{ __('Exchange Rate') }}</td><td>{{ $currency->exchange_rate }}</td></tr>
	<tr><td>{{ __('Status') }}</td><td>{{ $currency->status == 1 ? __('Active') : __('InActive')  }}</td></tr>
</table>

