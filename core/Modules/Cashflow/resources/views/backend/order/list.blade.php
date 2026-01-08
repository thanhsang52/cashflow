@extends('dashboard.layouts.master')
@section('title', __('cashflow::backend.orders'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">

@endpush

@section('content')

<div class="padding">
	<div class="box">
	   
		<div class="box-header dker">
		<h3>{!! __('cashflow::backend.invoices') !!}</h3>
			<small>
				<a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
				<a>{!! __('cashflow::backend.web_orders') !!}</a>
			</small>
		</div>
		<div class="box-tool box-tool-lg">
			<ul class="nav" style="display: flex;justify-content: space-around;">
				
				
			</ul>
		</div>
		<div>
			@include("cashflow::backend.order.search")
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
							
							<th>{{ __('Number') }}</th>
							<th>{{ __('Status') }}</th>
							<th>{{ __('Payment status') }}</th>
							<th>{{ __('Payment method') }}</th>
							<th>{{ __('Total') }}</th>
							<th>{{ __('Created by') }}</th>
							<th>{{ __('Created at') }}</th>
							<th class="text-center">{{ __('Options') }}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
			  </table>
			</div>
		</div>
		<footer class="p-x p-b dker">
			<div class="row">
				<div class="col-sm-12 hidden-xs">
				@if(@Auth::user()->permissionsGroup->delete_status)
					<!-- .modal -->
						<div id="m-all" class="modal fade" data-backdrop="true">
							<div class="modal-dialog" id="animate">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
									</div>
									<div class="modal-body text-center p-lg">
										<h6>
											{{ __('backend.confirmationDeleteMsg') }}
										</h6>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn dark-white p-x-md"
												data-dismiss="modal">{{ __('backend.no') }}</button>
										<button type="submit"
												class="btn danger p-x-md">{{ __('backend.yes') }}</button>
									</div>
								</div><!-- /.modal-content -->
							</div>
						</div>
						<!-- / .modal -->
					@endif
					@if(@Auth::user()->permissionsGroup->edit_status)
						<select name="action" id="action"
								class="input-sm form-control w-md inline v-middle c-select"
								required>
							<option value="">{{ __('backend.bulkAction') }}</option>
							@if(@Auth::user()->permissionsGroup->active_status)
								<option value="activate">{{ __('backend.activeSelected') }}</option>
								<option value="block">{{ __('backend.blockSelected') }}</option>
							@endif
							@if(@Auth::user()->permissionsGroup->delete_status)
								<option value="delete">{{ __('backend.deleteSelected') }}</option>
							@endif
						</select>
						<button type="submit" id="submit_all"
								class="btn white">{{ __('backend.apply') }}</button>
						<button id="submit_show_msg" class="btn white" data-toggle="modal"
								style="display: none"
								data-target="#m-all" ui-toggle-class="bounce"
								ui-target="#animate">{{ __('backend.apply') }}
						</button>
					@endif
				</div>
			</div>
		</footer>
	</div>
	<!-- .modal -->
	@if(@Auth::user()->permissionsGroup->delete_status)
		<div id="delete-invoice" class="modal fade" data-backdrop="true">
			<div class="modal-dialog" id="animate">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
					</div>
					<div class="modal-body text-center p-lg">
						<h6>
							{{ __('backend.confirmationDeleteMsg') }}
						</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn dark-white p-x-md"
								data-dismiss="modal">{{ __('backend.no') }}</button>
						<button type="button" id="invoice_delete_btn" row-id=""
								class="btn danger p-x-md">{{ __('backend.yes') }}</button>
					</div>
				</div><!-- /.modal-content -->
			</div>
		</div>
	@endif
</div>
@endsection
@push("after-scripts")
	<script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
	<script type="text/javascript">
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action").change(function () {
            if (this.value == "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });
		let table_name = "#invoice-table";
        $(document).ready(function () {
			var dataTable = $(table_name).DataTable({
                "processing": true,
                "serverSide": true,
                //"searching": true,
                "pageLength": {{ config('smartend.backend_pagination') }},
                "lengthMenu": [[10, 20, 30, 50, 75, 100, 200, -1], [10, 20, 30, 50, 75, 100, 200, "All"]],
                "ajax": {
                    "url": "{{ route('cashflow.website.get_order_table_data') }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {
                        data._token = "{{csrf_token()}}";
                        data.invoice = $('input[name=invoice]').val();
						data.coupon = $('input[name=coupon]').val();
                        data.status = $('select[name=status]').val();
						data.payment_status = $('select[name=payment_status]').val();
						data.payment_method = $('select[name=payment_method]').val();
						data.delivery_status = $('select[name=delivery_status]').val();
						data.from_date = $('input[name=from_date]').val();
						data.to_date = $('input[name=to_date]').val();
                    }

                },
                "dom": '<"dataTables_wrapper"<"col-sm-12 col-md-3"i><"col-sm-12 col-md-2 pull-right"l><"col-sm-12 col-md-7 pull-right"f><"col-sm-12 col-md-12"r><"row"t><"row b-t p-x p-t dker"<"col-sm-12"p>>>',
                "fnDrawCallback": function () {
                    if ($(table_name + '_paginate .paginate_button').length > 3) {
                        $(table_name + '_paginate')[0].style.display = "block";
                    } else {
                        $(table_name + '_paginate')[0].style.display = "none";
                    }


                    $('[data-toggle="tooltip"]').tooltip({html: true});
                    $('[data-toggle-second="tooltip"]').tooltip({html: true});
                },

                "language": {!! json_encode(__("backend.dataTablesTranslation")) !!},
                "columns": [

                        @if(@Auth::user()->permissionsGroup->edit_status)
                        {
                            "data": "check", "class": "dker", "orderable": false
                        },
                        @endif
				
						{ data : "number", name : "number", orderable: true, searchable: true  },
						{ data : "status", name : "status", orderable: true, searchable: true },
						{ data : "payment_status", name : "payment_status", orderable: true, searchable: true  },
						{ data : "payment.method", name : "payment_method", orderable: true, searchable: true  },
						{ data : "total", name : "total", orderable: true, searchable: true, render: $.fn.dataTable.render.number( ',', '.', 0 )  },
						{ data : "customer.full_name", name : "customer", orderable: true, searchable: true  },
						{ data : "created_date", name : "created_date", orderable: false, searchable: true },
                        {
                            "data": "options", "orderable": false
                        }
                ]
                @if(@Auth::user()->permissionsGroup->edit_status)
                , "order": [[1, "desc"]]
                @else
                , "order": [[0, "desc"]]
                @endif

            });
            dataTable.on('page.dt', function () {
                $('html, body').animate({
                    scrollTop: $(".dataTables_wrapper").offset().top
                }, 'slow');
            });
            $.fn.dataTable.ext.errMode = 'none';

            $("#search-btn").on('click', function () {
                dataTable.draw();
            });
            $('#filter_form').submit(function () {
                if ($("#search_submit_stat").val() === "") {
                    dataTable.draw();
                    return false;
                }
            });

            $("#filter_btn").on('click', function () {
                $("#filter_div").slideToggle();
            });
			
		});
        function DeleteInvoice(id) {
            $("#invoice_delete_btn").attr("row-id", id);
            $("#delete-invoice").modal("show");
        }
		$("#invoice_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id != "") {
                $.ajax({
                    type: "DELETE",
                    url: "<?php echo route("cashflow.invoice.destroy",''); ?>/" + row_id,
                    success: function (result) {
                        if (result.stat == 'success') {
                            $('#invoice_delete_btn').html("{!! __('backend.yes') !!}");
                            swal({
                                title: "<span class='text-success'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 50000,
                            });
                            $(table_name).DataTable().ajax.reload();
                        } else {
                            swal({
                                title: "<span class='text-danger'>" + result.msg + "</span>",
                                text: "",
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                        }
                        $('#delete-invoice').modal('hide');
                        $('.modal-backdrop').hide();
                    }
                });
            }
        });
		
    </script>
	<script src="{{ asset('assets/dashboard/js/jquery-ui/jquery-ui.min.js') }}"></script>
@endpush
@section('js-script')
<script src="{{ asset('public/backend/assets/js/ajax-datatables/contract.js') }}"></script>
@endsection
