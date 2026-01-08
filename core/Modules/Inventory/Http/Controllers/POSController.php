<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
/*use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Transection;
use App\Models\Account;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;*/
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Inventory\Entities\ItemBranch;
use Modules\Inventory\Entities\Item;
use Modules\Inventory\Entities\Customer;
use Modules\Inventory\Entities\Order;
use Modules\Inventory\Entities\OrderDetail;
use Modules\Inventory\Entities\Shift;
use Auth;
use DataTables;

class POSController extends Controller
{
    public function __construct(
        /*private Category $category,
        private Product $product,
        private Order $order,
        private Coupon $coupon,
        private Transection $transection,
        private Account $account,
        private OrderDetail $orderDetails,
        private Customer $customer*/
        
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): Factory|View|Application
    {
        $branch_no = Auth::user()->branch_no;
        session()->put('branch_no', $branch_no);
        if (!session()->has('shift')) {
            $shifts = Shift::where('branch_no', $branch_no)->where('user_id', Auth::user()->id)->where('closed_at', NULL)->get();
            if ($shifts->count()!=1 ) 
                return view('inventory::pos.shift-register',compact('branch_no','shifts'));
            else
                session()->put('shift', $shifts->first());
        }
        $category = $request->query('category_id', 0);
        $keyword = $request->query('search', false);
        $key = explode(' ', $keyword);
        //$categories = $this->category->where('status', 1)->where('position', 0)->latest()->get();
        $categories = NULL;

            $products=array();

        $cartId = 'wc-' . rand(10, 1000);

        if (!session()->has('current_user')) {
            session()->put('current_user', $cartId);
        }
        if (strpos(session('current_user'), 'wc')) {
            $userId = 0;
        } else {
            $userId = explode('-', session('current_user'))[1];
        }

        if (!session()->has('cart_name')) {
            if (!in_array($cartId, session('cart_name') ?? [])) {
                session()->push('cart_name', $cartId);
            }
        }

        return view('inventory::pos.index', compact('categories', 'products', 'cartId', 'category', 'userId'));
    }
    public function registerShift(Request $request){
        $shift_id = $request->get('shift_id');
        $shift = NULL;
        if(isset($shift_id))
            $shift = Shift::find($shift_id);
        else{
            $shift = new Shift();
            $shift->cash_in_hand = intval(str_replace(",","",$request->get('cash_in_hand')));
            $shift->user_id = Auth::user()->id;
            $shift->branch_no = $request->get('branch_no');
            $shift->save();
        }
        session()->put('shift', $shift);
        return redirect()->route('pos.index');
    }
    public function closeShift(Request $request){
        
        $shift = session()->get('shift');
        $shift = Shift::find($shift->id);
        if(isset($shift)){
            $shift->closed_at = \Carbon\Carbon::now();
            $shift->notes = $request->get('notes');
            $shift->cash_in_hand_while_closing = intval($request->get('cash_in_hand_while_closing')) ;
            $shift->other = $request->get('other');
            $shift->total_return = intval($request->get('total_return'));
            $shift->total_amount= $request->get('total_amount');
            $shift->save();
            session()->forget('shift');
            Auth::logout();
        }
        return redirect('/');
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCustomers(Request $request): JsonResponse
    {
        $key = explode(' ', $request['q']);
        $data = [];

        return response()->json($data);
    }
    /**
     * @return RedirectResponse
     */
    public function clearCartIds(): RedirectResponse
    {
        session()->forget('cart_name');
        session()->forget(session('current_user'));
        session()->forget('current_user');

        return redirect()->route('pos.index');
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCartIds(Request $request): JsonResponse
    {
        $cartId = session('current_user');
        $userId = 0;
        $userType = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $userId = explode('-', session('current_user'))[1];
            $userType = 'sc';
        }
        $cart = session($cartId);
        $cartKeeper = [];
        if (session()->has($cartId) && count($cart) > 0) {
            foreach ($cart as $cartItem) {
                $cartKeeper[] = $cartItem;
            }
        }
        session()->put(session('current_user'), $cartKeeper);
        $userId = explode('-', session('current_user'))[1];
        $currentCustomer = '';
        if (explode('-', session('current_user'))[0] == 'wc') {
            $currentCustomer = 'Walking Customer';
        } else {
            $current = $this->customer->where('id', $userId)->first();
            $currentCustomer = $current->name . ' (' . $current->mobile . ')';
        }
        $payment_methods = TenderType::where('enable_yn',1)->get();
        return response()->json([
            'current_user' => session('current_user'),
            'cart_nam' => session('cart_name') ?? '',
            'current_customer' => $currentCustomer,
            'user_type' => $userType,
            'user_id' => $userId,
            'branch_no' => session('branch_no'),
         
            'view' => view('inventory::pos._cart', compact('cartId','payment_methods'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchProduct(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => __('Product name is required'),
        ]);

        //$key = explode(' ', $request['name']);
        $key =  $request['name'];
        $products = ItemBranch::where('item_code',$key)->where('on_hand', '>', 0)->where('branch_no',session('branch_no'))->get();

        $countP = $products->count();

        return response()->json([
            'result' => view('inventory::pos._search-result', compact('products'))->render(),
            'count' => $countP,
            'product' => $countP==1?$products[0]:NULL
        ],200);
    }
    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function searchByAddProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => __('Product name is required'),
        ]);
        $key =  $request['name'];
        if (is_numeric($key)) {
            $products = ItemBranch::where('item_code',$key)->paginate(6);
        } else {
            $products = ItemBranch::where('on_hand', '>', 0)
                        ->whereHas('product', function ($query) use ($key ) {
                            if (!empty($key))
                                $query->where('description', 'LIKE' ,  "%" .$key."%");
                        })
                        ->paginate(6);
        }

        $countP = $products->count();
        if ($countP > 0) {
            return response()->json([
                'count' => $countP,
                'id' => $products->first()->item_code,
            ]);
        }
        // Return a response when no products are found
        return response()->json(['message' => 'No products found'], 404);
    }

    public function searchCRMCustomer(Request $request): JsonResponse
    {
        $request->validate([
            'crm' => 'required',
        ], [
            'crm.required' => __('Product name is required'),
        ]);

        //$key = explode(' ', $request['crm']);
        $key = trim($request['crm']);
        $customer = Customer::where('customer_code',$key)->first();

        return response()->json([
            'data' => $customer
        ]);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addToCart(Request $request): JsonResponse
    {
        $cartId = session('current_user');
        $userId = 0;
        $userType = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $userId = explode('-', session('current_user'))[1];
            $userType = 'sc';
        }
        $product = Item::find($request->id);
        $cart = session($cartId);
        if (session()->has($cartId) && count($cart) > 0) {
            foreach ($cart as $key => $cartItem) {
                if (is_array($cartItem) && $cartItem['id'] == $request['id']) {
                    $qty = $product->quantity - $cartItem['quantity'];
                    if ($qty == 0) {
                        return response()->json([
                            'qty' => $qty,
                            'user_type' => $userType,
                            'user_id' => $userId,
                            'view' => view('inventory::pos._cart', compact('cartId'))->render()
                        ]);
                    }
                }
            }
        }

        $data = array();
        $data['id'] = $product->item_code;
        $cartKeeper = [];
        $itemExist = 0;
        if (session()->has($cartId) && count($cart) > 0) {
            foreach ($cart as $key => $cartItem) {
                if (is_array($cartItem) && $cartItem['id'] == $request['id']) {
                    $cartItem['quantity'] += 1;
                    $itemExist = 1;
                }
                array_push($cartKeeper, $cartItem);
            }
        }
        session()->put($cartId, $cartKeeper);

        if ($itemExist == 0) {
            $itemBranch = ItemBranch::where(
                [
                    'item_code' => $product->item_code,
                    'branch_no' => session('branch_no')
                ]
                )->first();
            if($itemBranch->hasPromotion()){
                $promotion = $itemBranch->promotions()->first();
            
            }
            $data['quantity'] = $request['quantity'];
            $data['price'] = $product->product_price->price;
            $data['name'] = $product->ext_info->name_vi;
            $data['discount'] =  (isset($promotion)?($promotion->price - $promotion->Promption_price):0);
            $data['image'] = 'https://arserv2.medicare.com.vn/api/get-product-image/'.$product->picture_filename;
            $data['tax'] = 0;
            if ($request->session()->has($cartId)) {
                $keeper = [];
                foreach (session($cartId) as $item) {
                    array_push($keeper, $item);
                }
                $keeper[] = $data;
                $request->session()->put($cartId, $keeper);
            } else {
                $request->session()->put($cartId, [$data]);
            }
        }

        return response()->json([
            'user_type' => $userType,
            'user_id' => $userId,
            'view' => view('inventory::pos._cart', compact('cartId'))->render()
        ]);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        $cartId = session('current_user');
        $userId = Auth::user()->id;
        $userType = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $userId = explode('-', session('current_user'))[1];
            $userType = 'sc';
        }
        $cart = session($cartId);
        $cartKeeper = [];
        if (session()->has($cartId) && count($cart) > 0) {
            foreach ($cart as $cartItem) {
                if (is_array($cartItem) && $cartItem['id'] != $request['key']) {
                    array_push($cartKeeper, $cartItem);
                }
            }
        }
        session()->put($cartId, $cartKeeper);
        return response()->json([
            'user_type' => $userType,
            'user_id' => $userId,
            'view' => view('inventory::pos._cart', compact('cartId'))->render()
        ], 200);
    }
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function newCartId(Request $request): RedirectResponse
    {
        $cartId = 'wc-' . rand(10, 1000);
        session()->put('current_user', $cartId);
        if (!in_array($cartId, session('cart_name') ?? [])) {
            session()->push('cart_name', $cartId);
        }

        return redirect()->route('pos.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDiscount(Request $request): JsonResponse
    {
        $cartId = session('current_user');
        $userId = 0;
        $userType = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $userId = explode('-', session('current_user'))[1];
            $userType = 'sc';
        }
        $cart = session($cartId, collect([]));
        if ($cart != null) {
            $totalProductPrice = 0;
            $productDiscount = 0;
            $productTax = 0;
            $extDiscount = 0;
            $couponDiscount = $cart['coupon_discount'] ?? 0;
            foreach ($cart as $ct) {
                if (is_array($ct)) {
                    $totalProductPrice += $ct['price'] * $ct['quantity'];
                    $productDiscount += $ct['discount'] * $ct['quantity'];
                    $productTax += $ct['tax'] * $ct['quantity'];
                }
            }
            $priceDiscount = 0;
            if ($request->type == 'percent') {
                $priceDiscount = ($totalProductPrice / 100) * $request->discount;
            } else {
                $priceDiscount = $request->discount;
            }
            $extDiscount = $priceDiscount;
            $total = $totalProductPrice - $productDiscount + $productTax - $couponDiscount - $extDiscount;

            if ($total < 0) {
                return response()->json([
                    'extra_discount' => "amount_low",
                    'user_type' => $userType,
                    'user_id' => $userId,
                    'view' => view('inventory::pos._cart', compact('cartId'))->render()
                ]);
            } else {
                $cart['ext_discount'] = $request->discount;
                $cart['ext_discount_type'] = $request->type;
                session()->put($cartId, $cart);

                return response()->json([
                    'extra_discount' => "success",
                    'user_type' => $userType,
                    'user_id' => $userId,
                    'view' => view('inventory::pos._cart', compact('cartId'))->render()
                ]);
            }
        } else {
            return response()->json([
                'extra_discount' => "empty",
                'user_type' => $userType,
                'user_id' => $userId,
                'view' => view('inventory::pos._cart', compact('cartId'))->render()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateTax(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart['tax'] = $request->tax;
        $request->session()->put('cart', $cart);

        return back();
    }

    /**
     * @param $cart
     * @param $price
     * @return float|int
     */
    public function extraDisCalculate($cart, $price): float|int
    {

        if ($cart['ext_discount_type'] == 'percent') {
            $priceDiscount = ($price / 100) * $cart['ext_discount'];
        } else {
            $priceDiscount = $cart['ext_discount'];
        }

        return $priceDiscount;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $cartId = session('current_user');
        $userId = 0;
        $userType = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $userId = explode('-', session('current_user'))[1];
            $userType = 'sc';
        }
        //$cart = session($cartId, collect([]));

        if ($request->quantity > 0) {
            $item_code = $request->key;
         
            /*$product = current(array_filter($cart, function($v, $k) use ($item_code) {
                return $v['id'] == $item_code;
            }, ARRAY_FILTER_USE_BOTH));*/
            //$product = $this->product->find($request->key);
            $product = ItemBranch::where(
                [
                    'item_code' => $request->key,
                    'branch_no' => session('branch_no')
                ]
                )->first();
            $cart = session($cartId);
            $keeper = [];
            foreach ($cart as $item) {
                if (is_array($item)) {
                    if ($item['id'] == $request->key) {
                        $qty = $product->on_hand - $request->quantity;
                        if ($qty < 0) {
                            return response()->json([
                                'qty' => $qty,
                                'user_type' => $userType,
                                'user_id' => $userId,
                                'view' => view('inventory::pos._cart', compact('cartId'))->render()
                            ]);
                        }
                        $item['quantity'] = $request->quantity;
                    }
                    $keeper[] = $item;
                }
            }
            session()->put($cartId, $keeper);

            return response()->json([
                'user_type' => $userType,
                'user_id' => $userId,
                'view' => view('inventory::pos._cart', compact('cartId'))->render()
            ], 200);
        } else {
            return response()->json([
                'upQty' => 'zeroNegative',
                'user_type' => $userType,
                'user_id' => $userId,
                'view' => view('inventory::pos._cart', compact('cartId'))->render()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function placeOrder(Request $request): RedirectResponse
    {
        $cartId = session('current_user');
        $userId = 0;
        $userType = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $userId = explode('-', session('current_user'))[1];
            $userType = 'sc';
        }
        if (session($cartId)) {
            if (count(session($cartId)) < 1) {
                Toastr::error(translate('cart_empty_warning'));
                return back();
            }
        } else {
            Toastr::error(translate('cart_empty_warning'));
            return back();
        }

        $cart = session($cartId);
        $coupon_code = 0;
        $productPrice = 0;
        $orderDetails = [];
        $productDiscount = 0;
        $productTax = 0;
        $extDiscount = 0;
        $couponDiscount = $cart['coupon_discount'] ?? 0;

        $order_id = 100000 + Order::all()->count() + 1;
        if (Order::find($order_id)) {
            $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
        }

        $order = new Order();
        $order->id = $order_id;

        $order->user_id = $userId;
        $order->coupon_code = $cart['coupon_code'] ?? null;
        $order->coupon_discount_title = $cart['coupon_title'] ?? null;
        $order->payment_id = $request->type;
        $order->transaction_reference = $request->transaction_reference ?? null;

        $order->created_at = now();
        $order->updated_at = now();

        foreach ($cart as $c) {
            if (is_array($c)) {
                $product = ItemBranch::where('branch_no',session('branch_no'))->where('item_code',$c['id'])->first();
                if ($product) {
                    $price = $c['price'];
                    $orD = [
                        'product_id' => $c['id'],
                        'product_details' => $product,
                        'quantity' => $c['quantity'],
                        'price' => $c['price'],
                        'tax_amount' => 0,
                        'discount_on_product' => $c['discount'],
                        'discount_type' => 'discount_on_product',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $productPrice += $price * $c['quantity'];
                    $productDiscount += $c['discount'] * $c['quantity'];
                    $productTax += $c['tax'] * $c['quantity'];
                    $orderDetails[] = $orD;

                    //$product->quantity = $product->quantity - $c['quantity'];
                    //$product->order_count++;
                    //$product->save();
                }
            }
        }
        $totalPrice = $productPrice - $productDiscount;

        if (isset($cart['ext_discount_type'])) {
            $extDiscount = $this->extraDisCalculate($cart, $productPrice);
            $order->extra_discount = $extDiscount;
        }

        $totalTaxAmount = $productTax;
        try {
            $order->total_tax = $totalTaxAmount;
            $order->order_amount = $totalPrice;

            $order->coupon_discount_amount = $couponDiscount;
            $order->collected_cash = $request->collected_cash ? $request->collected_cash : $totalPrice + $totalTaxAmount - $extDiscount - $couponDiscount;
            $order->branch_no = session('branch_no');
            $rs = $order->save();

            //$customer = $this->customer->where('id', $userId)->first();
            /*if ($userId != 0 && $request->type == 0) {
                $grandTotal = $totalPrice + $totalTaxAmount - $extDiscount - $couponDiscount;

                if ($request->remaining_balance >= 0) {
                    $payableAccount = Account::find(2);
                    $payableTransaction = new Transection;
                    $payableTransaction->tran_type = 'Payable';
                    $payableTransaction->account_id = $payableAccount->id;
                    $payableTransaction->amount = $grandTotal;
                    $payableTransaction->description = 'POS order';
                    $payableTransaction->debit = 1;
                    $payableTransaction->credit = 0;
                    $payableTransaction->balance = $payableAccount->balance - $grandTotal;
                    $payableTransaction->date = date("Y/m/d");
                    $payableTransaction->customer_id = $customer->id;
                    $payableTransaction->order_id = $order_id;
                    $payableTransaction->save();

                    $payableAccount->total_out = $payableAccount->total_out + $grandTotal;
                    $payableAccount->balance = $payableAccount->balance - $grandTotal;
                    $payableAccount->save();
                } else {

                    if ($customer->balance > 0) {
                        $payableAccount = Account::find(2);
                        $payableTransaction = new Transection;
                        $payableTransaction->tran_type = 'Payable';
                        $payableTransaction->account_id = $payableAccount->id;
                        $payableTransaction->amount = $customer->balance;
                        $payableTransaction->description = 'POS order';
                        $payableTransaction->debit = 1;
                        $payableTransaction->credit = 0;
                        $payableTransaction->balance = $payableAccount->balance - $customer->balance;
                        $payableTransaction->date = date("Y/m/d");
                        $payableTransaction->customer_id = $customer->id;
                        $payableTransaction->order_id = $order_id;
                        $payableTransaction->save();

                        $payableAccount->total_out = $payableAccount->total_out + $customer->balance;
                        $payableAccount->balance = $payableAccount->balance - $customer->balance;
                        $payableAccount->save();

                        $receivableAccount = Account::find(3);
                        $receivableTransaction = new Transection;
                        $receivableTransaction->tran_type = 'Receivable';
                        $receivableTransaction->account_id = $receivableAccount->id;
                        $receivableTransaction->amount = -$request->remaining_balance;
                        $receivableTransaction->description = 'POS order';
                        $receivableTransaction->debit = 0;
                        $receivableTransaction->credit = 1;
                        $receivableTransaction->balance = $receivableAccount->balance - $request->remaining_balance;
                        $receivableTransaction->date = date("Y/m/d");
                        $receivableTransaction->customer_id = $customer->id;
                        $receivableTransaction->order_id = $order_id;
                        $receivableTransaction->save();

                        $receivableAccount->total_in = $receivableAccount->total_in - $request->remaining_balance;
                        $receivableAccount->balance = $receivableAccount->balance - $request->remaining_balance;
                        $receivableAccount->save();
                    } else {

                        $receivableAccount = Account::find(3);
                        $receivableTransaction = new Transection;
                        $receivableTransaction->tran_type = 'Receivable';
                        $receivableTransaction->account_id = $receivableAccount->id;
                        $receivableTransaction->amount = $grandTotal;
                        $receivableTransaction->description = 'POS order';
                        $receivableTransaction->debit = 0;
                        $receivableTransaction->credit = 1;
                        $receivableTransaction->balance = $receivableAccount->balance + $grandTotal;
                        $receivableTransaction->date = date("Y/m/d");
                        $receivableTransaction->customer_id = $customer->id;
                        $receivableTransaction->order_id = $order_id;
                        $receivableTransaction->save();

                        $receivableAccount->total_in = $receivableAccount->total_in + $grandTotal;
                        $receivableAccount->balance = $receivableAccount->balance + $grandTotal;
                        $receivableAccount->save();
                    }
                }

                $customer->balance = $request->remaining_balance;
                $customer->save();
            }

            //transaction start
            if ($request->type != 0) {
                $account = Account::find($request->type);
                $transection = new Transection;
                $transection->tran_type = 'Income';
                $transection->account_id = $request->type;
                $transection->amount = $totalPrice + $totalTaxAmount - $extDiscount - $couponDiscount;
                $transection->description = 'POS order';
                $transection->debit = 0;
                $transection->credit = 1;
                $transection->balance = $account->balance + $totalPrice + $totalTaxAmount - $extDiscount - $couponDiscount;
                $transection->date = date("Y/m/d");
                $transection->customer_id = $customer->id;
                $transection->order_id = $order_id;
                $transection->save();
                //transaction end

                //account
                $account->balance = $account->balance + $totalPrice + $totalTaxAmount - $extDiscount - $couponDiscount;
                $account->total_in = $account->total_in + $totalPrice + $totalTaxAmount - $extDiscount - $couponDiscount;
                $account->save();
            }*/

            foreach ($orderDetails as $key => $item) {
                $orderDetails[$key]['order_id'] = $order->id;
            }

            OrderDetail::insert($orderDetails);

            session()->forget($cartId);
            session(['last_order' => $order->id]);

            Toastr::success(__('order_placed_successfully'));
            return back();
        } catch (\Exception $e) {

            Toastr::warning(__($e->getMessage()));
            return back();
        }
    }
    public function orderList(Request $request){
        $shift = session()->get('shift');
        $type = $request->get('type');
        $branch_no = $shift->branch_no;
        if($branch_no>0){
            
            if( $type=='index'){
                return view('inventory::pos.order.list');
            }else{
                $limit = $request->get('length',config('smartend.backend_pagination'));
                $start = $request->get('start',0);
                $orders = Order::where('branch_no',$branch_no)->offset($start)->limit($limit);
                $total = $orders->get()->count();
                $x = 0;
                return Datatables::eloquent($orders)	
                    ->addColumn('check', function ($order) {
                        return '<div class="row_checker $i"><label class="ui-check m-a-0">
                                        <input type="checkbox" name="ids[]" value="'.$order->id.'"><i class="dark-white"></i>
                                        <input type="hidden" name="row_ids[]" value="'.$order->id.'" class="form-control row_no">
                                    </label>
                                </div>';
                            
                        })
                        ->addColumn('action', function ($order) use($total,&$x) {
                            $x++;
                            return '
                            <div class="text-center">
                                <div class="dropdown ' . ((($x+2) >= $total) ? "dropup" : "") . '">
                                    <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
                                    <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item $key" href="'.action('\Modules\Cashflow\App\Http\Controllers\ContractController@show', $order->id) .'" target="_blank"><i class="material-icons"></i> Show</a>'.
                                    '<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\ContractController@duplicate', $order->id) .'"><i class="material-icons"></i> Download invoice</a>'.
                                    '</div>
                                </div>
                            </div>
                            ';
                        
                        })
                    ->setRowId(function ($order) {
                        return "row_".$order->id;
                    })
                    ->rawColumns(['check','id','payment_id','order_amount','created_at','user_id','action'])
                    ->make(true);
            }
        }
    }
    public function getOrderData(Request $request){
        $branch_no = $request->get('branch_no');
        if($branch_no>0){
            $limit = $request->get('length',config('smartend.backend_pagination'));
            $start = $request->get('start',0);
            $orders = Order::where('branch_no',$branch_no)->limit($limit);;
            $total = $orders->get()->count();
            $x = 0;
            return Datatables::eloquent($orders)	
            ->addColumn('check', function ($order) {
                return '<div class="row_checker $i"><label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="'.$order->id.'"><i class="dark-white"></i>
                                <input type="hidden" name="row_ids[]" value="'.$order->id.'" class="form-control row_no">
                            </label>
                        </div>';
                    
                })
                ->addColumn('action', function ($order) use($total,&$x) {
                    $x++;
                    return '
                    <div class="text-center">
                        <div class="dropdown ' . ((($x+2) >= $total) ? "dropup" : "") . '">
                            <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
                            <div class="dropdown-menu pull-right">
                            <a class="dropdown-item $key" href="'.action('\Modules\Cashflow\App\Http\Controllers\ContractController@show', $order->id) .'" target="_blank"><i class="material-icons"></i> Show</a>'.
                            '<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\ContractController@duplicate', $order->id) .'"><i class="material-icons"></i> Download invoice</a>'.
                            '</div>
                        </div>
                    </div>
                    ';
                
                })
            ->setRowId(function ($order) {
                return "row_".$order->id;
            })
            ->rawColumns(['check','id','payment_id','order_amount','created_at','user_id','action'])
            ->make(true);
            
        }
    }
}
