<div id="{{ $product->item_code }}" class="">
    <input type="hidden" id="product_id" name="id" value="{{ $product->item_code }}">
    <input type="hidden" id="product_qty" name="quantity" value=1>
    <a data-id="{{ $product->item_code }}" class="pos-product-item card single-cart-data">
        <div class="pos-product-item_thumb">
            <img src="https://arserv2.medicare.com.vn/api/get-product-image/{{$product->product->picture_filename}}"
                class="img-fit">
        </div>
        <div class="pos-product-item_content">
            <div class="pos-product-item_title">{{ $product->description }}</div>
            <div class="pos-product-item_price">
                {{ $product->product_price->price . ' đ'}}
            </div>
        </div>
    </a>
</div>
