(function($) {
	"use strict";
	
	 var table = $('#contract-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: _url + '/contract/get_table_data',
		"columns" : [
			{
				className: 'dt-control',
				orderable: false,
				data: null,
				defaultContent: ''
			},
			{ data : "code", name : "code", orderable: true, searchable: true  },
			{ data : "vendor.code", name : "vendor.code", orderable: true, searchable: true  },
			{ data : "reference", name : "reference", orderable: true, searchable: true  },
			{ data : "name", name : "name", orderable: true, searchable: true  },
			{ data : "display_effect_from", name : "effect_from", orderable: false  },
			{ data : "display_effect_to", name : "effect_to", orderable: false },
			
            
			{ data : "action", name : "action" },
		],
		responsive: true,
		"bStateSave": true,
		"bAutoWidth":false,	
		//rowReorder: true,
		order: [[0, 'asc'],[1, 'asc']],
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
	});
	// Formatting function for row details - modify as you need
function format(d) {
    // `d` is the original data object for the row
    return (
        '<div class="row extra-row detail-information">'+
		'<dl class="col-md-3">' +
        '<dt>'+$lang_contract_type+':</dt>' +
        '<dd> ' + d.display_contract_type + '</dd>' +
		'</dl>' +
		'<dl class="col-md-3">' +
        '<dt>'+$lang_base_value+':</dt>' +
        '<dd> ' + d.base_value + '</dd>' +
		'</dl>' +
		'<dl class="col-md-3">' +
        '<dt>'+$lang_payment_term +':</dt>' +
        '<dd> ' + d.payment_term + '</dd>' +
		'</dl>' +
		'<dl class="col-md-3">' +
        '<dt>'+$lang_new_store_sku_payment_days+':</dt>' +
        '<dd> ' + d.new_store_sku_payment_days +'</dd>' +
		'</dl>' +
        '<dl class="col-md-3">' +
        '<dt>'+$lang_included_vat+':</dt>' +
        '<dd> ' +d.included_vat +'</dd>' +
		'</dl>' +
		'<dl class="col-md-3">' +
        '<dt>'+$lang_return+':</dt>' +
        '<dd> ' +d.return +'</dd>' +
		'</dl>' +
		'<dl class="col-md-3">' +
		'<dt>'+$lang_near_expiry_product+':</dt>' +
        '<dd> '+d.near_expiry_product+'</dd>' +
		'</dl>' +
		'<dl class="col-md-3">' +
        '<dt>'+$lang_note+':</dt>' +
        '<dd> '+d.note+'</dd>' +
        '</dl>'+
		'</div>'
    );
}
// Add event listener for opening and closing details
table.on('click', 'td.dt-control', function (e) {
    let tr = e.target.closest('tr');
    let row = table.row(tr);
 
    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
    }
    else {
        // Open this row
        row.child(format(row.data())).show();
    }
});
})(jQuery);
