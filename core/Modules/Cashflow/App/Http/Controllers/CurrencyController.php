<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Currency;
use App\Models\WebmasterSection;
use Validator;

class CurrencyController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
        
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $currencys = Currency::all()->sortByDesc("id");
        return view('cashflow::backend.currency.list',compact('currencys','GeneralWebmasterSections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.currency.create');
        }else{
           return view('backend.currency.modal.create');
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
			'base_currency' => '',
			'exchange_rate' => 'required|numeric',
			'status' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('currency.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        if($request->input('base_currency') == '1'){
			$currency = Currency::where('base_currency','1')->first();
			if($currency){
				$currency->base_currency = 0;
				$currency->save();
			}
		}

        $currency = new Currency();
        $currency->name = $request->input('name');
		$currency->base_currency = $request->input('base_currency');
		$currency->exchange_rate = $request->input('exchange_rate');
		$currency->status = $request->input('status');

        $currency->save();
		
		\Cache::forget('currency');

        //Prefix output
        $currency->base_currency = $currency->base_currency == 1 ? __('Yes') : __('No');
        $currency->status = status($currency->status);

        if(! $request->ajax()){
           return redirect()->route('currency.create')->with('success', __('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>__('Saved Successfully'),'data'=>$currency, 'table' => '#currency_table']);
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
        $currency = Currency::find($id);
        if(! $request->ajax()){
            return view('cashflow::backend.currency.view',compact('currency','id','GeneralWebmasterSections'));
        }else{
            return view('cashflow::backend.currency.modal.view',compact('currency','id'));
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
        $currency = Currency::find($id);
        if(! $request->ajax()){
            return view('cashflow::backend.currency.edit',compact('currency','id','GeneralWebmasterSections'));
        }else{
            return view('cashflow::backend.currency.modal.edit',compact('currency','id'));
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
			'base_currency' => '',
			'exchange_rate' => 'required|numeric',
			'status' => 'required',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('cashflow.currency.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        if($request->input('base_currency') == '1'){
			$base_currency = Currency::where('base_currency','1')->first();
			if($base_currency){
				$base_currency->base_currency = 0;
				$base_currency->save();
			}
		}		
		
        $currency = Currency::find($id);
		$currency->name = $request->input('name');
		$currency->base_currency = $request->input('base_currency');
		$currency->exchange_rate = $request->input('exchange_rate');
		$currency->status = $request->input('status');
	
        $currency->save();
		
		\Cache::forget('currency');

        //Prefix output
        $currency->base_currency = $currency->base_currency == 1 ? __('Yes') : __('No');
        $currency->status = status($currency->status);
		
		if(! $request->ajax()){
           return redirect()->route('cashflow.currency.index')->with('success', __('Updated Successfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>__('Updated Successfully'),'data'=>$currency, 'table' => '#currency_table']);
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
        $currency = Currency::find($id);
        $currency->delete();
        return redirect()->route('currency.index')->with('success',__('Deleted Successfully'));
    }
}