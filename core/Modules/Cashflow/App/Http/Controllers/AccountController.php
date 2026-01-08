<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Account;
use Validator;
use DataTables;
use App\Models\WebmasterSection;
use Auth;

class AccountController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
    }
	public function get_table_data(Request $request){
        $limit = $request->input('length');
        $start = $request->input('start');
        $dir = $request->input('order.0.dir');
        $order = $request->input('order.0.column');
        if ($order == "") {
            $order = "id";
        }

        //order, paginate
        if ($limit > 0) {
            $account = Account::with("currency")->select('cashflow_accounts.*')->offset($start)->limit($limit);
        }else{
            $account = Account::with("currency")->select('cashflow_accounts.*')->limit(1000000);
        }
        $total = $account->get()->count();
        $x = 0;
		return Datatables::eloquent($account)
                    ->addColumn('check', function ($account) {
                        return '<div class="row_checker"><label class="ui-check m-a-0">
                                        <input type="checkbox" name="ids[]" value="'.$account['id'].'"><i class="dark-white"></i>
                                        <input type="hidden" name="row_ids[]" value="'.$account['id'].'" class="form-control row_no">
                                    </label>
                                </div>';
                            
                        })
                    ->addColumn('options', function ($account) use($total,&$x) {
                            $x++;
                            return '
                            <div class="text-center">
                                <div class="dropdown ' . ((($x + 2) >= $total) ? "dropup" : "") . '">
                                    <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
                                    <div class="dropdown-menu pull-right">'.
                                    '<a class="dropdown-item " href="'.action('\Modules\Cashflow\App\Http\Controllers\AccountController@show', $account['id']) .'" target="_blank"><i class="material-icons"></i> Show</a>'.
                                    (@Auth::user()->permissionsGroup->edit_status?'<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\AccountController@edit', $account['id']) .'"><i class="material-icons"></i> Edit</a>':'').
                                    
                                    (@Auth::user()->permissionsGroup->delete_status?'<a class="dropdown-item text-danger" onclick="DeleteAccount(\''.$account['id'].'\')"><i class="material-icons"></i> Delete</a>':'').
                                    '</div>
                                </div>
                            </div>
                            ';
                    })
                    ->filter(function ($instance) use ($request) {
                        $search = $request->get('search');
                        if (!empty($search['value'])){
                            $instance->where('account_no', 'LIKE' ,  "%" .$search['value']."%" )->orWhere('name', 'LIKE', "%" .$search['value']."%");
                        }
                    })
                    ->setRowId(function ($account) {
                        return "row_".$account->id;
                    })
                    ->rawColumns(['check','name','account_no','openning_balance','contract_person','contact_email','options'])
                    ->make(true);							    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $accounts = Account::all()->sortByDesc("id");
        return view('cashflow::backend.account.list',compact('accounts','GeneralWebmasterSections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        if( ! $request->ajax()){
           return view('cashflow::backend.account.create',compact('GeneralWebmasterSections'));
        }else{
           return view('cashflow::backend.account.modal.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
        $validator = Validator::make($request->all(), [
            'name' => 'required',
			'currency_id' => 'required',
			'openning_balance' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('cashflow.accounts.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        
        $account = new Account();
        $account->name = $request->input('name');
		$account->account_no = $request->input('account_no');
		$account->currency_id = $request->input('currency_id');
		$account->openning_balance = $request->input('openning_balance');
		$account->contact_person = $request->input('contact_person');
		$account->contact_email = $request->input('contact_email');
		$account->note = $request->input('note');

        $account->save();
        $account->currency_id = $account->currency->name;
        $account->openning_balance = decimalPlace($account->openning_balance, $account->currency->name);

        if(! $request->ajax()){
           return redirect()->route('cashflow.accounts.create')->with('success', __('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>__('Saved Successfully'),'data'=>$account, 'table' => '#accounts_table']);
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
        $account = Account::find($id);
        if(! $request->ajax()){
            return view('cashflow::backend.account.view',compact('account','id','GeneralWebmasterSections'));
        }else{
            return view('cashflow::backend.account.modal.view',compact('account','id'));
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
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $account = Account::find($id);
        if(! $request->ajax()){
            return view('cashflow::backend.account.edit',compact('account','id','GeneralWebmasterSections'));
        }else{
            return view('cashflow::backend.account.modal.edit',compact('account','id'));
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
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'currency_id' => 'required',
			'openning_balance' => 'required|numeric',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('cashflow.accounts.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		
        $account = Account::find($id);
		$account->name = $request->input('name');
		$account->account_no = $request->input('account_no');
		$account->currency_id = $request->input('currency_id');
		$account->openning_balance = $request->input('openning_balance');
		$account->contact_person = $request->input('contact_person');
		$account->contact_email = $request->input('contact_email');
		$account->note = $request->input('note');
	
        $account->save();
        $account->currency_id = $account->currency->name;
        $account->openning_balance = decimalPlace($account->openning_balance, $account->currency->name);
		
		if(! $request->ajax()){
           return redirect()->route('cashflow.accounts.index')->with('success', __('Updated Successfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>__('Updated Successfully'),'data'=>$account, 'table' => '#accounts_table']);
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
        $account = Account::find($id);
        $account->delete();
        return redirect()->route('cashflow.accounts.index')->with('success',__('Deleted Successfully'));
    }
}