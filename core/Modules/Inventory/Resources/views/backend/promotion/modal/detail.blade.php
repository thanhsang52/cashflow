
<p><label>{{__('Promotion type')}}</label>: {{ $promotion->promotion_type_name }}</p>
@if ($promotion->hasItem())
@php 
$field_discount = ($promotion->type=='FP')?__('New item price'):'Percent value off';
@endphp
<h3>{{__('Promotional Items')}}</h3>
<table class="table table-bordered">
	<tr>
		<th width="10px">#</th>
		<th width="100px">{{ __('Code') }}</th>
		<th>{{ __('Item name') }}</th> 
		@if($promotion->type=='GD') 
		<th>{{ __('Qualifying quantity') }}</th>
		<th>{{ $promotion->discount_type_name }}</th> 
		@endif
		@if($promotion->type=='LD') 
		<th>{{ __('Qualifying quantity') }}</th>
		<th>{{ __('Number of items to discount') }}</th>
		<th>{{ $promotion->discount_type_name }}</th> 
		@endif
	</tr>
	@foreach($promotion->items as $key => $item)
	<tr>
		<td>{{$key+1}}</td>
		<td>{{ $item->item_code }}</td>
		<td>{{ $item->description }}</td> 
		@if($promotion->type=='GD') 
		<td>{{ (int)$promotion->{'bin_count'.$item->pivot->bin} }}</td> 
		<td>{{$promotion->discounted_bin==$item->pivot->bin?$promotion->value:$item->product_price->price}}</td> 
		@endif
		@if($promotion->type=='LD') 
		<td>{{ (int)$promotion->{'bin_count'.$item->pivot->bin} }}</td> 
		<td>{{$promotion->discounted_bin}}</td> 
		<td>{{$promotion->value}}</td> 
		@endif
	</tr>
	@endforeach
</table>
@endif
@if ($promotion->hasLevel())
<h3>{{__('Promotional Level')}}</h3>
<table class="table table-bordered">
	<tr><th width="10px">#</th><th width="100px">{{ __('Qualifying quantity') }}</th><th>{{ $field_discount }}</th></tr>
	@foreach($promotion->levels as $key => $level)
		<tr><td>{{$key+1}}</td><td>{{ $level->qualify_level }}</td><td>{{ $level->value }}</td></tr>
	@endforeach
</table>
@endif