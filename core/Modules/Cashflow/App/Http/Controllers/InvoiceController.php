<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Note;
use Modules\Cashflow\App\Models\Invoice;
use App\Models\WebmasterSection;
use Validator;
use DataTables;
use Auth;
use \GuzzleHttp\Client;
class InvoiceController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
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
        return view('cashflow::backend.invoice.list',compact('GeneralWebmasterSections'));
    }
    public function get_table_data(Request $request){
		$limit = $request->input('length',config('smartend.backend_pagination'));
        $start = $request->input('start',0);
        $dir = $request->input('order.0.dir');
        $order = $request->input('order.0.column');
        if ($order == "") {
            $order = "invoice_id";
        }

		$invoices = Invoice::limit($limit);
        $total = $invoices->get()->count();
        $x = 0;
		return Datatables::eloquent($invoices)	
            ->addColumn('check', function ($invoice) {
                return '<div class="row_checker $i"><label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="'.$invoice['invoice_id'].'"><i class="dark-white"></i>
                                <input type="hidden" name="row_ids[]" value="'.$invoice['invoice_id'].'" class="form-control row_no">
                            </label>
                        </div>';
                    
                })
            ->addColumn('options', function ($invoice) use($total,&$x) {
                $x++;
                return '
                <div class="text-center">
                    <div class="dropdown ' . ((($x+2) >= $total) ? "dropup" : "") . '">
                        <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
                        <div class="dropdown-menu pull-right">'.
                        ($invoice->invoice_no?'<a class="dropdown-item $key" href="'.action('\Modules\Cashflow\App\Http\Controllers\InvoiceController@show', $invoice['invoice_id']) .'" target="_blank"><i class="material-icons"></i> View</a>':'').
                        (@Auth::user()->permissionsGroup->edit_status?'<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\InvoiceController@edit', $invoice['invoice_id']) .'"><i class="material-icons"></i> Edit</a>':'').
                        (@Auth::user()->permissionsGroup->delete_status?'<a class="dropdown-item text-danger" onclick="DeleteInvoice(\''.$invoice['invoice_id'].'\')"><i class="material-icons"></i> Delete</a>':'').
                        '</div>
                    </div>
                </div>
                ';
            
            })
            ->setRowId(function ($invoice) {
                return "row_".$invoice->invoice_id;
            })
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('invoice_id')) && $request->get('invoice_id') >0) {
                    $instance->where('invoice_id', $request->get('invoice_id'));
                }
                if (!empty($request->get('branch_no')) && $request->get('branch_no') != '0') {
                    $instance->where('branch_no', $request->get('branch_no'));
                }
                if (!empty($request->get('status'))) {
                    $instance->where('status', $request->get('status'));
                }
                if (!empty($request->get('invoice_template_type'))) {
                    $instance->where('invoice_template_type', $request->get('invoice_template_type'));
                }
                if (!empty($request->get('from_date'))) {
                    $instance->where('invoice_date', '>=' ,date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if (!empty($request->get('to_date'))) {
                    $instance->where('invoice_date', '<=' ,date('Y-m-d',strtotime($request->get('to_date'))));
                }
                $search = $request->get('search');
                if (!empty($search['value'])){
                    $instance->where('note', 'LIKE' ,  "%" . $search['value']. "%");
                }
            })
            ->rawColumns(['check','invoice_id','branch_no','invoice_date','buyerTaxCode','created_user','invoice_no','status','sum_amount_with_no_tax','sum_amount_with_tax','options'])
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
        $invoice = Invoice::where(['invoice_id' => $id])->first();
        if(!empty($invoice)){
            $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
            return view('cashflow::backend.invoice.edit', compact('id','invoice','GeneralWebmasterSections'));
        }else{
            return view('errors.404');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		//$invoice = Invoice::where(['invoice_id' => $id])->first();
        $invoice = Invoice::find($id);
        $invoice->branch_no = $request->input('branch_no');
        $invoice->buyerName = $request->input('buyer_name');
		$invoice->buyerLegalName = $request->input('buyerLegalName');
        $invoice->buyerEmail = $request->input('buyerEmail');
		$invoice->buyerAddressLine = $request->input('buyerAddressLine');
        $invoice->save();
        if(! $request->ajax()){
            if ($request->get('action') == 'save') {
                // Just save the record
                return redirect()->route('cashflow.invoice.edit',$id)->with('success', __('Updated Successfully'));
            } elseif ($request->get('action') == 'save_and_close') {
                // Save the record, and redirect to index
                return redirect()->route('cashflow.invoice.index')->with('success', __('Updated Successfully'));
            }
           
        }else{
		   //return response()->json(['result'=>'success','action'=>'update', 'message'=>__('Updated Successfully'),'data'=>$contract, 'table' => '#contracts_table']);
           return response()->json(array("stat" => "success", "msg" => __("backend.saveDone")));
        }
	    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        $invoice->delete();
        return redirect()->back()->with('success',__('Deleted Successfully'));
        
    }

    public function show($id)
    {
        $invoice = Invoice::where(['invoice_id' => $id])->first();

        // Validate if the invoice and required fields are present
        if (!$invoice || empty($invoice->invoice_no)) {
            return response()->json(['stat' => 'error', 'msg' => __('Invoice or Invoice No is missing')]);
        }

        $token = $this->getViettelInvoiceToken($invoice->sellerTaxCode);
        $client = new Client();

        $params['headers'] = [
            'Content-Type' => 'application/json',
            'Cookie' => 'access_token=' . $token,
        ];

        $params['json'] = [
            'fileType' => 'PDF',
            'templateCode' => $invoice->invoice_template,
            'supplierTaxCode' => $invoice->sellerTaxCode,
            'invoiceNo' => $invoice->invoice_no,
        ];

        $apiURL = env('VINVOICE_HOST', '') . 'services/einvoiceapplication/api/InvoiceAPI/InvoiceUtilsWS/getInvoiceRepresentationFile';

        try {
            $res = $client->post($apiURL, $params);
            $response = json_decode($res->getBody()->getContents());

            if (empty($response) || $response->errorCode != 200) {
                switch ($response->errorCode) {
                    case 400:
                        return response()->json(['stat' => 'error', 'msg' => __('Please publish the Viettel e-invoice firstly')]);
                    case 500:
                        return response()->json(['stat' => 'error', 'msg' => __('Error')]);
                    default:
                        return response()->json(['stat' => 'error', 'msg' => __('Unknown Error')]);
                }
            }

            // Decode and display PDF data
            $data = base64_decode($response->fileToBytes);
            header('Content-Type: application/pdf');
            echo $data;
        } catch (\Exception $e) {
            // Log and cache the exception
            \Log::error('API Error: ' . $e->getMessage());

            $cacheKey = 'api_error_' . $id;
            \Cache::put($cacheKey, [
                'message' => $e->getMessage(),
                'url' => $apiURL,
                'params' => $params,
                'time' => now(),
            ], now()->addMinutes(30)); // Cache for 30 minutes

            // Return a user-friendly response
            return response()->json(['stat' => 'error', 'msg' => __('An error occurred while processing your request.')]);
        }

        return false;
    }

    protected function getViettelInvoiceToken($sellerTaxCode){
        $client = new Client();
        $params['headers'] = ['Content-Type' => 'application/json'];
        $credential = [
            "username"=> $sellerTaxCode.'_admin',
            "password"=>"Abcdmedica@2030"
        ];
        if($sellerTaxCode == '0302209992' ){
            $credential = [
              "username"=>"0302209992",
              "password"=>"Abcd@2022"
            ];	
        }
        $params['json'] = $credential;
        	
        $apiURL = env('VINVOICE_HOST', '').'auth/login';
        try{
            $res = $client->post($apiURL, $params);
            $response = json_decode($res->getBody()->getContents());
            if(empty($response) || isset($response->errorCode)){
                return false;
            }
            return $response->access_token;
        }catch(Exception $e){
            return false;
        }
    }
}