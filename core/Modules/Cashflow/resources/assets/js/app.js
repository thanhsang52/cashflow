//Ajax Modal Function
$(document).on("click",".ajax-modal",function(){
    var link = $(this).data("href");
    if ( typeof link == 'undefined' ) {
        link = $(this).attr("href");
    }

    var title = $(this).data("title");
    var fullscreen = $(this).data("fullscreen");
    var reload = $(this).data("reload");

    $.ajax({
        url: link,
        beforeSend: function(){
           $("#preloader").css("display","block"); 
        },success: function(data){
           $("#preloader").css("display","none");
           $('#main_modal .modal-title').html(title);
           $('#main_modal .modal-body').html(data);
           $("#main_modal .alert-secondary").addClass('d-none');
           $("#main_modal .alert-danger").addClass('d-none');
           $('#main_modal').modal('show'); 
           
           if(fullscreen ==true){
               $("#main_modal >.modal-dialog").addClass("fullscreen-modal");
           }else{
               $("#main_modal >.modal-dialog").removeClass("fullscreen-modal");
           }
           
           if(reload == false){
               $("#main_modal .ajax-submit").attr('data-reload',false);
           }
           
           //init Essention jQuery Library
           if($('.ajax-submit').length){
               $('.validate').parsley();
           }

           if($('.ajax-screen-submit').length){				
               $('.ajax-screen-submit').parsley();
           }
       
           //init_editor();
           
           /** Init Datepicker **/
           //init_datepicker();
           
           /** Init Colorpicker **/
           if($('.colorpicker').length){
               $('.colorpicker').colorpicker();
           }

           /** Init DateTimepicker **/
        //    $('.datetimepicker').daterangepicker({
        //        timePicker: true,
        //        timePicker24Hour: true,
        //        singleDatePicker: true,
        //        showDropdowns: true,
        //        locale: {
        //          format: 'YYYY-MM-DD HH:mm'
        //        }
        //    });

           
           $(".float-field").keypress(function(event) {
              if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
                   (event.which < 48 || event.which > 57)) { event.preventDefault();
               }
           });	

           $(".int-field").keypress(function(event) {
               if ((event.which < 48 || event.which > 57)) { event.preventDefault();
               }
           });	

           
           //Select2
        //    $("#main_modal select.select2").select2({
        //         dropdownParent: $("#main_modal")
        //    });
           
           //Ajax Select2
           if ($("#main_modal .select2-ajax").length) {
               $('#main_modal .select2-ajax').each(function(i, obj) {
                   
                   var display2 = "";
                   if( typeof  $(this).data('display2') !== "undefined" ){
                       display2 = "&display2=" +  $(this).data('display2');
                   }
           
           
                   $(this).select2({
                     ajax: {
                       url: _url + '/ajax/get_table_data?table=' + $(this).data('table') + '&value=' + $(this).data('value') + '&display=' + $(this).data('display') + display2 + '&where=' +$(this).data('where'),
                       processResults: function (data) {
                         return {
                           results: data
                         };
                       }
                     },
                     dropdownParent: $("#main_modal")
                   });
                       
               });
           }
           
           //Auto Selected
           if ($(".auto-select").length) {
               $('.auto-select').each(function(i, obj) {
                   $(this).val($(this).data('selected')).trigger('change');
               })	
           }

        //    $('.crm-scroll').slimscroll({
        //        railVisible: true,
        //        railColor: '#7f8c8d',
        //        height: '500px',
        //        alwaysVisible: true,
        //    });
                       
        //    $(".dropify").dropify();
           $("#main_modal .ajax-submit input:required, #main_modal .ajax-submit select:required, #main_modal .ajax-submit textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
           $("#main_modal .ajax-screen-submit input:required, #main_modal .ajax-screen-submit select:required, #main_modal .ajax-screen-submit textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
        },
         error: function (request, status, error) {
           console.log(request.responseText);
         }
    });
    
    return false;
}); 

$("#main_modal").on('show.bs.modal', function () {
    $('#main_modal').css("overflow-y","hidden"); 		
});

$("#main_modal").on('shown.bs.modal', function () {
   $('#main_modal').css("overflow-y","auto");	
});

//Ajax Secondary Modal Function
$(document).on("click",".ajax-modal-2",function(){
    var link = $(this).attr("href");

    var title = $(this).data("title");
    var fullscreen = $(this).data("fullscreen");
    var reload = $(this).data("reload");
    
    $.ajax({
        url: link,
        beforeSend: function(){
           $("#preloader").css("display","block"); 
        },success: function(data){
           $("#preloader").css("display","none");
           $('#secondary_modal .modal-title').html(title);
           $('#secondary_modal .modal-body').html(data);
           $("#secondary_modal .alert-secondary").addClass('d-none');
           $("#secondary_modal .alert-danger").addClass('d-none');
           $('#secondary_modal').modal('show'); 
           
           if(fullscreen ==true){
               $("#secondary_modal >.modal-dialog").addClass("fullscreen-modal");
           }else{
               $("#secondary_modal >.modal-dialog").removeClass("fullscreen-modal");
           }
           
           if(reload == false){
               $("#secondary_modal .ajax-submit").attr('data-reload',false);
           }
           
           //init Essention jQuery Library
           $("#secondary_modal select.select2").select2({
                dropdownParent: $("#secondary_modal")
           });
           
           //$('.year').mask('0000-0000');
           if($('.ajax-submit').length){
               $('.ajax-submit').parsley();
           }
           
           /** Init Datepicker **/
           init_datepicker();
           
           $(".float-field").keypress(function(event) {
              if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
                   (event.which < 48 || event.which > 57)) { event.preventDefault();
               }
           });	

           $(".int-field").keypress(function(event) {
               if ((event.which < 48 || event.which > 57)) { event.preventDefault();
               }
           });	
           
           //Ajax Select2
           if ($("#secondary_modal .select2-ajax").length) {
               $('#secondary_modal .select2-ajax').each(function(i, obj) {
                   
                   var display2 = "";
                   if( typeof  $(this).data('display2') !== "undefined" ){
                       display2 = "&display2=" +  $(this).data('display2');
                   }
           
           
                   $(this).select2({
                     ajax: {
                       url: _url + '/ajax/get_table_data?table=' + $(this).data('table') + '&value=' + $(this).data('value') + '&display=' + $(this).data('display') + display2 + '&where=' +$(this).data('where'),
                       processResults: function (data) {
                         return {
                           results: data
                         };
                       }
                     }
                   });
                       
               });
           }

           
           $(".dropify").dropify();
           $("#secondary_modal input:required, #secondary_modal select:required, #secondary_modal textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
        },
         error: function (request, status, error) {
           console.log(request.responseText);
         }
    });
    
    return false;
}); 
 //Ajax Secondary Modal Function
$(document).on("click",".ajax-modal-3",function(){

   var target = $(this).data("target");
   var title = $(this).data("title");
   $("#secondary_modal .modal-body").html($(target).find(".modal-body").children());
   $("#secondary_modal").toggle("modal");
   //$("#secondary_modal input:required, #secondary_modal select:required, #secondary_modal textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
       

   
   return false;
}); 
$("#secondary_modal").on('show.bs.modal', function () {
    $('#secondary_modal').css("overflow-y","hidden"); 		
});

$("#secondary_modal").on('shown.bs.modal', function () {
   $('#secondary_modal').css("overflow-y","auto");	
});


//Ajax Modal Submit
$(document).on("submit",".ajax-submit",function(){			 
    var link = $(this).attr("action");
    var reload = $(this).data('reload');
    var current_modal = $(this).closest('.modal');
    
    var elem = $(this);
    $(elem).find("button[type=submit]").prop("disabled",true);
    
    $.ajax({
        method: "POST",
        url: link,
        data:  new FormData(this),
        mimeType:"multipart/form-data",
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
           $("#preloader").css("display","block");  
        },success: function(data){
           $(elem).find("button[type=submit]").attr("disabled",false);	
           $("#preloader").css("display","none"); 
           var json = JSON.parse(data);
           if(json['result'] == "success"){

               if(reload != false){
                   //Main Modal
                   $('#main_modal .ajax-submit')[0].reset();
                   $("#main_modal .alert-secondary").html(json['message']);
                   $("#main_modal .alert-secondary").removeClass('d-none');
                   $("#main_modal .alert-danger").addClass('d-none');
               
                   window.setTimeout(function(){window.location.reload()}, 500);
               }else{
                   //Secondary Modal
                   $(current_modal).find('.ajax-submit')[0].reset();
                   $(current_modal).find(".alert-secondary").html(json['message']);
                   $(current_modal).find(".alert-secondary").removeClass('d-none');
                   $(current_modal).find(".alert-danger").addClass('d-none');
               }
               
           }else{
               if(Array.isArray(json['message'])){
                   if(reload != false){
                       //Main Modal
                       jQuery.each( json['message'], function( i, val ) {
                          $("#main_modal .alert-danger").append("<p>"+val+"</p>");
                       });
                       $("#main_modal .alert-secondary").addClass('d-none');
                       $("#main_modal .alert-danger").removeClass('d-none');
                   }else{
                       //Secondary Modal
                       jQuery.each( json['message'], function( i, val ) {
                          $(current_modal).find(".alert-danger").append("<p>"+val+"</p>");
                       });
                       $(current_modal).find(".alert-secondary").addClass('d-none');
                       $(current_modal).find(".alert-danger").removeClass('d-none');
                   }
               }else{
                   if(reload != false){
                       $("#main_modal .alert-danger").html("<p>" + json['message'] + "</p>");	
                       $("#main_modal .alert-secondary").addClass('d-none');
                       $("#main_modal .alert-danger").removeClass('d-none');
                   }else{
                       $(current_modal).find(".alert-danger").html("<p>" + json['message'] + "</p>");						
                       $(current_modal).find(".alert-secondary").addClass('d-none');
                       $(current_modal).find(".alert-danger").removeClass('d-none');
                   }
               }
           }
        },
        error: function (request, status, error) {
           console.log(request.responseText);
        }
    });

    return false;
});

