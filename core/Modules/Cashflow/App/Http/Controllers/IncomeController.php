<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Transaction;
use Modules\Cashflow\App\Models\TransactionHistory;
use Modules\Cashflow\App\Models\ContractTerm;
use Validator;
use Illuminate\Validation\Rule;
use DataTables;
use Modules\Cashflow\App\Services\TransactionService;
use App\Models\WebmasterSection;
use Auth;
class IncomeController extends Controller
{
	
	public function __construct()
    {
		date_default_timezone_set(get_option('timezone','Asia/Dhaka'));	
	}	

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
		$contractTerms =  ContractTerm::all();

		//Create an array to store our grouped rows
		$contractTermGrouped = array();
		foreach($contractTerms as $key => $contract_term){
			$contract_term->load('contract');
			if(!$contract_term->hasContract()) continue;
			$contact_name= $contract_term->contract->code;
			if(!array_key_exists($contact_name, $contractTermGrouped)){
				$contractTermGrouped[$contact_name] = array();
			}

			$contractTermGrouped[$contact_name][] = array(
				$contract_term->id,
				$contract_term->term->name
			);
		}
        return view('cashflow::backend.income.list',compact('contractTermGrouped','GeneralWebmasterSections'));
	}
	
	public function get_table_data(Request $request){
		
		$currency = currency();

		$transactions = Transaction::with("income_type")
									->with("created_by")
									->with("contract_term")
									->select('cashflow_transactions.*')
									->where("cashflow_transactions.dr_cr","cr")
									->where("cashflow_transactions.status","!=","trashed")
									->limit(10000000) //add limit to fix bug query
									->orderBy("cashflow_transactions.trans_date","desc");

		return Datatables::eloquent($transactions)
						->editColumn('amount', function ($trans) {
							$currency = !empty($trans->account->currency->name)?$trans->account->currency->name:'VND';	
							return "<span class='float-right'>".decimalPlace($trans->amount, $currency)."</span>";
						})
						// ->addColumn('paid_at', function ($data) {
						// 	return $data->paid_at;
						// })
						->addColumn('check', function ($trans) {
							return '<div class="row_checker"><label class="ui-check m-a-0">
											<input type="checkbox" name="ids[]" value="'.$trans['id'].'"><i class="dark-white"></i>
													<input type="hidden" name="row_ids[]" value="'.$trans['id'].'" class="form-control row_no">
										</label>
									</div>';
								
							})
						->addColumn('options', function ($trans) {
								$trashOption = ($trans->status=='pending' && @Auth::user()->permissionsGroup->delete_status)?'<a class="dropdown-item text-danger" onclick="DeleteIncome(\''.$trans['id'].'\')"><i class="material-icons"></i> Trash</a>':'';
								return '
								
								<div class="text-center">
									<div class="dropup">
										<button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
										<div class="dropdown-menu pull-right">
										<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\IncomeController@show', $trans['id']) .'" target="_blank"><i class="material-icons"></i> Show</a>'.
										(@Auth::user()->permissionsGroup->edit_status?'<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\IncomeController@edit', $trans['id']) .'"><i class="material-icons"></i> Edit</a>':'').
										
										$trashOption.
										'</div>
									</div>
								</div>
								';
							
						})
						/*->addColumn('action', function ($trans) {
							if($trans->category_id != 1){
								return '<form action="'.action('\Modules\Cashflow\App\Http\Controllers\IncomeController@destroy', $trans['id']).'" class="text-center" method="post">'
								.'<a href="'.action('\Modules\Cashflow\App\Http\Controllers\IncomeController@edit', $trans['id']).'" data-title="'.__('Update Income') .'" class="btn btn-warning btn-xs"><i class="ti-pencil"></i></a>&nbsp;'
								.'<a href="'.action('\Modules\Cashflow\App\Http\Controllers\IncomeController@show', $trans['id']).'" data-title="'.__('View Income Details') .'" class="btn btn-primary btn-xs ajax-modal"><i class="ti-eye"></i></a>&nbsp;'
								.csrf_field()
								.'<input name="_method" type="hidden" value="DELETE">'
								.'<button class="btn btn-danger btn-xs btn-remove" type="submit"><i class="ti-eraser"></i></button>'
								.'</form>';
							}else{
								return '<div class="text-center"><a href="'.action('\Modules\Cashflow\App\Http\Controllers\IncomeController@show', $trans['id']).'" data-title="'.__('View Details') .'" class="btn btn-primary btn-xs ajax-modal"><i class="ti-eye"></i></a></div>';

							}
						})*/
						->setRowId(function ($trans) {
							
							return "row_".$trans->id;
						})
						->filter(function ($instance) use ($request) {
							if (!empty($request->get('contract_term_id')) && $request->get('contract_term_id') != '0') {
								$instance->whereHas('contract_term', function ($query) use ($request) {
									$query->where('id', $request->get('contract_term_id'));
								});
								
                            }
                            if (!empty($request->get('status'))) {
                                $instance->where('status', $request->get('status'));
                            }
                            $search = $request->get('search');
                            if (!empty($search['value'])){
                                $instance->where('note', 'LIKE' ,  "%" . $search['value']. "%");
                            }
                        })
					
						->rawColumns(['check','trans_date','contract_term','status','amount','created_by','options'])
						->make(true);							    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
		//Create an array to store our grouped rows
		$contractTerms =  ContractTerm::all();
		$contractTermGrouped = array();
		foreach($contractTerms as $key => $contract_term){
			$contact_name= $contract_term->contract->display_name;
			if(!array_key_exists($contact_name, $contractTermGrouped)){
				$contractTermGrouped[$contact_name] = array();
			}

			$contractTermGrouped[$contact_name][] = array(
				$contract_term->id,
				$contract_term->term->display_name
			);
		}
		if( ! $request->ajax()){
		   return view('cashflow::backend.income.create',compact('contractTermGrouped','GeneralWebmasterSections'));
		}else{
           return view('backend.income.modal.create',compact('contractTermGrouped'));
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
			'trans_date' => 'required',
			//'account_id' => 'required',
			'category_id' => 'required',
			'amount' => 'required|numeric',
			'payment_method_id' => 'required',
			'reference' => 'nullable|max:50',
			'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['stat'=>'error','msg'=>$validator->errors()->all()]);
			}else{
				return redirect()
							->route('cashflow.income.create')
							->withErrors($validator)
							->withInput();
			}			
		}

		$attachment = '';
		if($request->hasfile('attachment')){
		   $file = $request->file('attachment');
		   $attachment = time().$file->getClientOriginalName();
		   $file->move(public_path()."/uploads/transactions/", $attachment);
		}

		
        $transaction = new Transaction();
	    $transaction->trans_date = $request->input('trans_date');
		//$transaction->account_id = $request->input('account_id');
		$transaction->category_id = $request->input('category_id');
		$transaction->contract_term_id = $request->input('contract_term_id');
		$transaction->type = 'income';
		$transaction->dr_cr = 'cr';
		$transaction->amount = $request->input('amount');
		$transaction->currency_rate = !empty($transaction->account->currency->exchange_rate)?$transaction->account->currency->exchange_rate:1;
		$transaction->status = 'pending';
		//$transaction->vendor_id = $request->input('vendor_id');
		$transaction->payment_method_id = $request->input('payment_method_id');
		$transaction->reference = $request->input('reference');
		$transaction->note = $request->input('note');
		$transaction->attachment = $attachment;
		
        $transaction->save();
	
		$history = new TransactionHistory();
		$history->transaction_id = $transaction->id;
		$history->log = "Created new transaction ".$transaction->type;
        $history->save();
		if(! $request->ajax()){
           return redirect()->route('cashflow::cashflow.income.index')->with('doneMessage', __('backend.addDone'));
        }else{
		   return response()->json(['stat'=>'success', 'doneMessage', __('backend.addDone'),'data'=>$transaction, 'table' => '#income-table']);
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
        $transaction = Transaction::find($id);
		if(! $request->ajax()){
		    return view('cashflow::backend.income.view',compact('transaction','id','GeneralWebmasterSections'));
		}else{
			return view('cashflow::backend.income.modal.view',compact('transaction','id'));
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
        $transaction = Transaction::where('id',$id)
						          ->where('category_id','!=',1)
						          ->first();
		$contractTerms =  ContractTerm::all();

		//Create an array to store our grouped rows
		$contractTermGrouped = array();
		foreach($contractTerms as $key => $contract_term){
			$contact_name= $contract_term->contract->display_name;
			if(!array_key_exists($contact_name, $contractTermGrouped)){
				$contractTermGrouped[$contact_name] = array();
			}

			$contractTermGrouped[$contact_name][] = array(
				$contract_term->id,
				$contract_term->term->display_name
			);
		}

		if(! $request->ajax()){
		   return view('cashflow::backend.income.edit',compact('transaction','id','contractTermGrouped','GeneralWebmasterSections'));
		}else{
           return view('cashflow::backend.income.modal.edit',compact('transaction','id','contractTermGrouped'));
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
			'trans_date' => 'required',
			//'account_id' => 'required',
			'category_id' => 'required',
			'amount' => 'required|numeric',
			'payment_method_id' => 'required',
			'reference' => 'nullable|max:50',
			'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('cashflow.income.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}

		$attachment = "";
        if($request->hasfile('attachment'))
		{
		  $file = $request->file('attachment');
		  $attachment = time().$file->getClientOriginalName();
		  $file->move(public_path()."/uploads/transactions/", $attachment);
		}
		

		$transaction = Transaction::where('id',$id)
						          ->where('category_id','!=',1)
						          ->first();
		$previous_amount = $transaction->amount;
		$transaction->trans_date = $request->input('trans_date');
		//$transaction->account_id = $request->input('account_id');
		$transaction->category_id = $request->input('category_id');
		$transaction->contract_term_id = $request->input('contract_term_id');
		$transaction->type = 'income';
		$transaction->dr_cr = 'cr';
		$transaction->amount = $request->input('amount');
		//$transaction->customer_id = $request->input('customer_id');
		//$transaction->vendor_id = $request->input('vendor_id');
		$transaction->payment_method_id = $request->input('payment_method_id');
		$transaction->reference = $request->input('reference');
		$transaction->note = $request->input('note');
		if($request->hasfile('attachment')){
			$transaction->attachment = $attachment;
		}

		$transaction->save();
	     
		$history = new TransactionHistory();
		$history->transaction_id = $transaction->id;
		$history->log = "Updated transaction ".$transaction->reference;
		$history->save();

		if(! $request->ajax()){
           return redirect()->route('cashflow.income.index')->with('doneMessage', __('backend.updateDone'));
        }else{
		   return response()->json(['stat'=>'success', 'doneMessage', __('backend.saveDone'),'data'=>$transaction, 'table' => '#income-table']);
		}
	    
    }
	
	public function update_status(Request $request){
		$id = $request->input('id');
		if($id>0){
			$transaction = Transaction::where('id',$id)
									->first();
			$transaction->status = $request->input('status');
			$transaction->save();
		}
		if(! $request->ajax()){
			return redirect()->route('cashflow.income.index')->with('msg', __('backend.saveDone'));
		}else{
			return response()->json(['stat'=>'success', 'msg'=> __('backend.saveDone'), 'table' => '#income-table']);
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
		$transaction = Transaction::find($id);
		if($transaction->category_id != 1){
			$transaction->delete();
			$history = new TransactionHistory();
			$history->transaction_id = $transaction->id;
			$history->log = "Deleted transaction ".$transaction->reference;
			$history->save();
        	return redirect()->route('cashflow.income.index')->with('doneMessage', __('backend.updateDone'));
		}
    }
	public function calculate_amount($id){
		$transaction = Transaction::find($id);
		$service = new TransactionService($transaction);
		$data['contract_type'] = $service->getContractType();
		$data['amount'] = $service->calculateAmount();
		return view('cashflow::backend.income.modal.calculate',compact('data'));
	}
}
