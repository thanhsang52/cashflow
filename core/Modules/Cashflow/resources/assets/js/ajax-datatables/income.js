(function($) {
	"use strict";
	
	 var table = $('#income-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url:_url + '/income/get_table_data',
			data: function (d) {
				d.status = $('#filter-status').val();
				//d.contract_id = $('#filter-contract-id').val();
				d.contract_term_id = $('#filter-contract-term-id').val();
				
			}
		},
		"columns" : [
			{ data : "paid_at", name : "paid_at" },
			{ data : "contract_term.display_name",  name : "contract_term_display_name" },
			{ data : "status", name : "status" },
			{ 
				data : "amount", 
				name : "amount",
				render: function (data, type, row) {
					return type === 'export' ? data.replace(/[₫,]/g, '') : data;
				}

			 },
			{ data : "created_by.name", name : "created_user_id", defaultContent: '' },
			{ 
				data : "action", 
				name : "action", 
				orderable: false,
				render: function (data, type, row) {
					return type === 'export' ? data.replace(/[₫,]/g, '') : data;
				}
			}
		],
		layout: {
			topStart: {
				buttons: [
					/*{
                        text: '<i class="fa fa-plus"></i> Add',
                        action: function (e, dt, node, conf) {
                            window.location.href = '/income/create'; //relative to domain
                        }
                    },
					{
						text: 'See detail',
						action: function (e, dt, node, config) {
							alert(
								'Row data: ' + JSON.stringify(dt.row({ selected: true }).data())
							);
						},
						enabled: false
					},*/
					{
						extend: 'csvHtml5',
						text: '<i class="fa fa-file-code-o"></i> CSV',
						title: 'Data export',
						exportOptions: { 
							orthogonal: 'export',
							columns: [0, 1, 2, 3, 4]
						}
					},
					{
						extend: 'excelHtml5',
						text: '<i class="fa fa-file-excel-o"></i> Excel',
						title: 'Data export',
						exportOptions: { 
							columns: [0, 1, 2, 3, 4]
						}
					},
					{
						extend: 'pdfHtml5',
						text: '<i class="fa fa-file-pdf-o"></i> PDF',
						title: 'Data export',
						exportOptions: { 
							columns: [0, 1, 2, 3, 4]
						}
					},
					{
						extend: 'print',
						text: '<i class="fa fa-print"></i> Print',
						title: 'Data export',
						exportOptions: { 
							columns: [0, 1, 2, 3, 4]
						}
					},
					'colvis'
				]
			}
		},
		//select: true,
		responsive: true,
		"bStateSave": true,
		"bAutoWidth":false,	
		//rowReorder: true,
		"language": {
		   "decimal":        "",
		   "emptyTable":     $lang_no_data_found,
		   "info":           $lang_showing + " _START_ " + $lang_to + " _END_ " + $lang_of + " _TOTAL_ " + $lang_entries,
		   "infoEmpty":      $lang_showing_0_to_0_of_0_entries,
		   "infoFiltered":   "(filtered from _MAX_ total entries)",
		   "infoPostFix":    "",
		   "thousands":      ",",
		   "lengthMenu":     $lang_show + " _MENU_ " + $lang_entries,
		   "loadingRecords": $lang_loading,
		   "processing":     $lang_processing,
		   "search":         $lang_search,
		   "zeroRecords":    $lang_no_matching_records_found,
		   "paginate": {
			  "first":      $lang_first,
			  "last":       $lang_last,
			  "next":       $lang_next,
			  "previous":   $lang_previous
		   }
		}
	});
	
	$( document ).on('ajax-screen-submit', function() {
		table.draw(); 
		$('#main_modal').modal('hide').trigger('change');
	});
	// $('#filter-contract-id').change(function(){
	// 	table.draw();
	// });
	$('#filter-contract-term-id').change(function(){
		table.draw();
	});
	$('#filter-status').change(function(){
		table.draw();
	});
	$('#filter-contract-term-id').filterGroups({groupSelector: '#filter-contract-id', });
	$('.control-label-row-count').text(table.length);

})(jQuery);