//Ajax Modal Submit without loading
$(document).on("submit",".ajax-screen-submit",function(){			 
    var link = $(this).attr("action");
    var reload = $(this).data('reload');
    var current_modal = $(this).closest('.modal');
    
    var elem = $(this);
    $(elem).find("button[type=submit]").prop("disabled",true);
    
    $.ajax({
        method: "POST",
        url: link,
        data:  new FormData(this),
        mimeType:"multipart/form-data",
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
           $("#preloader").css("display","block");  
        },success: function(data){
           $(elem).find("button[type=submit]").attr("disabled",false);	
           $("#preloader").css("display","none"); 
           var json = JSON.parse(data);
           
           if(json['result'] == "success"){
               
               $(document).trigger('ajax-screen-submit');
               
               $.toast({
                   text: json['message'],
                   showHideTransition: 'slide',
                   icon: 'success',
                   position : 'top-right' 
               });
               
               var table  = json['table'];
               
               if(json['action'] == "update"){
                   
                   $(table + ' tr[data-id="row_' + json['data']['id'] +'"]').find('td').each (function() {
                      if(typeof $(this).attr("class") != "undefined"){
                          $(this).html(json['data'][$(this).attr("class")]);
                      }
                   });  
                   
               }else if(json['action'] == "store"){
                   $(elem)[0].reset();
                   var new_row = $(table).find('tbody').find('tr:eq(0)').clone();

                   $(new_row).attr("data-id", "row_"+json['data']['id']);
                   
                   
                   $(new_row).find('td').each (function() {
                       if($(this).attr("class") == "dataTables_empty"){
                          window.location.reload();
                       }	
                       if(typeof $(this).attr("class") != "undefined"){
                          $(this).html(json['data'][$(this).attr("class").split(' ')[0]]);
                       }
                   }); 
                   

                   $(new_row).find('form').attr("action", link + "/" + json['data']['id']);
                   $(new_row).find('.dropdown-edit').attr("data-href", link + "/" + json['data']['id']+"/edit");
                   $(new_row).find('.dropdown-view').attr("data-href", link + "/" + json['data']['id']);
                   
                   $(table).prepend(new_row);

               }

               $(current_modal).find(".alert-secondary").addClass('d-none');
               $(current_modal).find(".alert-danger").addClass('d-none');
               
           }else if(json['result'] == "error"){

               $(current_modal).find(".alert-danger").html("");
               if(Array.isArray(json['message'])){
                   jQuery.each( json['message'], function( i, val ) {
                      $(current_modal).find(".alert-danger").append("<p>" + val + "</p>");
                   });
                   $(current_modal).find(".alert-secondary").addClass('d-none');
                   $(current_modal).find(".alert-danger").removeClass('d-none');
               }else{
                   $(current_modal).find(".alert-danger").html("<p>" + json['message'] + "</p>");	
                   $(current_modal).find(".alert-secondary").addClass('d-none');
                   $(current_modal).find(".alert-danger").removeClass('d-none');
               }
           }else{
               $.toast({
                   text: data.replace(/(<([^>]+)>)/ig,""),
                   showHideTransition: 'slide',
                   icon: 'error',
                   position : 'top-right' 
               });
           }
        },
        error: function (request, status, error) {
           console.log(request.responseText);
        }
    });

    return false;
});

