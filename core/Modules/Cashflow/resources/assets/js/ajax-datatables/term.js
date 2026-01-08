(function($) {
	"use strict";
	
	 var table = $('#term-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url:_url + '/term/get_table_data',
			data: function (d) {
				d.contract_id = $('#filter-category-id').val();
			}
		},
		"columns" : [
			{ data : "code", name : "code" , orderable: true, searchable: true },
			{ data : "name", name : "name" , orderable: true, searchable: true },
			{ data : "category.name", name : "category.name", orderable: false },
			{ data : "action", name : "action", orderable: false, class:'action-col'},
		],
		//order: [[0, 'asc']],
		rowReorder: true,
		"bStateSave": true,
		"bAutoWidth":false,
		
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
	table.on("click", "tbody tr td:not('.action-col')", function (e) {
		//e.currentTarget.classList.toggle('selected');
		$(e.target).parent().toggleClass('selected');
	});
	$( document ).on('ajax-screen-submit', function() {
		table.draw();
	});
	$('#filter-category-id').change(function(){
		table.draw();
	});
	document.querySelector('#confirm-delete-btn').addEventListener('click', function () {
		var jsonData = [];
		table.rows('.selected').data().each((row) =>jsonData.push(row.id));

		if(table.rows('.selected').data().length>0)
			if( confirm('Are you sure you want to delete ' + table.rows('.selected').data().length + ' row(s) selected')==true)
				$.ajax({
					url:_url + '/term/multidelete',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					type: 'POST',
					data: {
						"id":jsonData
					},
					success:function(data){
						table.row('.selected').remove().draw(false);
					}
				})
	
	});
})(jQuery);
