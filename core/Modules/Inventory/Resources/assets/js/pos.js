document.addEventListener("keydown", function(event) {
    "use strict";
    if (event.altKey && event.code === "KeyO")
    {
        submit_order();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyZ")
    {
        $('#payment_close').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyS")
    {
        $('#order_complete').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyC")
    {
        emptyCart();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyA")
    {
        $('#add_new_customer').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyN")
    {
        $('#submit_new_customer').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyK")
    {
        $('#short-cut').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyP")
    {
        $('#print_invoice').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyQ")
    {
        $('#search').focus();
        $("#search-box").css("display", "none");
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyE")
    {
        $("#search-box").css("display", "none");
        $('#extra_discount').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyD")
    {
        $("#search-box").css("display", "none");
        $('#coupon_discount').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyB")
    {
        $('#invoice_close').click();
        event.preventDefault();
    }

});



"use strict";
function submit_order(){
    $("#search-box").css("display", "none");
    let cus_id = $('#customer').val();
    $('#customer_id').val(cus_id);
    let  cart_id = $('#cart_id').val();
    $('#order_cart_id').val(cart_id);
    if(cus_id == 'null')
    {
        toastr.warning('Please, Select Customer First.!', {
            CloseButton: true,
            ProgressBar: true
        });
    }else{
        let payementId = $('#payment_opp').val();
        if(payementId == 1)
        {
            let tt = $('#total_price').text();
            $('#cash_amount').attr({'min': tt,'required':true});
        }
        $("#paymentModal").modal();
    }
}


"use strict";
function price_calculation() {
    //console.log('reee');
    let collectedCash = $('#cash_amount').val();
    let order_total = $('#total_price').text();
    let total = parseFloat(collectedCash - order_total).toFixed(2);
    $('#returned').val(total);
}

"use strict";
function customer_Balance_Append(val)
{
    let customerId = val;
    $('#customer_balance').remove();
    if(customerId !=0){
        console.log(customerId);
        $('#payment_opp').append('<option id="customer_balance" value="0">Customer Balance</option>')
    }
}

    function printDiv(divName) {
        if($('html').attr('dir') === 'rtl') {
            $('html').attr('dir', 'ltr')
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            $('.width-inone').attr('dir', 'rtl')
            window.print();
            document.body.innerHTML = originalContents;
            $('html').attr('dir', 'rtl')
            location.reload()
        }else{
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload()
        }
    }


function addon_quantity_input_toggle(e) {
    var cb = $(e.target);
    if (cb.is(":checked")) {
        cb.siblings('.addon-quantity-input').css({'visibility': 'visible'});
    } else {
        cb.siblings('.addon-quantity-input').css({'visibility': 'hidden'});
    }
}

function checkAddToCartValidity() {
    var names = {};
    $('#add-to-cart-form input:radio').each(function () { // find unique names
        names[$(this).attr('name')] = true;
    });
    var count = 0;
    $.each(names, function () { // then count them
        count++;
    });
    if ($('input:radio:checked').length == count) {
        return true;
    }
    return false;
}

function cartQuantityInitialize() {
    $('.btn-number').on('click',function (e) {
        e.preventDefault();

        var fieldName = $(this).attr('data-field');
        var type = $(this).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (type == 'minus') {

                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if (type == 'plus') {

                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });

    $('.input-number').focusin(function () {
        $(this).data('oldValue', $(this).val());
    });

    $('.input-number').on('change',function () {

        let minValue = parseInt($(this).attr('min'));
        let maxValue = parseInt($(this).attr('max'));
        let valueCurrent = parseInt($(this).val());

        var name = $(this).attr('name');
        if (valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Cart',
                text: 'Sorry, the minimum value was reached'
            });
            $(this).val($(this).data('oldValue'));
        }
        if (valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Cart',
                text: 'Sorry, stock limit exceeded.'
            });
            $(this).val($(this).data('oldValue'));
        }
    });
    $(".input-number").on('keydown',function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
}

$(function () {
    $(document).on('click', 'input[type=number]', function () {
        this.select();
    });
});


jQuery(document).on('mouseup',function (e) {
    var container = $(".search-card");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.addClass('d-none');
    }
});

function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}
function toggle_full_screen()
{
    if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen))
    {
        if (document.documentElement.requestFullScreen){
            document.documentElement.requestFullScreen();
        }
        else if (document.documentElement.mozRequestFullScreen){ /* Firefox */
            document.documentElement.mozRequestFullScreen();
        }
        else if (document.documentElement.webkitRequestFullScreen){   /* Chrome, Safari & Opera */
            document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        }
        else if (document.msRequestFullscreen){ /* IE/Edge */
            document.documentElement.msRequestFullscreen();
        }
    }
    else
    {
        if (document.cancelFullScreen){
            document.cancelFullScreen();
        }
        else if (document.mozCancelFullScreen){ /* Firefox */
            document.mozCancelFullScreen();
        }
        else if (document.webkitCancelFullScreen){   /* Chrome, Safari and Opera */
            document.webkitCancelFullScreen();
        }
        else if (document.msExitFullscreen){ /* IE/Edge */
            document.msExitFullscreen();
        }
    }
}
$(document).on('change', '.money', function() {
    $(this).val(currency($(this).val(), {symbol: "",precision: 0}).format());
});

//Ajax Modal Function
$(document).on("click",".ajax-modal",function(){
    var link = $(this).data("href");
    if ( typeof link == 'undefined' ) {
        link = $(this).attr("href");
    }

    var title = $(this).data("title");


    $.ajax({
        url: link,
        beforeSend: function(){
           $("#preloader").css("display","block"); 
        },success: function(data){
           $("#preloader").css("display","none");
           $('#quick-view .modal-title').html(title);
           $('#quick-view .modal-body').html(data);
           $("#quick-view .alert-secondary").addClass('d-none');
           $("#quick-view .alert-danger").addClass('d-none');
           $('#quick-view').modal('show'); 
           
          
      

           
          
           $("#quick-view .ajax-submit input:required, #quick-view .ajax-submit select:required, #quick-view .ajax-submit textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
           $("#quick-view .ajax-screen-submit input:required, #quick-view .ajax-screen-submit select:required, #quick-view .ajax-screen-submit textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
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