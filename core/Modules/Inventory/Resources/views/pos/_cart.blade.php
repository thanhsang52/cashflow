@push('css_or_js')
<link rel="stylesheet" href="{{ URL::asset('core/Modules/Inventory/Resources/assets/css/custom.css')}}"/>
@endpush
<div class="card-body pt-0">
    <div class="table-responsive pos-cart-table border">
        <table class="table table-align-middle mb-0">
            <thead class="text-muted">
            <tr>
                <th>{{ __('inventory::pos.item') }}</th>
                <th>{{ __('inventory::pos.qty') }}</th>
                <th>{{ __('inventory::pos.price') }}</th>
                <th>{{ __('inventory::pos.delete') }}</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $subtotal = 0;
            $tax = 0;
            $ext_discount = 0;
            $ext_discount_type = 'amount';
            $discount_on_product = 0;
            $product_tax = 0;
            $coupon_discount = 0;
            ?>

            @if (session()->has($cartId) && count(session($cartId)) > 0)
                    <?php
                    $cart = session()->get($cartId);
                    if (isset($cart['tax'])) {
                        $tax = $cart['tax'];
                    }
                    if (isset($cart['ext_discount'])) {
                        $ext_discount = $cart['ext_discount'];
                        $ext_discount_type = $cart['ext_discount_type'];
                    }
                    if (isset($cart['coupon_discount'])) {
                        $coupon_discount = $cart['coupon_discount'];
                    }
                    ?>
                @foreach (session($cartId) as $key => $cartItem)
                    @if (is_array($cartItem))
                            <?php
                            $product_subtotal = $cartItem['price'] * $cartItem['quantity'];
                            $discount_on_product += $cartItem['discount'] * $cartItem['quantity'];
                            $subtotal += $product_subtotal;
                            $product_tax += $cartItem['tax'] * $cartItem['quantity'];

                            ?>
                        <tr>
                            <td class="media gap-2 align-items-center">
                                <img class="avatar avatar-sm"
                                     src="{{$cartItem['image']}}"
                                     alt="{{ $cartItem['name'] }} {{__('image')}}">
                                <div class="">
                                    <h5 class="text-hover-primary mb-0">{{ Str::limit($cartItem['name'], 10) }}</h5>
                                </div>
                            </td>
                            <td>
                                <input type="number" data-key="{{ $key }}" class="form-control text-center qty-width"
                                       value="{{ $cartItem['quantity'] }}" min="1"
                                       onkeyup="updateQuantity('{{ $cartItem['id'] }}',this.value)">
                            </td>
                            <td>
                                <div>
                                    {{ $product_subtotal . ' đ'  }}
                                </div>
                            </td>
                            <td>
                                <a href="javascript:removeFromCart({{ $cartItem['id'] }})"
                                   class="btn btn-sm btn-outline-danger square-btn"> <i class="tio-delete"></i></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
@php
    $total = $subtotal - $discount_on_product;
    $discount_amount = $ext_discount_type == 'percent' && $ext_discount > 0 ? ($subtotal * $ext_discount) / 100 : $ext_discount;
    $total -= $discount_amount;
    $total_tax_amount = $product_tax;