//Ajax Remove without loading
$(document).on("click",".ajax-get-remove",function(){	
    var current_modal = $(this).closest('.modal');
    
    Swal.fire({
     title: $lang_alert_title,
     text: $lang_alert_message,
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: $lang_confirm_button_text,
     cancelButtonText: $lang_cancel_button_text
   }).then((result) => {
       if (result.value) {
           var link = $(this).attr("href");
           $.ajax({
                method: "GET",
                url: link,
                beforeSend: function(){
                   $("#preloader").css("display","block");  
                },success: function(data){
                   $("#preloader").css("display","none"); 

                   var json = JSON.parse(JSON.stringify(data));
                   console.log(json['result']);
                   if(json['result'] == "success"){
                       
                       $.toast({
                           text: json['message'],
                           showHideTransition: 'slide',
                           icon: 'success',
                           position : 'top-right' 
                       });
                       
                       var table  = json['table'];
                       //$(table).find('#row_' + json['id']).remove();
                       $(table + ' tr[data-id="row_' + json['id'] +'"]').remove();

                   }else if(json['result'] == "error"){	
                       if(Array.isArray(json['message'])){
                           jQuery.each( json['message'], function( i, val ) {
                              $.toast({
                                   text: val,
                                   showHideTransition: 'slide',
                                   icon: 'error',
                                   position : 'top-right' 
                               });
                           });

                       }else{
                            $.toast({
                                   text: json['message'],
                                   showHideTransition: 'slide',
                                   icon: 'error',
                                   position : 'top-right' 
                               });
                       }
                   }else{
                       $.toast({
                           text: data.replace(/(<([^>]+)>)/ig,""),
                           showHideTransition: 'slide',
                           icon: 'error',
                           position : 'top-right' 
                       });
                   }
                },
                error: function (request, status, error) {
                   console.log(request.responseText);
                }
           });
       }
   });
   
   return false;

});


