<table class="table table-bordered">
	<tr><td><label>{{ _lang('Code') }}</label>: {{ $contract->code }}</td><td><label>{{ _lang('Name') }}</label>: {{ $contract->name }}</td></tr>
	<tr>
		<td><label>{{ _lang('Type') }}</label>: {{ isset($contract->type) ? $contract->display_contract_type : '' }}</td>
		<td><label>{{ _lang('Included VAT') }}</label>: {{ $contract->included_vat?'Yes':'No' }}</td>
	</tr>
	<tr><td colspan="2"><label>{{ _lang('Vendor') }}</label>: {{ isset($contract->vendor->name) ? $contract->vendor->name : '' }}</td></tr>
	<tr><td><label>{{ _lang('Base value') }}</label>: {!! xss_clean($contract->base_value) !!}</td><td><label>{{ _lang('Reference') }}</label>: {{ $contract->reference }}</td></tr>
	<tr><td><label>{{ _lang('Effective date from') }}</label>: {{ $contract->display_effect_from }}</td><td><label>{{ _lang('Effective date to') }}</label>: {{ $contract->display_effect_to }}</td></tr>
	<tr><td><label>{{ _lang('Payment term') }}</label>: {{ $contract->payment_term }}</td><td><label>{{ _lang('Agreed New Store/SKU Payment Days') }}</label>: {{ $contract->new_store_sku_payment_days }}</td></tr>
	<tr><td><label>{{ _lang('Near expiry product') }}</label>: {{ $contract->near_expiry_product }}</td><td><label>{{ _lang('Stock slow selling') }}</label>: {{ $contract->stock_slow_selling }}</td></tr>
	<tr><td><label>{{ _lang('Discontinued items') }}</label>: {{ $contract->discontinued_items }}</td><td><label>{{ _lang('Customer return') }}</label>: {{ $contract->customer_return }}</td></tr>
	<tr><td><label>{{ _lang('Minimun shelf life') }}</label>: {{ $contract->minimun_shelf_life }}</td><td><label>{{ _lang('Cost price changes (days)') }}</label>: {{ $contract->cost_price_changes }}</td></tr>
	<tr>
		<td colspan="2"><label>{{ _lang('Attachment') }}</label>:
			@if($contract->attachment != "")
			<a href="{{ asset('public/uploads/contracts/'.$contract->attachment) }}" target="_blank" class="btn btn-link btn-xs">{{ $contract->attachment }}</a>
			@else
				<label class="badge badge-warning">
				<strong>{{ _lang('No Atachment Availabel !') }}</strong>
				</label>
			@endif
		</td>
	</tr>
	<tr><td colspan="2"><label>{{ _lang('Note') }}</label>: {{ $contract->note }}</td></tr>
</table>
@if (count($contract->terms))
<table class="table table-bordered">
	<tr><th width="10px">#</th><th width="100px">{{ _lang('Ref num.') }}</th><th width="100px">{{ _lang('Term code') }}</th><th>{{ _lang('Term name') }}</th><th>{{ _lang('Term note') }}</th></tr>
	@foreach($contract->terms as $key => $term)
		<tr><td>{{$key+1}}</td><td>{{ $term->pivot->ref_num }}</td><td>{{ $term->code }}</td><td>{{ $term->name }}</td><td>{{ $term->pivot->note }}</td></tr>
	@endforeach
</table>
@endif
