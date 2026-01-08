@extends('dashboard.layouts.master')
@section('title', __('backend.contract'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
@endpush

@section('content')

<div class="padding">
	<div class="box">
	   
		<div class="box-header dker">
		<h3>{!! __('cashflow::backend.contract') !!}</h3>
			<small>
				<a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
				<a>{!! __('cashflow::backend.contract') !!}</a>
			</small>
		</div>
		<div class="box-tool box-tool-lg">
			<ul class="nav" style="display: flex;justify-content: space-around;">
				
				@if(@Auth::user()->permissionsGroup->add_status)
					<li class="nav-item inline">
						<a class="btn btn-fw primary m-l" href="{{route('cashflow.contract.create')}}">
							<i class="material-icons">&#xe02e;</i>
							&nbsp; {{ __('cashflow::backend.addNewContract') }}
						</a>
					</li>
				@endif
			</ul>
		</div>
		<div>
			<div class="table-responsive">
				<table id="contract-table" class="table table-bordered">
					<thead class="dker">
						<tr>
							@if(@Auth::user()->permissionsGroup->edit_status)
								<th style="width:20px;">
									<label class="ui-check m-a-0">
										<input id="checkAll" type="checkbox"><i></i>
									</label>
								</th>
							@endif
							<th>{{ __('Contract code') }}</th>
							<th>{{ __('Vendor') }}</th>
							<th>{{ __('Ref to main contract') }}</th>
							<th>{{ __('Name') }}</th>
							<th>{{ __('Effect From') }}</th>
							<th>{{ __('Effect To') }}</th>
							
							
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
		<div id="delete-contract" class="modal fade" data-backdrop="true">
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
						<button type="button" id="contract_delete_btn" row-id=""
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
		let table_name = "#contract-table";
        $(document).ready(function () {
			var dataTable = $(table_name).DataTable({
                "processing": true,
                "serverSide": true,
                //"searching": true,
                "pageLength": {{ config('smartend.backend_pagination') }},
                "lengthMenu": [[10, 20, 30, 50, 75, 100, 200, -1], [10, 20, 30, 50, 75, 100, 200, "All"]],
                "ajax": {
                    "url": "{{ route('cashflow.get_contract_table_data') }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {
                        data._token = "{{csrf_token()}}";
                        //data.find_q = $('#find_q').val();
                        //data.section_id = $('#find_section_id').val();
 
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
						{ data : "code", name : "code", orderable: true, searchable: true  },
						{ data : "vendor.code", name : "vendor.code", orderable: true, searchable: true },
						{ data : "reference", name : "reference", orderable: true, searchable: true  },
						{ data : "name", name : "name", orderable: true, searchable: true  },
						{ data : "effect_from", name : "effect_from", orderable: false, searchable: true },
						{ data : "effect_to", name : "effect_to", orderable: false, searchable: true },
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
        function DeleteContract(id) {
            $("#contract_delete_btn").attr("row-id", id);
            $("#delete-contract").modal("show");
        }
		$("#contract_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id != "") {
                $.ajax({
                    type: "DELETE",
                    url: "<?php echo route("cashflow.contract.destroy",''); ?>/" + row_id,
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
                        $('#delete-contract').modal('hide');
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