//Ajax Remove without loading
$(document).on("submit",".ajax-remove",function(event){	
     event.preventDefault();

    var current_modal = $(this).closest('.modal');
    
    Swal.fire({
     title: $lang_alert_title,
     text: $lang_alert_message,
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: $lang_confirm_button_text,
     cancelButtonText: $lang_cancel_button_text
   }).then((result) => {
       if (result.value) {
           var link = $(this).attr("action");
           $.ajax({
                method: "POST",
                url: link,
                data: $(this).serialize(),
                beforeSend: function(){
                   $("#preloader").css("display","block");  
                },success: function(data){
                   $("#preloader").css("display","none"); 
                   var json = JSON.parse(JSON.stringify(data));
                   if(json['result'] == "success"){
                       
                       $.toast({
                           text: json['message'],
                           showHideTransition: 'slide',
                           icon: 'success',
                           position : 'top-right' 
                       });
                       
                       var table  = json['table'];
                       //$(table).find('#row_' + json['id']).remove();
                       $(table + ' tr[data-id="row_' + json['id'] +'"]').remove();

                   }else if(json['result'] == "error"){
                       if(Array.isArray(json['message'])){
                           jQuery.each( json['message'], function( i, val ) {
                              $.toast({
                                   text: val,
                                   showHideTransition: 'slide',
                                   icon: 'error',
                                   position : 'top-right' 
                               });
                           });

                       }else{
                            $.toast({
                                   text: json['message'],
                                   showHideTransition: 'slide',
                                   icon: 'error',
                                   position : 'top-right' 
                               });
                       }
                   }else{
                       $.toast({
                           text: data.replace(/(<([^>]+)>)/ig,""),
                           showHideTransition: 'slide',
                           icon: 'error',
                           position : 'top-right' 
                       });
                   }
                },
                error: function (request, status, error) {
                   console.log(request.responseText);
                }
           });
       }
   });
   
});

