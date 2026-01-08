(function($) {
	"use strict";
	
	 var table_name = $('#orders-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url:_url + '/pos/orders',
			data: function (d) {
				//d.status = $('#filter-status').val();
				
				
				
			}
		},
		"dom": '<"dataTables_wrapper"<"col-sm-12 col-md-3"i><"col-sm-12 col-md-2 pull-right"l><"col-sm-12 col-md-7 pull-right"f><"col-sm-12 col-md-12"r><"row"t><"row b-t p-x p-t dker"<"col-sm-12"p>>>',
		/*"fnDrawCallback": function () {
			if ($(table_name + '_paginate .paginate_button').length > 3) {
				$(table_name + '_paginate')[0].style.display = "block";
			} else {
				$(table_name + '_paginate')[0].style.display = "none";
			}


			$('[data-toggle="tooltip"]').tooltip({html: true});
			$('[data-toggle-second="tooltip"]').tooltip({html: true});
		},*/
            
		"columns" : [
			{ data : "check", name : "check", orderable: false },
			{ data : "id", name : "id" },
			{ data : "payment_id", name : "payment_id" },
			{ 
				data : "order_amount", 
				name : "order_amount",
				render: function (data, type, row) {
					return type === 'export' ? data.replace(/[₫,]/g, '') : data;
				}

			 },
			{ data : "user_id", name : "user_id", defaultContent: '' },
			{ data : "action", name : "action", orderable: false, class:'action-col'},
		],
		/*layout: {
			topStart: {
				buttons: [
					
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
		},*/
		//select: true,
		responsive: true,
		"bStateSave": true,
		"bAutoWidth":false,	
		//rowReorder: true,
		
	});
	
	$( document ).on('ajax-screen-submit', function() {
		table_name.draw(); 
		$('#main_modal').modal('hide').trigger('change');
	});
	

})(jQuery);