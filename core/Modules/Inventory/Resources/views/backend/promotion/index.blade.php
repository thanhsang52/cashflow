@extends('dashboard.layouts.master')
@section('title', __('inventory::backend.promotions'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">

@endpush

@section('content')
<div class="padding">
	<div class="box">
	   
		<div class="box-header dker">
		<h3>{!! __('inventory::backend.promotions') !!}</h3>
			<small>
				<a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
				<a>{!! __('inventory::backend.promotions') !!}</a>
			</small>
		</div>
		<!-- <div class="box-tool box-tool-lg">
			<ul class="nav" style="display: flex;justify-content: space-around;">
				
				@if(@Auth::user()->permissionsGroup->add_status)
					<li class="nav-item inline">
						<a class="btn btn-fw primary m-l" href="">
							<i class="material-icons">&#xe02e;</i>
							&nbsp; {{ __('inventory::backend.addNewinventory') }}
						</a>
					</li>
				@endif
			</ul>
		</div> -->
		<div>
			
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
							<th>{{ __('Promo code') }}</th>
							<th>{{ __('Type') }}</th>
							<th>{{ __('AR Name') }}</th>
							<th>{{ __('Start date') }}</th>
							<th>{{ __('End date') }}</th>
							<th>{{ __('Discount type') }}</th>
							<th>{{ __('Value') }}</th>
							<th>{{ __('Qualifying quantity') }}</th>
							
							<th>{{ __('Region') }}</th>
							<th>{{ __('Is member price') }}</th>
						
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
                    "url": "{{ route('promotion.get_promotion_table_data') }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {
                        data._token = "{{csrf_token()}}";
                        data.name = $('input[name=name]').val();
                        data.promo_code = $('select[name=promo_code]').val();
						data.region = $('select[name=region]').val();
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
			
						{ data : "promo_code", name : "promo_code", orderable: true, searchable: true  },
						{ data : "promotion_type_name", name : "promotion_type", orderable: false, searchable: false  },
						{ data : "name", name : "name", orderable: true, searchable: true  },
						{ data : "start_date", name : "start_date", orderable: true, searchable: true },
						{ data : "end_date", name : "end_date", orderable: true, searchable: false  },
						{ data : "discount_type_name", name : "discount_type", orderable: true, searchable: false  },
						{ data : "value", name : "value", orderable: true, searchable: false  },
						{ data : "qualifying_quantity", render: function ( data, type, row ) { 
							
							var is_number= /^-?\d+$/.test(data);
							if(is_number) return data;
							var json = $.parseJSON(JSON.stringify(data));
							//console.log(json);
							return '<a href="#" class="ajax-modal" data-title="'+ json.id +' '+json.name+'" data-href="{{url()->current()}}/detail/'+json.id+'"/><i class="material-icons"></i> Detail</a>';
							}   
						},
						
                        { data : "region", name : "region", orderable: true, searchable: true },
						{ data : "is_member_price", render: function ( data, type, row ) { 
								if(data==true)
                           			return '<i class=\"fa fa-check text-success inline\"></i>'; 
								return '<i class=\"fa fa-times text-danger inline\"></i>';
							}   
						},
						
					
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
        
		
		
    </script>
	<script src="{{ asset('assets/dashboard/js/jquery-ui/jquery-ui.min.js') }}"></script>
	<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/app.js') }}"></script>
@endpush