function init_editor(){
	if($(".summernote").length > 0){
		  tinymce.init({
			  selector: "textarea.summernote",
			  theme: "modern",
			  height:250,
			  plugins: [
				  "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				  "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				  "save table contextmenu directionality emoticons template paste textcolor"
			  ],
			  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
			  style_formats: [
				  {title: 'Bold text', inline: 'b'},
				  {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
				  {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
				  {title: 'Example 1', inline: 'span', classes: 'example1'},
				  {title: 'Example 2', inline: 'span', classes: 'example2'},
				  {title: 'Table styles'},
				  {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			  ]
		  });
	}
}
function init_datepicker(){
	/** Start Datepicker **/
	
    var date_format = ["Y-m-d", "d-m-Y", "d/m/Y","m-d-Y", "m.d.Y", "m/d/Y", "d.m.Y", "d/M/Y", "M/d/Y", "d M, Y"];
    var picker_date_format = ["YYYY-MM-DD", "DD-MM-YYYY", "DD/MM/YYYY","MM-DD-YYYY", "MM.DD.YYYY", "MM/DD/YYYY", "DD.MM.YYYY", "DD/MMM/YYYY", "MMM/DD/YYYY", "DD MMM, YYYY"];
	
	var fake_format = picker_date_format[date_format.indexOf(_date_format)];

	$('.datepicker').daterangepicker({
		autoUpdateInput: true,
		singleDatePicker: true,
		showDropdowns: true,
		locale: {
		  format: 'YYYY-MM-DD'
		}
	});

	$('.datepicker').css('color','transparent');

	$(document).on('click','.fake_datepicker',function(){
         $(this).prev().focus();
	});

	//Set Default date
	if ($(".datepicker").length) {
		$('.datepicker').each(function(i, obj) {
			if(typeof $(this).next().attr('class') === "undefined"){
				$(this).after('<p class="fake_datepicker"></p>');
				$(this).next('.fake_datepicker').css('margin-top',"-48.2px");
		    }
			$(this).next('.fake_datepicker').html(moment($(this).val()).format(fake_format));
		})	
    }

	$('.datepicker').on('apply.daterangepicker', function(ev, picker) {
	     $(this).next('.fake_datepicker').html(moment($(this).val()).format(fake_format));
	});

	/** End Datepicker **/
}

function showRole(elem){
	if($(elem).val() == ''){
		return;
	}
	window.location = _url + '/cashflow/permission/control/' + $(elem).val();
}

$(function() {
	var increment = 10;
	var startFilter = 0;
	var endFilter = increment;
	var $this = $('.card-body .row.items');
	var elementLength = $this.find('div.item').length;
	$('.listLength').text(elementLength);

	if (elementLength > 2) {
		$('.buttonToogle').show();
	}
	$('.items .item').slice(startFilter, endFilter).addClass('shown');
	$('.shownLength').text(endFilter);
	$('.items .item').not('.shown').hide();
	$('.buttonToogle .showMore').on('click', function() {
		if (elementLength > endFilter) {
			startFilter += increment;
			endFilter += increment;
			$('.items .item').slice(startFilter, endFilter).not('.shown').addClass('shown').toggle(500);
			$('.shownLength').text((endFilter > elementLength) ? elementLength : endFilter);
			if (elementLength <= endFilter) {
				$(this).remove();
			}
		}

	});
});

$(document).on('click','.btn-remove-2',function(){
    var link = $(this).attr('href');
    //Sweet Alert for delete action
    Swal.fire({
      title: $lang_alert_title,
      text: $lang_alert_message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: $lang_confirm_button_text,
      cancelButtonText: $lang_cancel_button_text
    }).then((result) => {
      if (result.value) {
         window.location.href = link;
      }
    });
    
    return false;
});

$(document).on('click','.btn-remove',function(){
    //Sweet Alert for delete action
    Swal.fire({
      title: $lang_alert_title,
      text: $lang_alert_message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: $lang_confirm_button_text,
      cancelButtonText: $lang_cancel_button_text
    }).then((result) => {
      if (result.value) {
        $(this).closest('form').submit();
      }
    });
    
    return false;
});
$(document).ready(function(){
    //Auto Selected
    if ($(".auto-select").length) {
        $('.auto-select').each(function(i, obj) {
            $(this).val($(this).data('selected')).trigger('change');
        })	
    }

    //Access Control
	$(document).on('change','#permissions #role_id',function(){
		showRole($(this));
	});
	
	$("#permissions .custom-control-input").each(function(){
		if($(this).prop("checked") == true){
			$(this).closest(".collapse").addClass("show");
		}
	});
});
