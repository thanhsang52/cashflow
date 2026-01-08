@extends('dashboard.layouts.master')
@section('title', __('cashflow::backend.accounts'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
@endpush

@section('content')

<div class="padding">
	<div class="box">
		<div>
			<div class="box-header dker">
			<h3>{!! __('cashflow::backend.accounts') !!}</h3>
				<small>
					<a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
					<a>{!! __('cashflow::backend.accounts') !!}</a>
				</small>
			</div>
			<div class="box-tool box-tool-lg">
				<ul class="nav" style="display: flex;justify-content: space-around;">
					
					@if(@Auth::user()->permissionsGroup->add_status)
						<li class="nav-item inline">
							<a class="btn btn-fw primary m-l" href="{{route('cashflow.accounts.create')}}">
								<i class="material-icons">&#xe02e;</i>
								&nbsp; {{ __('cashflow::backend.addNewAccount') }}
							</a>
						</li>
					@endif
				</ul>
			</div>
			<div class="table-responsive">
				<table id="accounts_table" class="table table-bordered data-table">
					<thead>
					    <tr>
							@if(@Auth::user()->permissionsGroup->edit_status)
								<th style="width:20px;">
									<label class="ui-check m-a-0">
										<input id="checkAll" type="checkbox"><i></i>
									</label>
								</th>
							@endif
						    <th>{{ __('Name') }}</th>
							<th>{{ __('Account No') }}</th>
							<th>{{ __('Currency') }}</th>
							<th>{{ __('Openning Balance') }}</th>
							
							<th class="text-center">{{ __('backend.options') }}</th>
					    </tr>
					</thead>
					<tbody>
					   
					</tbody>
				</table>
			</div>
		</div>
	</div>
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
		let table_name = "#accounts_table";
        $(document).ready(function () {
			var dataTable = $(table_name).DataTable({
                "processing": true,
                "serverSide": true,
                //"searching": true,
                "pageLength": {{ config('smartend.backend_pagination') }},
                "lengthMenu": [[10, 20, 30, 50, 75, 100, 200, -1], [10, 20, 30, 50, 75, 100, 200, "All"]],
                "ajax": {
                    "url": "{{ route('cashflow.get_account_table_data') }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {
                        data._token = "{{csrf_token()}}";
                        data.find_q = $('#find_q').val();   
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
                "language": {!! json_encode(__("backend.dataTablesTranslation")) !!}
                ,
                "columns": [

                        @if(@Auth::user()->permissionsGroup->edit_status)
                        {
                            "data": "check", "class": "dker", "orderable": false
                        },
                        @endif
						{ data : "name", name : "name", orderable: true, searchable: true  },
						{ data : "account_no", name : "account_no", orderable: true, searchable: true },
						{ data : "currency.name", name : "currency", orderable: true, searchable: true  },
						{ data : "openning_balance", name : "openning_balance", orderable: true, searchable: true  },
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
        function DeleteAccount(id) {
            $("#account_delete_btn").attr("row-id", id);
            $("#delete-account").modal("show");
        }
		$("#account_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id != "") {
                $.ajax({
                    type: "DELETE",
                    url: "<?php echo route("cashflow.accounts.destroy",''); ?>/" + row_id,
                    success: function (result) {
                        if (result.stat == 'success') {
                            $('#contract_delete_btn').html("{!! __('backend.yes') !!}");
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
                        $('#delete-account').modal('hide');
                        $('.modal-backdrop').hide();
                    }
                });
            }
        });
    </script>
	<script src="{{ asset('assets/dashboard/js/jquery-ui/jquery-ui.min.js') }}"></script>
@endpush