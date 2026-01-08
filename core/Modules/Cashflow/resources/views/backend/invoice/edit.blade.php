@extends('dashboard.layouts.master')
@section('title', __('cashflow::backend.invoice'))
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/style.css') }}">
@endpush
@section('content')
<div class="padding">
	<div class="box">
		<div class="">
			<div class="box-header dker">
				<h3>{!! __('cashflow::backend.invoice') !!}</h3>
				<small>
					<a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
					<a href="{{route('cashflow.invoice.index')}}">{!! __('cashflow::backend.invoice') !!}</a>
				</small>
			</div>
            <div class="box b-info">
                <div class="box-body p-a-2">
                <form method="post" class="validate" autocomplete="off" action="{{route('cashflow.invoice.update', $id)}}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">
                    <input name="id" type="hidden" id="invoice_id" value="{{ $invoice->id }}">				
					<fieldset style="border: 1px solid rgba(120, 130, 140, 0.13); padding:10px;margin-bottom:10px">
                    <legend style="width:auto">{{ __('cashflow::backend.customer_info') }}</legend>
					<div class="row"> 
						<div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('Invoice no') }}</label>						
                                <input type="text" readonly class="form-control" name="invoice_no" value="{{ $invoice->invoice_no }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-6 m-b-5p">
                            <div class="form-group m-b-0">
                                <label class="control-label">{{ __('cashflow::backend.invoice_date') }}</label>
                                <div class="input-group date" ui-jp="datetimepicker" ui-options="{
                                            format: 'DD MMM YYYY',
                                            icons: {
                                            time: 'fa fa-clock-o',
                                            date: 'fa fa-calendar',
                                            up: 'fa fa-chevron-up',
                                            down: 'fa fa-chevron-down',
                                            previous: 'fa fa-chevron-left',
                                            next: 'fa fa-chevron-right',
                                            today: 'fa fa-screenshot',
                                            clear: 'fa fa-trash',
                                            close: 'fa fa-remove'
                                            },
                                        allowInputToggle: true,
                                        locale:'en'
                                        }">
                                    <input placeholder="{{__('cashflow::backend.invoice_date')}}" class="form-control" name="invoice_date" type="text" value="{{ $invoice->invoice_date }}">
                                    <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.serialNo') }}</label>						
                                <input type="text" readonly class="form-control" name="seri_no" value="{{ $invoice->seri_no }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
						<div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.customerName') }}</label>						
                                <input type="text" class="form-control" name="buyer_name" value="{!! convertToLatin($invoice->buyerName) !!}">
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.buyerLegalName') }}</label>						
                                <input type="text" class="form-control" name="buyerLegalName" value="{!! convertToLatin($invoice->buyerLegalName) !!}">
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.buyerAddressLine') }}</label>						
                                <input type="text" class="form-control" name="buyerAddressLine" value="{{ convertToLatin($invoice->buyerAddressLine) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.buyerTaxCode') }}</label>						
                                <input type="text" class="form-control" name="buyerTaxCode" value="{{ $invoice->buyerTaxCode }}">
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.buyerEmail') }}</label>						
                                <input type="text" class="form-control" name="buyerEmail" value="{{ $invoice->buyerEmail }}">
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.note') }}</label>						
                                <input type="text" class="form-control" name="note" value="{!! $invoice->note !!}">
                            </div>
                        </div>
                    </div>
                    </fieldset>
                    <fieldset style="border: 1px solid rgba(120, 130, 140, 0.13); padding:10px;margin-bottom:10px">
                    <legend style="width:auto">{{ __('cashflow::backend.branch_info') }}</legend>
				
						
                    <div class="row"> 
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.branchNo') }}</label>						
                                <input type="number" class="form-control" name="branch_no" value="{{ convertToLatin($invoice->branch->branch_no) }}" required>
                            </div>
                        </div>
						<div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.branchName') }}</label>						
                                <input type="text" readonly class="form-control" name="branch_name" value="{{ convertToLatin($invoice->branch->branch_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.branch_taxcode') }}</label>						
                                <input type="text" readonly class="form-control" name="branch_taxcode" value="{{ convertToLatin($invoice->branch->taxcode) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.branchAddress') }}</label>						
                                <input type="text" readonly class="form-control" name="branch_address" value="{{ convertToLatin($invoice->branch->address) }}" required>
                            </div>
                        </div>
                       
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.branch_location') }}</label>						
                                <input type="text" readonly class="form-control" name="branch_location" value="{{ convertToLatin($invoice->branch->location) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
                                <label class="control-label">{{ __('cashflow::backend.branch_serino') }}</label>						
                                <input type="text" readonly class="form-control" name="branch_serino" value="{{ $invoice->branch->seri_no }}" required>
                            </div>
                        </div>
                    </div>
                    </fieldset>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary m-t" name="action" value="save_and_close"><i class="material-icons">
                            &#xe31b;</i>{!! __('cashflow::backend.update_exit') !!}</button>
                            <button type="submit" class="btn btn-lg btn-primary m-t" name="action" value="save">{!! __('backend.update') !!}</button>
                            <a href="{{ route('cashflow.contract.index') }}"
                            class="btn btn-lg btn-default m-t"><i class="material-icons">
                                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                        </div>
                    </div>	
                </form>
                    <div class="table-responsive">
                        <table id="invoice-table" class="table table-bordered">
                            <thead class="dker">
                                <tr>
                                    @if(@Auth::user()->permissionsGroup->edit_status)
                                        <th style="width:20px;">
                                            <label class="ui-check m-a-0">
                                                <input id="checkAll" type="checkbox"><i></i>
                                            </label>
                                        </th>
                                    @endif
                                    <th>{{ __('cashflow::backend.sku') }}</th>
                                    <th>{{ __('cashflow::backend.productName') }}</th>
                                    <th>{{ __('cashflow::backend.unit') }}</th>
                                    <th>{{ __('cashflow::backend.quantity') }}</th>
                                    <th>{{ __('cashflow::backend.unitprice') }}</th>
                                    <th>{{ __('cashflow::backend.subtotal') }}</th>
                                    <th>{{ __('cashflow::backend.tax') }}</th>
                                    <th>{{ __('cashflow::backend.subtotalincludetax') }}</th>
                                    <th>{{ __('cashflow::backend.note') }}</th>
                                    <th class="text-center">{{ __('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total =count($invoice->invoiceDetails);
                                    $x = 0;
                                @endphp
                                @foreach($invoice->invoiceDetails as $key => $invoiceDetail)
                                    <tr>
                                        @if(@Auth::user()->permissionsGroup->edit_status)
                                            <td>
                                                <label class="ui-check m-a-0">
                                                    <input type="checkbox" name="check[]" value="{{ $invoiceDetail->id }}"><i></i>
                                                </label>
                                            </td>
                                        @endif
                                        @php
                                            $x++;
                                        @endphp
                                        <td>{{ $invoiceDetail->itemCode }}</td>
                                        <td>{{ $invoiceDetail->itemName }}</td>
                                        <td>{{ $invoiceDetail->unitName }}</td>
                                        <td>{{ $invoiceDetail->quantity }}</td>
                                        <td>{{ decimalPlace($invoiceDetail->unitPrice) }}</td>
                                        <td>{{ decimalPlace($invoiceDetail->amount_with_no_tax) }}</td>
                                        <td>{{ $invoiceDetail->taxPercentage }}%</td>
                                        <td>{{ decimalPlace($invoiceDetail->amount_with_no_tax +  $invoiceDetail->taxPercentage*$invoiceDetail->amount_with_no_tax)}}</td>
                                        <td>{{ $invoiceDetail->note }}</td>
                                        <td class="text-center">
                                            <form action="{{route('cashflow.invoice-detail.destroy',$invoiceDetail->id_invoice_lines)}}" method="post">
                                                {{ csrf_field() }}
                                                <input name="_method" type="hidden" value="DELETE">
                                                <div class="dropdown {{($x  >= $total-1) ? 'dropup' : ''}}">
                                                    <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i>
                                                        Options
                                                    </button>
                                                    <div class="dropdown-menu pull-right">
                                                        
                                                        <a class="dropdown-item" href="{{route('cashflow.invoice-detail.edit',$invoiceDetail->id_invoice_lines)}}"><i class="material-icons"></i> {{ __('Edit') }}</a>
                                                        <button class="btn-remove dropdown-item text-danger" type="submit"><i class="material-icons"></i> {{ __('Delete') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("after-scripts")
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/app.js') }}"></script>
@endpush