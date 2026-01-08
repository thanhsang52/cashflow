<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Transaction;
use Validator;
use Illuminate\Validation\Rule;
use DataTables;
use App\Models\WebmasterSection;
class ExpenseController extends Controller
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
        return view('cashflow::backend.expense.list',compact('GeneralWebmasterSections'));
	}
	
	public function get_table_data(){
		
		$currency = currency();

		$transactions = Transaction::with("account")
									->with("expense_type")
									->select('transactions.*')
									->where("transactions.dr_cr","dr")
									->orderBy("transactions.id","desc");

		return Datatables::eloquent($transactions)
						->editColumn('amount', function ($trans) {
							$currency = $trans->account->currency->name;	
							return "<span class='float-right'>".decimalPlace($trans->amount, $currency)."</span>";
						})
						->editColumn('trans_date', function ($trans) {
							return $trans->paid_at;
						})
						->addColumn('action', function ($trans) {
							if($trans->category_id != 1){
								return '<form action="'.action('ExpenseController@destroy', $trans['id']).'" class="text-center" method="post">'
								.'<a href="'.action('ExpenseController@edit', $trans['id']).'" data-title="'.__('Update Income') .'" class="btn btn-warning btn-xs ajax-modal"><i class="ti-pencil"></i></a>&nbsp;'
								.'<a href="'.action('ExpenseController@show', $trans['id']).'" data-title="'.__('View Income Details') .'" class="btn btn-primary btn-xs ajax-modal"><i class="ti-eye"></i></a>&nbsp;'
								.csrf_field()
								.'<input name="_method" type="hidden" value="DELETE">'
								.'<button class="btn btn-danger btn-xs btn-remove" type="submit"><i class="ti-eraser"></i></button>'
								.'</form>';
							}else{
								return '<div class="text-center"><a href="'.action('ExpenseController@show', $trans['id']).'" data-title="'.__('View Details') .'" class="btn btn-primary btn-xs ajax-modal"><i class="ti-eye"></i></a></div>';
							}
						})
						->setRowId(function ($trans) {
							return "row_".$trans->id;
						})
						->rawColumns(['amount','base_amount','status','action'])
						->make(true);							    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('cashflow::backend.expense.create');
		}else{
           return view('cashflow::backend.expense.modal.create');
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
			'account_id' => 'required',
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
				return redirect('income/create')
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
		$transaction->account_id = $request->input('account_id');
		$transaction->category_id = $request->input('category_id');
		$transaction->type = 'expense';
		$transaction->dr_cr = 'dr';
		$transaction->amount = $request->input('amount');
		$transaction->currency_rate = $transaction->account->currency->exchange_rate;
		$transaction->vendor_id = $request->input('vendor_id');
		$transaction->payment_method_id = $request->input('payment_method_id');
		$transaction->reference = $request->input('reference');
		$transaction->note = $request->input('note');
		$transaction->attachment = $attachment;
		
        $transaction->save();
	
        
		if(! $request->ajax()){
           return redirect()->route('cashflow.expense.index')->with('success', __('Saved Successfully'));
        }else{
		   return response()->json(['stat'=>'success', 'message'=>__('Saved Successfully'),'data'=>$transaction, 'table' => '#income-table']);
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
        $transaction = Transaction::find($id);
		if(! $request->ajax()){
		    return view('cashflow::backend.expense.view',compact('transaction','id'));
		}else{
			return view('cashflow::backend.expense.modal.view',compact('transaction','id'));
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
        $transaction = Transaction::where('id',$id)
						          ->where('category_id','!=',1)
						          ->first();
		if(! $request->ajax()){
		   return view('cashflow::backend.expense.edit',compact('transaction','id'));
		}else{
           return view('cashflow::backend.expense.modal.edit',compact('transaction','id'));
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
			'account_id' => 'required',
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
				return redirect()->route('income.edit', $id)
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
		$transaction->account_id = $request->input('account_id');
		$transaction->category_id = $request->input('category_id');
		$transaction->type = 'expense';
		$transaction->dr_cr = 'dr';
		$transaction->amount = $request->input('amount');
		$transaction->vendor_id = $request->input('vendor_id');
		$transaction->payment_method_id = $request->input('payment_method_id');
		$transaction->reference = $request->input('reference');
		$transaction->note = $request->input('note');
		if($request->hasfile('attachment')){
			$transaction->attachment = $attachment;
		}

		$transaction->save();
	     
		
		if(! $request->ajax()){
           return redirect()->route('cashflow.expense.index')->with('success', __('Updated Successfully'));
        }else{
		   return response()->json(['result'=>'success', 'message'=>__('Updated Successfully'),'data'=>$transaction, 'table' => '#income-table']);
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
        	return redirect()->route('cashflow.expense.index')->with('success',__('Removed Successfully'));
		}
       
    }
}
