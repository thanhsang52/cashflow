(function($) {
	"use strict";
	
	 var table = $('#term-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url:_url + '/cashflow/contract_term/get_table_data',
			data: function (d) {
				d.contract_id = $('select#filter-contract-id').val();
				d.contract_term_id = $('select[name=contract_term_id]').val();
			}
		},
		"dom": '<"dataTables_wrapper"<"row"<"col-sm-12 col-md-5"l><"col-sm-12 col-md-7"f><"col-sm-12 col-md-12"r>><"row"t><"row b-t p-x p-t dker"<"col-sm-12 col-md-4 pull-left"i><"col-sm-12 col-md-8"p>>>',
		"columns" : [
			{ data : "contract.display_name", name : "contract" },
			{ data : "term.code", name : "term.code" , orderable: true, searchable: true },
			{ data : "term.name", name : "term.name" , orderable: true, searchable: true },
			

		],
		order: [[1, 'asc']],
		responsive: true,
		"bStateSave": true,
		"bAutoWidth":false,	
		"ordering": false,
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
	table.on("click", "tbody tr", function (e) {
		$('#term-table tbody tr.selected').removeClass('selected');
		$(e.currentTarget).toggleClass('selected');
		var row = table.rows('.selected').data();
		$('select[name=contract_term_id]').val(row[0].id).trigger('change');
		$('#main_modal').parents().find('button.close').click();
	});
	$( document ).on('ajax-screen-submit', function() {
		table.draw();
	});
	$('#filter-contract-id').select2().on('change',function(){
		table.draw();
	});
	$('#select-btn').on('click',function(){
		var row = table.rows('.selected').data();
		$('select[name=contract_term_id]').val(row[0].id).trigger('change');
	})
	
})(jQuery);
