@extends('dashboard.layouts.master')
@section('title', __('backend.income'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/plugins/select2/select2.min.css') }}">
@endpush
@section('content')

<div class="padding">
	<div class="box">
		<div class="box-header dker">
		<h3>{!! __('cashflow::backend.income') !!}</h3>
			<small>
				<a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a>{!! __('cashflow::backend.cashflow') !!}</a>
			</small>
		</div>
		<div class="box-tool box-tool-lg">
			<ul class="nav">
				
				@if(@Auth::user()->permissionsGroup->add_status)
					<li class="nav-item inline">
						<a class="btn btn-fw primary m-l" href="{{route('cashflow.income.create')}}" onclick="CreateIncome()">
							<i class="material-icons">&#xe02e;</i>
							&nbsp; {{ __('cashflow::backend.addNewIncome') }}
						</a>
					</li>
				@endif
					<li class="nav-item inline">
                        <button type="button" class="btn info" id="filter_btn" title="" data-toggle="tooltip" data-original-title="Search">
                            <i class="fa fa-search"></i>
                        </button>
                    </li>
			</ul>
		</div>
		<div class="dker b-b displayNone" id="filter_div" style="display: block;">
			<div class="p-a">
				<fieldset style="border: 1px solid rgba(120, 130, 140, 0.13); padding:5px">
					<legend style="width: auto">{{ __('Filter by') }}</legend>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">{{ __('Contracts') }}</label>	
								<select class="form-control select2" id="filter-contract-id" name="contract_id">
									<option value="">{{ __('Select All') }}</option>
									<option data-id="0" value="0">{{ __('Non Contract') }}</option>
									@foreach(\Modules\Cashflow\App\Models\Contract::all() as $contract)
									<option data-id="{{$contract->id}}" value="{{ $contract->code }}">{{ $contract->display_name }}</option>
									@endforeach
					
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">{{ __('Terms') }}</label>						
								<select class="form-control select2" id="filter-contract-term-id" name="contract_term_id">
									
								<option value="">{{ __('Select all') }}</option>
									
								@foreach($contractTermGrouped as $group => $value)
									<optgroup label="{{ $group }}">
									@foreach ($value as $contract_term)
										<option value="{{ $contract_term[0] }}">{{ $contract_term[1] }}</option>
									@endforeach
									</optgroup>
								@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">{{ __('Status') }}</label>
								<select class="form-control select2" id="filter-status" name="status">
									<option value="">{{ __('Select All') }}</option>
									<option value="pending">{{ __('Pending') }}</option>
									<option value="approved">{{ __('Approved') }}</option>
									<option value="submited">{{ __('Submited') }}</option>
									<option value="processed">{{ __('Processed') }}</option>
									<option value="rejected">{{ __('Rejected') }}</option>
								</select>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
		<div>
			<div class="table-responsive">
				<table id="income-table" class="table table-bordered">
					<thead>
						<tr>
							@if(@Auth::user()->permissionsGroup->edit_status)
								<th style="width:20px;">
									<label class="ui-check m-a-0">
										<input id="checkAll" type="checkbox"><i></i>
									</label>
								</th>
							@endif
							<th>{{ __('Date') }}</th>
							<th>{{ __('Contract Term') }}</th>
							<th>{{ __('Status') }}</th>
							<th class="text-right">{{ __('Amount') }}</th>
							<th>{{ __('Created by') }}</th>
							<th class="action-col">{{ __('Action') }}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
			  </table>
			</div>
            <!-- .modal -->
            @if(@Auth::user()->permissionsGroup->delete_status)
                <div id="delete-income" class="modal fade" data-backdrop="true">
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
                                <button type="button" id="income_delete_btn" row-id=""
                                        class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div>
                </div>
            @endif
		</div>
	</div>
</div>

@endsection

@push("after-scripts")
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/app.js') }}"></script>
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/optgroup-filter.js') }}"></script>
<!-- <script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/datatable/dataTables.js') }}"></script> -->
<!-- <script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/ajax-datatables/vendor.js') }}"></script> -->
    <script type="text/javascript">
        $(".select2").select2();
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
		let table_name = "#income-table";
        $(document).ready(function () {
			var dataTable = $(table_name).DataTable({
                "processing": true,
                "serverSide": true,
                //"searching": true,
                "pageLength": {{ config('smartend.backend_pagination') }},
                "lengthMenu": [[1,10, 20, 30, 50, 75, 100, 200, -1], [1,10, 20, 30, 50, 75, 100, 200, "All"]],
                "ajax": {
                    "url": "{{ route('cashflow.get_income_table_data') }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {
                        data._token = "{{csrf_token()}}";
                        data.find_q = $('#find_q').val();
                        data.find_date = $('#find_date').val();
                        data.created_by = $('#find_created_by').val();
                        data.status = $('#filter-status').val();
                        data.contract_id = $('#filter-contract-id').find(':selected').data('id');
                        data.contract_term_id = $('#filter-contract-term-id').val();
                        
                    }

                },
                "dom": '<"dataTables_wrapper"<"col-sm-12 col-md-3 pull-left"i><"col-sm-12 col-md-2 pull-right"l><"col-sm-12 col-md-7 pull-right"f><"col-sm-12 col-md-12"r><"row"t><"row b-t p-x p-t dker"<"col-sm-12"p>>>',
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
						{ data : "trans_date", name : "trans_date", orderable: false, searchable: false },
						{ data : "contract_term.display_name", name : "contract_term", orderable: false, searchable: false  },
						{ data : "status", name : "status", orderable: true, searchable: true },
						{ data : "amount", name : "amount", orderable: true, searchable: true  },
						{ data : "created_by.name", name : "created_by", orderable: true, searchable: true  },
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
            $('#filter-contract-id').change(function(){
                dataTable.draw();
            });
            $('#filter-contract-term-id').change(function(){
                dataTable.draw();
            });
            $('#filter-status').change(function(){
                dataTable.draw();
            });
            $('#filter-contract-term-id').filterGroups({groupSelector: '#filter-contract-id', });
            //$('.control-label-row-count').text(dataTable.length);
		});
        // function CreateTerm() {
        //     $("#create-term").modal("show");
        // }
        

        function DeleteIncome(id) {
            $("#income_delete_btn").attr("row-id", id);
            $("#delete-income").modal("show");
        }

        // function UpdateTerm(id) {
        //     $("#update-term").modal("show");
        //     let btn = $('#update-term-form-submit');
        //     btn.html("<img src=\"{{ URL::to('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.save') !!}");
        //     btn.prop('disabled', true);
        //     $.get("{{ route("cashflow.term.edit","") }}/" + id, function (data) {
        //         btn.prop('disabled', false);
        //         btn.html("<i class=\"material-icons\">&#xe31b;</i> {!! __('backend.save') !!}");
        //         $('#update-term .modal-body').html(data);
        //     });
        //     return false;
        // }

        $("#income_delete_btn").click(function () {
            $(this).html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 25px\"/> {!! __('backend.yes') !!}");
            var row_id = $(this).attr('row-id');
            if (row_id != "") {
                $.ajax({
                    type: "POST",
                    //dataType: "json",
                    url: "<?php echo route("cashflow.income.update_status"); ?>" ,
                    "data": {
                        "_token" : "{{csrf_token()}}",
                        "status" : "trashed",
                        "id" : row_id,
                    },
                    success: function (result) {
                        if (result.stat == 'success') {
                            $('#income_delete_btn').html("{!! __('backend.yes') !!}");
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
                        $('#delete-income').modal('hide');
                        $('.modal-backdrop').hide();
                    }
                });
            }
        });
       
    </script>
	<script src="{{ asset('assets/dashboard/js/jquery-ui/jquery-ui.min.js') }}"></script>
	
@endpush