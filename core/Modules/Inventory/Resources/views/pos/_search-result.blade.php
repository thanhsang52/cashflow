

@foreach($products as $product)
<div id="{{ $product->item_code }}" class="">
    <input type="hidden" id="product_id" name="id" value="{{ $product->item_code }}" class="has-value">
    <input type="hidden" id="product_qty" name="quantity" value="1" class="has-value">
    <a data-id="{{ $product->item_code }}" class="pos-product-item card single-cart-data">
        <div class="pos-product-item_thumb">
            <img src="https://arserv2.medicare.com.vn/api/get-product-image/{{$product->product->picture_filename}}" class="img-fit">
        </div>
        <div class="pos-product-item_content">
            <div class="pos-product-item_title">{{$product->product->description}}</div>
            <div class="pos-product-item_price">
                @if($product->hasPromotion())
                <s>{{ $product->product_price->price . ' đ'}}</s> </br/>
                @else
                {{ $product->product_price->price . ' đ'}}</br/>
                @endif
            </div>
            @foreach($product->promotions as $promotion)
            <div class="pos-product-item_price_promotion">
                
                {{ $promotion->name }} </br/>
                {{ $promotion->Promption_price . ' đ'}}
            </div>
            @endforeach
        </div>
    </a>
</div>
@endforeach