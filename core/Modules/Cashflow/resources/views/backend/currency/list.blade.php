@extends('dashboard.layouts.master')

@section('content')

<div class="padding">
	<div class="box">
	    <div class="alert alert-warning">
			<strong>{{ __('Do not change base currecny once you made any transactions otherwise your calculation will be wrong !') }}</strong>
		</div>
		
		<div class="alert alert-info">
			<strong>{{ __('Base currency exchange rate will be always 1.00') }}</strong>
		</div>
		
		<div class="">
			<div class="box-header dker">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <h3>{{ __('Cashflow') }}  </h3>
                        <small>
                            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
							<a>Cashflow</a> /
                            <a>Currency</a> 
                        </small>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="row">
                           
                            <div class="col-sm-12">
                                @if(@Auth::user()->permissionsGroup->add_status)
                                    <a class="btn btn-fw primary w-500 pull-right" style="overflow: hidden"
                                       href="{{route('cashflow.currency.create')}}">
                                        <i class="material-icons">&#xe02e;</i>
                                        &nbsp; {{ __('cashflow::backend.currencyNew') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			<div class="b-t">
				<div class="table-responsive">
					<table id="currency_table" class="table table-bordered data-table m-a-0">
						<thead class="dker">
							<tr>
								<th class="dker width20">
									<label class="ui-check m-a-0">
										<input id="checkAll" type="checkbox"><i></i>
									</label>
								</th>
								<th>{{ __('Name') }}</th>
								<th>{{ __('Base Currency') }}</th>
								<th>{{ __('Exchange Rate') }}</th>
								<th>{{ __('Status') }}</th>
								<th class="text-center">{{ __('Action') }}</th>
							</tr>
						</thead>
						<tbody>
                            @php
                                $total =count($currencys);
                                $x = 0;
                            @endphp
							@foreach($currencys as $currency)
                            @php
                                $x++;
                            @endphp
							<tr data-id="row_{{ $currency->id }}">
								<td class="dker"><label class="ui-check m-a-0">
										<input type="checkbox" name="ids[]" value="{{ $currency->id }}"><i
											class="dark-white"></i>
										{!! Form::hidden('row_ids[]',$currency->id, array('class' => 'form-control row_no')) !!}
									</label>
								</td>
								<td class='name'>{{ $currency->name }}</td>
								<td class='base_currency'>{{ $currency->base_currency == 1 ? __('Yes') : __('No') }}</td>
								<td class='exchange_rate'>{{ $currency->exchange_rate }}</td>
								<td class='status'><i class="fa {{ ($currency->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i></td>
								
								<td class="text-center">
									
									<form action="{{ route('cashflow.currency.destroy', $currency['id']) }}" method="post">
										{{ csrf_field() }}
										<input name="_method" type="hidden" value="DELETE">
											<div class="dropdown {{($x  >= $total) ? 'dropup' : ''}}">
												<button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i>
													Options
												</button>
												<div class="dropdown-menu pull-right">
													<a class="dropdown-item" href="{{ route('cashflow.currency.show', $currency['id']) }}" target="_blank"><i class="material-icons"></i> {{ __('Preview') }}</a>
													<a class="dropdown-item" href="{{ route('cashflow.currency.edit', $currency['id']) }}"><i class="material-icons"></i> {{ __('Edit') }}</a>
													<!-- <button class="btn-remove dropdown-item text-danger" type="submit"><i class="material-icons"></i> {{ __('Delete') }}</button> -->
												</div>
											</div>
									</form>
									
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				
			</div>
		</div>
	</div>
</div>

@endsection
@push("after-scripts")
    <script type="text/javascript">
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        

       
    </script>
@endpush