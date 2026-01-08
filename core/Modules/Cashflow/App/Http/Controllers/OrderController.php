<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\OrderLine;
use Modules\Cashflow\App\Models\Order;
use App\Models\WebmasterSection;
use Validator;
use DataTables;
use Auth;
use \GuzzleHttp\Client;
use Carbon\Carbon;
use QrCode;
class OrderController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');
        header('Content-Type: text/html; charset=utf-8');
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        //$invoices = Invoice::all()->sortByDesc("id");
        return view('cashflow::backend.order.list',compact('GeneralWebmasterSections'));
    }
    public function get_table_data(Request $request){
		$limit = $request->input('length',config('smartend.backend_pagination'));
        $start = $request->input('start',0);
        $dir = $request->input('order.0.dir');
        $ordering = $request->input('order.0.column');
        if ($ordering == "") {
            $ordering = "id";
        }

		$orders = Order::with('customer')->with('address')->limit($limit);
        $total = $orders->get()->count();
        $x = 0;
		return Datatables::eloquent($orders)	
            ->addColumn('check', function ($order) {
                return '<div class="row_checker $i"><label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="'.$order['id'].'"><i class="dark-white"></i>
                                <input type="hidden" name="row_ids[]" value="'.$order['id'].'" class="form-control row_no">
                            </label>
                        </div>';
                    
                })
            ->addColumn('options', function ($order) use($total,&$x) {
                $x++;
                return '
                <div class="text-center">
                    <div class="dropdown ' . ((($x+2) >= $total) ? "dropup" : "") . '">
                        <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
                        <div class="dropdown-menu pull-right">'.
                        ($order->id?'<a class="dropdown-item $key" href="'.action('\Modules\Cashflow\App\Http\Controllers\OrderController@show', $order['id']) .'" target="_blank"><i class="material-icons"></i> View</a>':'').
                        ($order->status=='cancelled'?'<a class="dropdown-item $key" href="'.action('\Modules\Cashflow\App\Http\Controllers\OrderController@show', $order['id']) .'" target="_blank"><i class="material-icons"></i> Reason</a>':'').
                        (@Auth::user()->permissionsGroup->edit_status?'<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\OrderController@edit', $order['id']) .'"><i class="material-icons"></i> Edit</a>':'').
                       
                        '</div>
                    </div>
                </div>
                ';
            
            })
            ->setRowId(function ($order) {
                return "row_".$order->id;
            })
            ->filter(function ($instance) use ($request) {
               
                if (!empty($request->get('status'))) {
                    $instance->where('status', $request->get('status'));
                }
                if (!empty($request->get('payment_status'))) {
                    $instance->where('payment_status', $request->get('payment_status'));
                }
                if (!empty($request->get('payment_method'))) {
                    $instance->whereJsonContains('payment', ['method' => $request->get('payment_method')]);
                }
                if (!empty($request->get('delivery_status'))) {
                    $instance->where('delivery_status', $request->get('delivery_status'));
                }
                if (!empty($request->get('coupon'))) {
                    $instance->where('coupon', 'LIKE' ,"%" . $request->get('coupon'). "%");
                }
                if (!empty($request->get('invoice'))) {
                    $instance->where('invoice', $request->get('invoice'));
                }
                if (!empty($request->get('from_date'))) {
                    $from_date = Carbon::parse($request->get('from_date'))->format('Y-m-d');
                    $instance->whereRaw('DATE(DATE_ADD(created_at, INTERVAL 7 HOUR)) >= ?', [$from_date]);
                }
                if (!empty($request->get('to_date'))) {
                    $to_date = Carbon::parse($request->get('to_date'))->format('Y-m-d');
                    $instance->whereRaw('DATE(DATE_ADD(created_at, INTERVAL 7 HOUR)) <= ?', [$to_date]);
                }
                $search = $request->get('search');
                if (!empty($search['value'])){
                    $instance->whereHas('customer', function ($query) use ($search) {
                        $query->where('given_name', 'LIKE' ,"%" . $search['value']. "%");
                    });
                }
            })
            ->rawColumns(['check','invoice','customer_id','channel_id','tax','total','discount_total','status','delivery_total','payment_status','customer','options'])
            ->make(true);							    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.note.create');
        }else{
           return view('backend.note.modal.create');
        }
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'type' => 'required',
			'object_id' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('cashflow.note.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        $note = new Note();
        $note->content = $request->input('content');
		$note->type = $request->input('type');
        //$note->created_by = $request->input('created_by');
        $note->object_id = $request->input('object_id');
        $note->save();

        session(['activeTab' => 'notes']);
        $route_back = $request->get('route_back');
        if(isset($route_back)) return redirect($route_back);

        if(! $request->ajax()){
           return redirect()->route('cashflow.contract.show',0)->with('success', __('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>__('Saved Successfully'),'data'=>$category, 'table' => '#income_expense_categories_table']);
        }
        
   }
	

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        //header('Content-Type: text/html; charset=utf-8');
        $order = Order::find($id);
        if(!empty($order)){
            $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
            return view('cashflow::backend.order.edit', compact('id','order','GeneralWebmasterSections'));
        }else{
            return view('errors.404');
        }
        
    }

    

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $order = Order::find($id);
        $qrCode = QrCode::size(200)->generate($order->customer->import_platform_id);
        if(! $request->ajax()){
            return view('cashflow::backend.order.view',compact('order','id','GeneralWebmasterSections','qrCode'));
        }else{
            return view('cashflow::backend.order.modal.view',compact('order','id'));
        } 
        
    }
}