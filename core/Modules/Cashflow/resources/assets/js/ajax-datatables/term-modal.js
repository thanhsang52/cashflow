(function($) {
	"use strict";
	
	 var table = $('#term-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url:_url + '/cashflow/term/get_table_data',
			data: function (d) {
				d.contract_id = $('#filter-category-id').val();
			}
		},
		"columns" : [
			//{ data : "id", name: "id"},
			{ data : "code", name : "code" , orderable: true, searchable: true },
			{ data : "name", name : "name" , orderable: true, searchable: true },
			{ data : "category.name", name : "category.name" },
			
			
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
	table.on("click", "tbody tr td", function (e) {
		//e.currentTarget.classList.toggle('selected');
		$(e.target).parent().toggleClass('selected');
	});
	$( document ).on('ajax-screen-submit', function() {
		table.draw();
	});
	$('#filter-category-id').change(function(){
		table.draw();
	});
    document.querySelector('#terms-select').addEventListener('click', function () {
		var jsonData = [];
		var compiledRow = "";
		var numRow = $('#table-selected-terms tbody tr').length;
		table.rows('.selected').data().each((row,i) => {
			jsonData.push(row.id);
			compiledRow += "<tr><td><input type=\"hidden\" name=termIDs[] value=\""+row.id+"\"/>"+(++numRow)+"</td><td><input type=\"text\" name=params[ref_num]["+row.id+"] class=\"form-control\" value=\"\" /></td><td>"+row.code+"</td><td>"+row.name+"</td><td><input type=\"text\" name=params[note]["+row.id+"] class=\"form-control\" value=\"\" /></td><td><a onClick=\"removeSelectedTerm()\" href=\"#\" class=\"btn btn-danger btn-xs\"><i class=\"ti-eraser\"></i>Destroy</a></td></tr>";
		});
		

		if(table.rows('.selected').data().length>0)
			if( confirm('Are you sure you want to select ' + table.rows('.selected').data().length + ' row(s)')==true)
            {
                $('#selected-terms-ids').val(jsonData);
				$('#table-selected-terms  tbody').append(compiledRow);
            }
	
	});
	$('#table-selected-terms tbody tr td .btn-remove').on('click', function(){
		$(this).parents('tr').remove();
	});

	
})(jQuery);
function removeSelectedTerm(){
	$(event.target).parents('tr').remove();
}