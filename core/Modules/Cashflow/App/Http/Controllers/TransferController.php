<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Transaction;
use Modules\Cashflow\App\Models\Transfer;
use Validator;
use DB;

class TransferController extends Controller
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
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = Transfer::all()->sortByDesc("id");
        return view('backend.transfer.list',compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.transfer.create');
        }else{
           return view('backend.transfer.modal.create');
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
            'from_account' => 'required',
            'to_account' => 'required|different:from_account',
            'amount' => 'required|numeric',
            'payment_method_id' => 'required',
            'reference' => 'nullable|max:50',
            'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('transfers.create')
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

        DB::beginTransaction();

        //Create Expense
        $expense = new Transaction();
        $expense->trans_date = $request->input('trans_date');
        $expense->account_id = $request->input('from_account');
        $expense->category_id = 1;
        $expense->type = 'expense';
        $expense->dr_cr = 'dr';
        $expense->amount = $request->input('amount');
        $expense->currency_rate = $expense->account->currency->exchange_rate;
        $expense->payment_method_id = $request->input('payment_method_id');
        $expense->reference = $request->input('reference');
        $expense->note = $request->input('note');
        $expense->attachment = $attachment;
        
        $expense->save();

        //Create Income
        $income = new Transaction();
        $income->trans_date = $request->input('trans_date');
        $income->account_id = $request->input('to_account');
        $income->category_id = 1;
        $income->type = 'income';
        $income->dr_cr = 'cr';
        $income->amount = convert_currency($expense->account->currency_id, $income->account->currency_id, $expense->amount);
        $income->currency_rate = $income->account->currency->exchange_rate;
        $income->payment_method_id = $request->input('payment_method_id');
        $income->reference = $request->input('reference');
        $income->note = $request->input('note');
        $income->attachment = $attachment;
        
        $income->save();

        $transfer = new Transfer();
        $transfer->expense_transaction_id = $expense->id;
		$transfer->income_transaction_id = $income->id;;

        $transfer->save();

        DB::commit();

        if(! $request->ajax()){
           return redirect()->route('transfers.index')->with('success', _lang('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Successfully'),'data'=>$transfer, 'table' => '#transfers_table']);
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
        $transfer = Transfer::find($id);
        if(! $request->ajax()){
            return view('backend.transfer.edit',compact('transfer','id'));
        }else{
            return view('backend.transfer.modal.edit',compact('transfer','id'));
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
            'from_account' => 'required',
            'to_account' => 'required|different:from_account',
            'amount' => 'required|numeric',
            'payment_method_id' => 'required',
            'reference' => 'nullable|max:50',
            'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
        ]);


		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('transfers.edit', $id)
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
        
        		
        DB::beginTransaction();

        $transfer = Transfer::find($id);

        //Create Expense
        $expense = Transaction::find($transfer->expense_transaction_id);
        $expense->trans_date = $request->input('trans_date');
        $expense->account_id = $request->input('from_account');
        $expense->category_id = 1;
        $expense->type = 'expense';
        $expense->dr_cr = 'dr';
        $expense->amount = $request->input('amount');
        $expense->currency_rate = $expense->account->currency->exchange_rate;
        $expense->payment_method_id = $request->input('payment_method_id');
        $expense->reference = $request->input('reference');
        $expense->note = $request->input('note');
        if($request->hasfile('attachment')){
            $expense->attachment = $attachment;
        }
        
        $expense->save();

        //Create Income
        $income = Transaction::find($transfer->income_transaction_id);
        $income->trans_date = $request->input('trans_date');
        $income->account_id = $request->input('to_account');
        $income->category_id = 1;
        $income->type = 'income';
        $income->dr_cr = 'cr';
        $income->amount = convert_currency($expense->account->currency_id, $income->account->currency_id, $expense->amount);
        $income->currency_rate = $income->account->currency->exchange_rate;
        $income->payment_method_id = $request->input('payment_method_id');
        $income->reference = $request->input('reference');
        $income->note = $request->input('note');
        if($request->hasfile('attachment')){
            $income->attachment = $attachment;
        }
        
        $income->save();

        DB::commit();
		
		if(! $request->ajax()){
           return redirect()->route('transfers.index')->with('success', _lang('Updated Successfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Successfully'),'data'=>$transfer, 'table' => '#transfers_table']);
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
        DB::beginTransaction();

        $transfer = Transfer::find($id);
        $expense = Transaction::find($transfer->expense_transaction_id);
        $expense->delete();

        $income = Transaction::find($transfer->income_transaction_id);
        $income->delete();

        $transfer->delete();

        DB::commit();

        return redirect()->route('transfers.index')->with('success',_lang('Deleted Successfully'));
    }
}