@endphp
<div class="box p-3">
    <dl class="row">
        <dt class="col-6">{{ __('inventory::pos.sub_total') }} :</dt>
        <dd class="col-6 text-right">{{ $subtotal . ' đ'  }}</dd>

        <dt class="col-6">{{ __('inventory::pos.product_discount') }} :</dt>
        <dd class="col-6 text-right">{{ round($discount_on_product, 2) . ' đ'  }}
        </dd>

        <dt class="col-6">{{ __('inventory::pos.extra_discount') }} :</dt>
        <dd class="col-6 text-right">
            <button id="extra_discount" class="btn btn-sm" type="button" data-toggle="modal"
                    data-target="#add-discount"><i
                    class="tio-edit"></i></button>{{ number_format($discount_amount, 2)}} đ 
        </dd>
        <dt class="col-6">{{ __('inventory::pos.coupon_discount') }} :</dt>
        <dd class="col-6 text-right">
            <button id="coupon_discount" class="btn btn-sm" type="button" data-toggle="modal"
                    data-target="#add-coupon-discount"><i
                    class="tio-edit"></i></button>{{ $coupon_discount . ' đ'  }}
        </dd>

        <dt class="col-6">{{ __('inventory::pos.tax') }} :</dt>
        <dd class="col-6 text-right">{{ round($total_tax_amount, 2) . ' đ'  }}</dd>
        <dt class="col-6">{{ __('inventory::pos.total') }} :</dt>
        <dd class="col-6 text-right h4 b">
            <span id="total_price">{{ round($total + $total_tax_amount - $coupon_discount, 2) }}</span>
            đ
        </dd>
    </dl>
    <div class="row g-2">
        <div class="col-6 mt-2">
            <button type="button" class="btn btn-danger btn-block empty-cart">
                <i class="fa fa-times-circle "></i>
                {{ __('inventory::pos.cancel_order') }}
            </button>
        </div>
        <div class="col-6 mt-2">
            <button type="button" class="btn btn-success btn-block submit-order">
                <i class="fa fa-shopping-bag"></i>
                {{ __('inventory::pos.place_order') }}
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="add-customer" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('add_new_customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="product_form">
                    @csrf
                    <input type="hidden" class="form-control" name="balance" value=0>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ __('customer_name') }} <span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                       placeholder="{{ __('customer_name') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ __('mobile_no') }} <span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="tel" id="mobile" name="mobile" class="form-control"
                                       value="{{ old('mobile') }}"
                                       pattern="[+0-9]+"
                                       title="Please enter a valid phone number with only numbers and the plus sign (+)"
                                       placeholder="{{ __('mobile_no') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ __('email') }}</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email') }}"
                                       placeholder="{{ __('Ex_:_ex@example.com') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ __('state') }}</label>
                                <input type="text" name="state" class="form-control"
                                       value="{{ old('state') }}" placeholder="{{ __('state') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ __('city') }} </label>
                                <input type="text" name="city" class="form-control"
                                       value="{{ old('city') }}" placeholder="{{ __('city') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ __('zip_code') }} </label>
                                <input type="text" name="zip_code" class="form-control"
                                       value="{{ old('zip_code') }}"
                                       placeholder="{{ __('zip_code') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ __('address') }} </label>
                                <input type="text" name="address" class="form-control"
                                       value="{{ old('address') }}"
                                       placeholder="{{ __('address') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" id="submit_new_customer"
                                class="btn btn-primary">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('inventory::pos.extra_discount') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="">{{ __('discount') }}</label>
                        <input type="number" id="dis_amount" class="form-control" name="discount" step="0.01" min="0">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">{{ __('type') }}</label>
                        <select name="type" id="type_ext_dis" class="form-control type_ext_dis">
                            <option value="amount" {{ $ext_discount_type == 'amount' ? 'selected' : '' }}>
                                {{ __('amount') }}
                                (đ)
                            </option>
                            <option value="percent" {{ $ext_discount_type == 'percent' ? 'selected' : '' }}>
                                {{ __('percent') }}
                                (%)
                            </option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary extra-discount"
                            type="submit">{{ __('submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-coupon-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('inventory::pos.coupon_discount') }}</h5>
                <button id="coupon_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">{{ __('coupon_code') }}</label>
                    <input type="text" id="coupon_code" class="form-control" name="coupon_code">
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary coupon-discount" type="submit">{{ __('submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-tax" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('update_tax') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pos.tax') }}" method="POST" class="row">
                    @csrf
                    <div class="form-group col-12">
                        <label for="">{{ __('tax') }} (%)</label>
                        <input type="number" class="form-control" name="tax" min="0">
                    </div>

                    <div class="form-group col-sm-12">
                        <button class="btn btn-sm btn-primary"
                                type="submit">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('inventory::pos.payment') }} </h5>
                <br/>
                
                <button id="payment_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form action="{{ route('pos.order') }}" id='order_place' method="post">
                    @csrf
                    <div class="form-group">
                        <h4 class="mb-0" id="total_balance">
                            <span class="style-four-cart">{{ __('inventory::pos.total') }} = 
                            {{ round($total + $total_tax_amount - $coupon_discount, 2) }}
                            đ
                            </span>
                        </h4>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="">{{ __('inventory::pos.type') }}</label>
                        <select class="payment-opp form-control" name="payment_id" id="payment_opp"
                                class="form-control select2" required>
                                <option value="1">{{ __('inventory::pos.cash') }}</option>
                                <option value="1">{{ __('inventory::pos.momo') }}</option>
                        </select>
                    </div>
                    <div class="form-group d-none" id="balance">
                        <label class="input-label" for="">{{ __('inventory::pos.customer_balance') }}
                            (đ)</label>
                        <input type="number" id="balance_customer" class="form-control" name="customer_balance"
                               disabled>
                    </div>
                    <div class="form-group d-none" id="remaining_balance">
                        <label class="input-label" for="">{{ __('inventory::pos.remaining_balance') }}
                            (đ)</label>
                        <input type="number" id="balance_remain" class="form-control" name="remaining_balance"
                               value="" readonly>
                    </div>
                    <div class="form-group d-none" id="transaction_ref">
                        <label class="input-label" for="">{{ __('inventory::pos.transaction_reference') }}
                            (đ)
                            -({{ __('optional') }})</label>
                        <input type="text" id="tran_ref" class="form-control" name="transaction_reference">
                    </div>
                    <div class="form-group" id="collected_cash">
                        <label class="input-label" for="">{{ __('inventory::pos.collected_cash') }}
                            (đ)</label>
                        <input type="number" id="cash_amount" onkeyup="price_calculation();" class="form-control"
                               name="collected_cash" step="0.01">
                    </div>
                    <div class="form-group" id="returned_amount">
                        <label class="input-label" for="">{{ __('inventory::pos.returned_amount') }}
                            (đ)</label>
                        <input type="number" id="returned" class="form-control" name="returned_amount"
                               value="" readonly>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary" id="order_complete"
                                type="submit">{{ __('inventory::pos.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="short-cut-keys" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('short_cut_keys') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span>{{ __('to_click_order') }} : alt + O</span><br>
                <span>{{ __('to_click_payment_submit') }} : alt + S</span><br>
                <span>{{ __('to_close_payment_submit') }} : alt + Z</span><br>
                <span>{{ __('to_click_cancel_cart_item_all') }} : alt + C</span><br>
                <span>{{ __('to_click_add_new_customer') }} : alt + A</span> <br>
                <span>{{ __('to_submit_add_new_customer_form') }} : alt + N</span><br>
                <span>{{ __('to_click_short_cut_keys') }} : alt + K</span><br>
                <span>{{ __('to_print_invoice') }} : alt + P</span> <br>
                <span>{{ __('to_cancel_invoice') }} : alt + B</span> <br>
                <span>{{ __('to_focus_search_input') }} : alt + Q</span> <br>
                <span>{{ __('to_click_extra_discount') }} : alt + E</span> <br>
                <span>{{ __('to_click_coupon_discount') }} : alt + D</span> <br>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="close-terminal-register" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('inventory::pos.close_terminal_register') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{Form::open(['route'=>['pos.close-shift'],'method'=>'POST', 'files' => true])}}
                <div class="row" id="register_items">
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">{{__('inventory::pos.cash_in_hand_while_closing')}}</label>
                            <input type="text" class="form-control" name="cash_in_hand_while_closing" value="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">{{__('inventory::pos.other')}}</label>
                            <input type="text" class="form-control" name="other" value="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">{{__('inventory::pos.total_return')}}</label>
                            <input type="text" class="form-control" name="total_return" value="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">{{__('inventory::pos.total_amount')}}</label>
                            <input type="text" class="form-control" name="total_amount" value="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">{{__('inventory::pos.note')}}</label>
                            <textarea class="form-control" name="notes"></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center"><button type="submit" class="btn btn-success">{{__('inventory::pos.close_shift')}}</button></div>
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>
@php 
$shift = session()->get('shift');
@endphp
<div class="modal fade" id="open-terminal-register" role="dialog" aria-modal="true">
    <div class="modal-dialog  modal-md">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-directional-purple white">

                <h4 class="modal-title" id="myModalLabel">Terminal Register Status</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">POS</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center m-1">Opened On <span id="r_date">{{$shift->created_at}}</span> By {{isset($shift->user->name)??$shift->user->name}}<span></span></div>
                <div class="row" id="register_items">
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">Cash</label>
                            <input type="text" class="form-control" id="0" value="{{$shift->cash_in_hand}}" readonly="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">Bank Transfer</label>
                            <input type="text" class="form-control green" id="0" value="0" readonly="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">Cheque</label>
                            <input type="text" class="form-control green" id="0" value="0" readonly="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">Prepaid Card</label>
                            <input type="text" class="form-control green" id="0" value="0" readonly="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">Other</label>
                            <input type="text" class="form-control green" id="0" value="0" readonly="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group  text-bold-600 green">
                            <label for="0">Change</label>
                            <input type="text" class="form-control green" id="0" value="0" readonly="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


