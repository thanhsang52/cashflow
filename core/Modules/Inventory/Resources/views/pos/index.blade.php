@extends('inventory::pos.layouts.master')
            @section('content')
            <div class="container-fluid">
                <div class="d-flex flex-wrap">
                    
                    @php($customers = \App\Models\User::get())
                    <div class="order--pos-left">
                        <div class="card billing-section-wrap">
                            <h5 class="p-3 m-0 bg-light">{{__('inventory::pos.billing_section')}}</h5>
                            <div>
                                <div class="card-body pb-0">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="flex-grow-1">
                                            <!-- <select id='customer' name="customer_id"
                                                    class="form-control js-data-example-ajax customer-change">
                                                <option>{{__('--select-customer--')}}</option>
                                                <option value="0">{{__('walking_customer')}}</option>
                                            </select> -->
                                            <div class="input-group-overlay input-group-merge input-group-custom">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-barcode info fa-2x"></i>
                                                    </div>
                                                </div>
                                                <input id="search-customer" autocomplete="off" type="text" name="search_customer" class="form-control search-bar-input" placeholder="{{ __('inventory::pos.search_by_customer_code')}}" aria-label="Scan here">
                                                <!-- <div class="pos-search-card w-4 position-absolute z-index-1 w-100">
                                                    <div id="search-box-2" class="card card-body search-result-box d--none"></div>
                                                </div> -->
                                               
                                            </div>
                                        </div>
                                        <div class="">
                                            <button class="w-i6 d-inline-block btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal" data-target="#add-customer" title="{{__('Add Customer')}}">
                                                <i class="tio-add"></i>
                                                {{ __('inventory::pos.customer')}}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="input-label text-capitalize" >
                                            {{__('inventory::pos.current_customer')}} :
                                            <span class="style-i4" id="current_customer"></span>
                                            <input type="hidden" name="customer_id" id="customer_id" value=""/>
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 flex-wrap align-items-center mb-3">
                                        <div class="flex-grow-1">
                                            <select id='cart_id' name="cart_id"
                                                    class=" form-control js-select2-custom cart-change">
                                            </select>
                                        </div>

                                        <div>
                                            <a class="w-i6 d-inline-block btn btn-danger rounded" href="{{route('pos.clear-cart-ids')}}">
                                                {{ __('inventory::pos.clear_cart')}}
                                            </a>
                                        </div>

                                        <div>
                                            <a class="w-i6 d-inline-block btn btn-success rounded" href="{{route('pos.new-cart-id')}}">
                                                {{ __('inventory::pos.new_order')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <div id="cartloader" class="d-none">
                                            <img width="50" src="{{ URL::asset('core/Modules/Inventory/Resources/assets/img/loader.gif')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="cart">
                                @include('inventory::pos._cart',['cart_id'=>$cartId])
                            </div>
                        </div>
                    </div>

                    <div class="order--pos-right">
                        <div class="card">
                            <h5 class="p-3 m-0 bg-light">{{__('inventory::pos.product_section')}}</h5>
                            <div class="px-3 py-4">
                                <div class="row gy-1">
                                    
                                    <div class="col-sm-12">
                                        <form class="">
                                            <div class="input-group-overlay input-group-merge input-group-custom">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-barcode info fa-2x"></i>
                                                    </div>
                                                </div>
                                                <input id="search" autocomplete="off" type="text" name="search"
                                                    class="form-control search-bar-input"
                                                    placeholder="{{__('inventory::pos.search_by_code_or_name')}}"
                                                    aria-label="Search here" >
                                                <!-- <diV class="pos-search-card w-4 position-absolute z-index-1 w-100">
                                                    <div id="search-box" class="card card-body search-result-box d--none"></div>
                                                </diV> -->
                                               
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-2" id="items">
                                <div class="pos-item-wrap">
                                    @foreach($products as $product)
                                        @include('inventory::pos._single_product',['product'=>$product])
                                    @endforeach
                                </div>
                               
                                @if(isset($products) && count($products)>0)
                                <div class="table-responsive mt-4">
                                    <div class="px-4 d-flex justify-content-lg-end">
                                        {!!$products->withQueryString()->links()!!}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endsection
        
        @section('modal')
        <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="quick-view-modal">

                </div>
            </div>
        </div>
        @php($order=NULL)
        @if($order)
            @php(session(['last_order'=> false]))
            <div class="modal fade" id="print-invoice" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content modal-content1">
                        <div class="modal-header">
                            <h5 class="modal-title">{{__('inventory::pos.print_invoice')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="text-dark" aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row font-i1">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <input id="print_invoice" type="button" class="mt-2 btn btn-primary non-printable print-div"
                                        data-name="printableArea"
                                        value="Proceed, If thermal printer is ready."/>
                                    <a id="invoice_close" data-route="{{url()->previous()}}"
                                    class="mt-2 btn btn-danger non-printable invoice-close">{{__('inventory::pos.back')}}</a>
                                </div>
                                <hr class="non-printable">
                            </div>
                            <div class="row m-auto" id="printableArea">
                                @include('inventory::pos.order.invoice')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @endsection
   
@push("after-scripts")
<script>
    "use strict";

    $(document).on('click', '#logoutLink', function(e) {
        e.preventDefault();

        Swal.fire({
            title: '{{__('Do you want to logout')}}?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonColor: '#FC6A57',
            cancelButtonColor: '#363636',
            confirmButtonText: `{{__('Yes')}}`,
            denyButtonText: `{{__('Don\'t Logout')}}'`,
        }).then((result) => {
            if (result.value) {
                window.location.href = '';
            } else {
                Swal.fire('{{__('Canceled')}}', '', '{{__('info')}}');
            }
        });
    });

    $(document).on('ready', function () {

        $(".print-div").on('click', function(){
            let divName = $(this).data('name');
            printDiv(divName);
        });

        $(".invoice-close").on('click', function(){
            window.location.href = $(this).data('route');
        });

        $('.category-show').on('change', function() {
            set_category_filter($(this).val());
        });

        $('.cart-change').on('change', function() {
            cart_change($(this).val());
        });

        $('.customer-change').on('change', function() {
            customer_change($(this).val());
        });

        $(document).on('click', '.single-cart-data', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            console.log('click on '+id);
            addToCart(id);
        });

        $('.js-hs-unfold-invoker').each(function () {
            var unfold = new HSUnfold($(this)).init();
        });

        $('#search').focus();
        /*$.ajax({
            url: "{{route('pos.get-cart-ids')}}",
            type: 'GET',

            dataType: 'json',
            beforeSend: function () {
                $('#loading').removeClass('d-none');
            },
            success: function (data) {
                var output = '';
                    for(var i=0; i<data.cart_nam.length; i++) {
                        output += `<option value="${data.cart_nam[i]}" ${data.current_user==data.cart_nam[i]?'selected':''}>${data.cart_nam[i]}</option>`;
                    }
                    $('#cart_id').html(output);
                    $('#current_customer').text(data.current_customer);
                    $('#customer_id').val(data.customer_id);
                    $('#cart').empty().html(data.view);
                    if(data.user_type === 'sc')
                    {
                        //console.log('after add');
                        customer_Balance_Append(data.user_id);
                    }
            },
            complete: function () {
                $('#loading').addClass('d-none');
            },
        });*/
    

        $(".direction-toggle").on("click", function () {
            setDirection(localStorage.getItem("direction"));
        });

        function setDirection(direction) {
            if (direction == "rtl") {
                localStorage.setItem("direction", "ltr");
                $("html").attr('dir', 'ltr');
            $(".direction-toggle").find('span').text('Toggle RTL')
            } else {
                localStorage.setItem("direction", "rtl");
                $("html").attr('dir', 'rtl');
            $(".direction-toggle").find('span').text('Toggle LTR')
            }
        }

        if (localStorage.getItem("direction") == "rtl") {
            $("html").attr('dir', "rtl");
            $(".direction-toggle").find('span').text('Toggle LTR')
        } else {
            $("html").attr('dir', "ltr");
            $(".direction-toggle").find('span').text('Toggle RTL')
        }

   

    function payment_option(val) {
        if ($(val).val() != 1 && $(val).val() != 0) {
            $("#collected_cash").addClass('d-none');
            $("#returned_amount").addClass('d-none');
            $("#balance").addClass('d-none');
            $("#remaining_balance").addClass('d-none');
            $("#transaction_ref").removeClass('d-none');
            $('#cash_amount').attr('required',false);
            console.log($(val).val());
        } else if ($(val).val() == 'C') {
            $("#collected_cash").removeClass('d-none');
            $("#returned_amount").removeClass('d-none');
            $("#transaction_ref").addClass('d-none');
            $("#balance").addClass('d-none');
            $("#remaining_balance").addClass('d-none');
            console.log($(val).val());

        } else if($(val).val() == '0'){
            $("#balance").removeClass('d-none');
            $("#remaining_balance").removeClass('d-none');
            $("#collected_cash").addClass('d-none');
            $("#returned_amount").addClass('d-none');
            $("#transaction_ref").addClass('d-none');
            $('#cash_amount').attr('required',false);
            let customerId = $('#customer').val();
            /*$.ajax({
            url: '{{route('pos.customer-balance')}}',
            type: 'GET',
            data: {
                customer_id: customerId
            },
            dataType: 'json',
            beforeSend: function () {
                $('#loading').removeClass('d-none');
                console.log("loding");
            },
            success: function (data) {
                console.log(data.customer_balance);
                let balance = data.customer_balance;
                let order_total = $('#total_price').text();
                let remain_balance = parseInt(balance) - parseInt(order_total);
                $('#balance_customer').val(balance);
                $('#balance_remain').val(remain_balance);
            },
            complete: function () {
                $('#loading').addClass('d-none');
            },
        });*/
        }
    }

    function customer_change(val) {
        $.post({
                url: '{{route('pos.remove-coupon')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    user_id:val
                },
                beforeSend: function () {
                    $('#loading').removeClass('d-none');
                },
                success: function (data) {
                    var output = '';
                    for(var i=0; i<data.cart_nam.length; i++) {
                        output += `<option value="${data.cart_nam[i]}" ${data.current_user==data.cart_nam[i]?'selected':''}>${data.cart_nam[i]}</option>`;
                    }
                    $('#cart_id').html(output);
                    $('#current_customer').text(data.current_customer);
                    //$('#customer_id').val(data.current_customer.customer_code);
                    alert(data.current_customer);
                    $('#cart').empty().html(data.view);
                    customer_Balance_Append(val);
                },
                complete: function () {
                    $('#loading').addClass('d-none');
                }
            });
    }

    function cart_change(val)
    {
        let  cart_id = val;
        let url = "{{route('pos.change-cart')}}"+'/?cart_id='+val;
        document.location.href=url;
    }

    function extra_discount()
    {
        let discount = $('#dis_amount').val();
        console.log(discount);
        let type = $('#type_ext_dis').val();
        if(discount)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('pos.discount') }}',
                data: {
                    _token: '{{csrf_token()}}',
                    discount:discount,
                    type:type,
                },
                beforeSend: function () {
                    $('#loading').removeClass('d-none');
                },
                success: function (data) {
                    if(data.extra_discount==='success')
                    {
                        toastr.success('{{ __('extra_discount_added_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.extra_discount==='empty')
                    {
                        toastr.warning('{{ __('your_cart_is_empty') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });

                    }else{
                        toastr.warning('{{ __('this_discount_is_not_applied_for_this_amount') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                    $('.modal-backdrop').addClass('d-none');
                    $('#cart').empty().html(data.view);
                    if(data.user_type === 'sc')
                    {
                        customer_Balance_Append(data.user_id);
                    }
                    $('#search').focus();
                },
                complete: function () {
                    $('.modal-backdrop').addClass('d-none');
                    $(".footer-offset").removeClass("modal-open");
                    $('#loading').addClass('d-none');
                }
            });
        }
    }

    function coupon_discount()
    {
        let  coupon_code = $('#coupon_code').val();
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('pos.coupon-discount')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    coupon_code:coupon_code,
                },
                beforeSend: function () {
                    $('#loading').removeClass('d-none');
                },
                success: function (data) {
                    console.log(data);
                    if(data.coupon === 'success')
                    {
                        toastr.success('{{ __('coupon_added_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.coupon === 'amount_low')
                    {
                        toastr.warning('{{ __('this_discount_is_not_applied_for_this_amount') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.coupon === 'cart_empty')
                    {
                        toastr.warning('{{ __('your_cart_is_empty') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    else {
                        toastr.warning('{{ __('coupon_is_invalid') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                    $('#cart').empty().html(data.view);
                    if(data.user_type === 'sc')
                    {
                        customer_Balance_Append(data.user_id);
                    }
                    $('#search').focus();
                },
                complete: function () {
                    $('.modal-backdrop').addClass('d-none');
                    $(".footer-offset").removeClass("modal-open");
                    $('#loading').addClass('d-none');
                }
            });

    }

    $(document).on('ready', function () {
        @if($order)
            $('#print-invoice').modal('show');
        @endif
    });

    function set_category_filter(id) {
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('category_id', id);
        location.href = nurl;
    }

    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var keyword = $('#datatableSearch').val();
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('keyword', keyword);
        location.href = nurl;
    });

    function quickView(product_id) {
        $.ajax({
            url: '{{route('pos.quick-view')}}',
            type: 'GET',
            data: {
                product_id: product_id
            },
            dataType: 'json',
            beforeSend: function () {
                $('#loading').removeClass('d-none');
            },
            success: function (data) {
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').addClass('d-none');
            },
        });
    }

    function addToCart(productId) {
        //let productId = form_id;
        let productQty = $('#product_qty').val();
        console.log('product Qty: '+productQty);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Access-Control-Allow-Origin': '*'
            }
        });
        $.post({
            url: '{{ route('pos.add-to-cart') }}',
            crossDomain: true,
            data: {
                _token: '{{csrf_token()}}',
                id:productId,
                quantity:productQty,
            },
            beforeSend: function () {
                $('#cartloader').removeClass('d-none');
            },
            success: function (data) {
                if(data.qty==0)
                {
                    toastr.warning('{{__('product_quantity_end!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }else{
                    toastr.success('{{__('item_has_been_added_in_your_cart!')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                }

                $('#cart').empty().html(data.view);
                if(data.user_type === 'sc')
                {
                    customer_Balance_Append(data.user_id);
                }
                $('#search').val('').focus();
                $('#search-box').addClass('d-none');
            },
            complete: function () {
                $('#cartloader').addClass('d-none');

            }
        });

    }

    function removeFromCart(key) {
        $.post('{{ route('pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {

                $('#cart').empty().html(data.view);
                if(data.user_type === 'sc')
                {
                    customer_Balance_Append(data.user_id);
                }
                toastr.info('{{__('item_has_been_removed_from_cart')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            $('#search').focus();

        });
    }

    function emptyCart() {
        Swal.fire({
            title: '{{__('Are_you_sure?')}}',
            text: '{{__('You_want_to_remove_all_items_from_cart!!')}}',
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#161853',
            cancelButtonText: '{{__('No')}}',
            confirmButtonText: '{{__('Yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.post('{{ route('pos.emptyCart') }}', {_token: '{{ csrf_token() }}'}, function (data) {
                    $('#cart').empty().html(data.view);
                    $('#search').focus();
                    if(data.user_type === 'sc')
                    {
                        customer_Balance_Append(data.user_id);
                    }
                    toastr.info('{{__('Item_has_been_removed_from_cart')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                });
            }
        })

    }

    function updateCart() {
        $.post('<?php echo e(route('pos.cart_items')); ?>', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
            $('#cart').empty().html(data);

        });
    }

    function updateQuantity(id,qty) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.post({
            url: '{{ route('pos.updateQuantity') }}',
            data: {
                _token: '{{csrf_token()}}',
                key: id,
                quantity: qty,
            },
            beforeSend: function () {
                $('#loading').removeClass('d-none');
            },
            success: function (data) {
                if(data.qty<0)
                {
                    toastr.warning('{{__('product_quantity_is_not_enough!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.upQty==='zeroNegative')
                {
                    toastr.warning('{{__('Product_quantity_can_not_be_zero_or_less_than_zero_in_cart!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }

                $('#search').focus();
                $('#cart').empty().html(data.view);
                if(data.user_type === 'sc')
                {
                    customer_Balance_Append(data.user_id);
                }
            },
            complete: function () {
                $('#loading').addClass('d-none');
            }
        });



    }

    /*$('.js-select2-custom').each(function () {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });

    $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{route('pos.customers')}}',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });
*/

    jQuery("#search.search-bar-input").on('keypress',function (event) { 
        //event.preventDefault();
        if (event.which === 13) { //key enter
            $(".search-card").removeClass('d-none').show();
            let name = $(this).val();
            if (name.length >=6) {
                $('#search-box').removeClass('d-none').show();
                $.get({
                    url: '{{route("pos.search-products")}}',
                    headers: {  'Access-Control-Allow-Origin': '*' },
                    dataType: 'json',
                    timeout: 86400,
                    crossDomain: true,
                    data: {
                        name: name
                    },
                    beforeSend: function () {
                        $('#loading').removeClass('d-none');
                    },
                    success: function (data) {
                        if (data.count == 0) {
                            $('#search-box').addClass('d-none');
                        }
                        $('.search-result-box').empty().html(data.result);
                        $('.pos-item-wrap').empty().html(data.result);
                        
                        if(data.count==1){
                            
                            addToCart(data.product.item_code)
                        }
                        
                    },
                    complete: function () {
                        $('#loading').addClass('d-none');
                    },
                    error: function() {
                        $('.pos-item-wrap').empty().html("Error occurred");
                    }
                });
                
            } else {
                $('.search-result-box').empty();
                $('#search-box').addClass('d-none');
            }
            return false;
        }
        else return true;
        return false;
    });

    
    jQuery("#search-customer.search-bar-input").on('keypress',function (event) {
        //event.preventDefault();
        if (event.keyCode === 13) { //key enter
            $(".search-card").removeClass('d-none').show();
            let crm = $(this).val();
            if (crm.length > 9 || isNaN(crm)) {
                $.get({
                    url: '{{route('pos.search-crmcustomer-by-id')}}',
                    dataType: 'json',
                    crossDomain: true,
                    data: {
                        crm: crm
                    },
                    beforeSend: function () {
                        $('#loading').removeClass('d-none');
                    },
                    success: function (rs) {
                        console.log(rs.data.customer_code);
                        //if (rs) {
                            //$('#search').attr("disabled", true);
                            //addToCart(data.id);
                            //$('#search').attr("disabled", false);
                            //$('.search-result-box').empty().html(data.result);
                            //$('#search').val('');
                            //$('#search-box').addClass('d-none');
                            //alert(rs.data.customer_code + ' ' + rs.data.first_name + ' ' + rs.data.last_name);
                            $('span#current_customer').text(rs.data.customer_code + ' ' + rs.data.first_name + ' ' + rs.data.last_name);
                            $('input#customer_id').val(rs.data.customer_code);
                            
                        
                        //}
                    },
                    complete: function () {
                        $('#loading').addClass('d-none');
                    },
                });
            } else {
                $('.search-result-box').empty();

            }
        }
    });
});

</script>
<script src="{{ URL::asset('core/Modules/Inventory/Resources/assets/js/cart.js')}}"></script>   
@endpush