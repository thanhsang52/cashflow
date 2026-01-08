(function($) {
	"use strict";
	
	 var table = $('#vendor-table').DataTable({
		processing: true,
		serverSide: false,
		ajax: 'vendor/get_table_data',
		"columns" : [
			{ data : "ids", name : "ids", orderable: false, searchable: false, class:"dker"  },
			{ data : "code", name : "code", orderable: true, searchable: true  },
			{ data : "name", name : "name", orderable: true, searchable: true },
			{ data : "company_name", name : "company_name", orderable: true, searchable: true  },
			{ data : "email", name : "email", orderable: true, searchable: true  },
            { data : "phone", name : "phone", orderable: false, searchable: true },
			{ data : "action", name : "action", orderable: false, searchable: false },
		],
		responsive: true,
		"bStateSave": true,
		"bAutoWidth":false,	
		rowReorder: false,
		// "language": {
		//    "decimal":        "",
		//    "emptyTable":     $lang_no_data_found,
		//    "info":           $lang_showing + " _START_ " + $lang_to + " _END_ " + $lang_of + " _TOTAL_ " + $lang_entries,
		//    "infoEmpty":      $lang_showing_0_to_0_of_0_entries,
		//    "infoFiltered":   "(filtered from _MAX_ total entries)",
		//    "infoPostFix":    "",
		//    "thousands":      ",",
		//    "lengthMenu":     $lang_show + " _MENU_ " + $lang_entries,
		//    "loadingRecords": $lang_loading,
		//    "processing":     $lang_processing,
		//    "search":         $lang_search,
		//    "zeroRecords":    $lang_no_matching_records_found,
		//    "paginate": {
		// 	  "first":      $lang_first,
		// 	  "last":       $lang_last,
		// 	  "next":       $lang_next,
		// 	  "previous":   $lang_previous
		//    }
		// }
	});
	
	$( document ).on('ajax-screen-submit', function() {
		table.draw();
	});
})(jQuery);