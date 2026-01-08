$(document).ready(function() {
    "use strict";
    
    // Setup AJAX CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Search product handler with barcode support
    $('#search').on('input', delay(function() {
        let query = $(this).val();
        if (query.length > 0) {
            // First try exact match (barcode)
            $.get('/pos/search-by-add', { name: query })
                .done(function(data) {
                    if (data.count > 0) {
                        addToCart(data.id);
                        $('#search').val('').focus();
                    }
                })
                .fail(function(jqXHR) {
                    if (jqXHR.status === 404) {
                        // If no exact match, search by name
                        $.get('/pos/search-products', { name: query })
                            .done(function(data) {
                                if (data.count > 0) {
                                    $("#search-box")
                                        .html(data.result)
                                        .removeClass('d-none')
                                        .addClass('search-result-box');
                                    
                                    // If single product found, add it directly
                                    if (data.product) {
                                        //addToCart(data.product.item_code);
                                        console.log('cart.js trigger event add to cart');
                                        $('#search').val('').focus();
                                        $("#search-box").addClass('d-none');
                                    }
                                } else {
                                    $("#search-box")
                                        .addClass('d-none')
                                        .removeClass('search-result-box');
                                    toastr.warning('No products found');
                                }
                            })
                            .fail(function() {
                                toastr.error('Error searching products');
                            });
                    } else {
                        toastr.error('Error searching products');
                    }
                });
        } else {
            $("#search-box")
                .addClass('d-none')
                .removeClass('search-result-box');
        }
    }, 300));

    // Product click handler with quantity validation
    $(document).on('click', '.pos-product-item', function(e) {
        //e.preventDefault();
        const productId = $(this).data('id');
        const productQty = $(`#${productId} #product_qty`).val() || 1;
        addToCart(productId, productQty);
    });

    // Handle money input formatting for Vietnamese currency
    $(document).on('change', '.money', function() {
        let value = $(this).val().replace(/[^\d]/g, '');
        $(this).val(new Intl.NumberFormat('vi-VN').format(value));
    });

    // Handle price calculation on cash amount change
    $('#cash_amount').on('input', price_calculation);

    // Terminal register amount inputs
    $('input[name="cash_in_hand_while_closing"], input[name="total_return"], input[name="total_amount"]').on('input', function() {
        let value = $(this).val().replace(/[^\d]/g, '');
        $(this).val(new Intl.NumberFormat('vi-VN').format(value));
    });

    // Close search results on click outside
    jQuery(document).on('mouseup', function(e) {
        var container = $(".search-card");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.addClass('d-none');
        }
    });

    // Initialize quantity controls
    cartQuantityInitialize();

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Load initial cart state
    loadCartState();

    // Focus number inputs on click
    $(document).on('click', 'input[type=number]', function() {
        this.select();
    });
});

function addToCart(id, quantity = 1) {
    if (!id) {
        toastr.warning('Invalid product');
        return;
    }

    $.post('/pos/add-to-cart', {
        id: id,
        quantity: quantity
    })
    .done(function(data) {
        $('#cart').html(data.view);
        if (data.qty === 0) {
            toastr.warning('Product out of stock');
        } else {
            toastr.success('Item added to cart');
            $('#search').val('').focus();
            $('#search-box').addClass('d-none');
        }
        if(data.user_type === 'sc') {
            customer_Balance_Append(data.user_id);
        }
    })
    .fail(function() {
        toastr.error('Failed to add item to cart');
    });
}

function updateQuantity(id, qty) {
    if (!id || qty <= 0) {
        toastr.warning('Invalid quantity');
        return;
    }

    $.post('/pos/update-quantity', {
        key: id,
        quantity: qty
    })
    .done(function(data) {
        $('#cart').html(data.view);
        if (data.qty < 0) {
            toastr.warning('Insufficient stock');
        } else if(data.upQty === 'zeroNegative') {
            toastr.warning('Quantity cannot be zero or negative');
        }
        $('#search').focus();
        if(data.user_type === 'sc') {
            customer_Balance_Append(data.user_id);
        }
    })
    .fail(function() {
        toastr.error('Failed to update quantity');
    });
}

function removeFromCart(key) {
    if (!key) {
        toastr.warning('Invalid item');
        return;
    }

    $.post('/pos/remove-from-cart', { key: key })
        .done(function(data) {
            $('#cart').html(data.view);
            if(data.user_type === 'sc') {
                customer_Balance_Append(data.user_id);
            }
            toastr.info('Item removed from cart');
            $('#search').focus();
        })
        .fail(function() {
            toastr.error('Failed to remove item');
        });
}

function emptyCart() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to remove all items from cart!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('/pos/empty-cart')
                .done(function(data) {
                    $('#cart').html(data.view);
                    $('#search').focus();
                    if(data.user_type === 'sc') {
                        customer_Balance_Append(data.user_id);
                    }
                    toastr.info('Cart has been emptied');
                })
                .fail(function() {
                    toastr.error('Failed to empty cart');
                });
        }
    });
}

// Import common functions from pos.js
const { 
    delay,
    loadCartState,
    submit_order,
    price_calculation,
    customer_Balance_Append,
    printDiv,
    cartQuantityInitialize,
    toggle_full_screen
} = window;
