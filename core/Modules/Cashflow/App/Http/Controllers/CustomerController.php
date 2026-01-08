<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Cashflow\App\Models\Customer;
use Validator;

class CustomerController extends Controller
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
        $customers = Customer::all()->sortByDesc("id");
        return view('backend.customer.list',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.customer.create');
        }else{
           return view('backend.customer.modal.create');
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
			'email' => 'required|email|unique:customers|max:255',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('customers.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        

        $customer = new Customer();
        $customer->name = $request->input('name');
		$customer->company_name = $request->input('company_name');
		$customer->email = $request->input('email');
		$customer->phone = $request->input('phone');
		$customer->country = $request->input('country');
		$customer->city = $request->input('city');
		$customer->state = $request->input('state');
		$customer->zip = $request->input('zip');
		$customer->address = $request->input('address');
		$customer->note = $request->input('note');

        $customer->save();

        if(! $request->ajax()){
           return redirect()->route('customers.create')->with('success', _lang('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Successfully'),'data'=>$customer, 'table' => '#customers_table']);
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
        $customer = Customer::find($id);
        if(! $request->ajax()){
            return view('backend.customer.view',compact('customer','id'));
        }else{
            return view('backend.customer.modal.view',compact('customer','id'));
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
        $customer = Customer::find($id);
        if(! $request->ajax()){
            return view('backend.customer.edit',compact('customer','id'));
        }else{
            return view('backend.customer.modal.edit',compact('customer','id'));
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
            'email' => [
                'required',
                'email',
                Rule::unique('customers')->ignore($id),
            ],
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('customers.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	

        $customer = Customer::find($id);
		$customer->name = $request->input('name');
		$customer->company_name = $request->input('company_name');
		$customer->email = $request->input('email');
		$customer->phone = $request->input('phone');
		$customer->country = $request->input('country');
		$customer->city = $request->input('city');
		$customer->state = $request->input('state');
		$customer->zip = $request->input('zip');
		$customer->address = $request->input('address');
		$customer->note = $request->input('note');
	
        $customer->save();
		
		if(! $request->ajax()){
           return redirect()->route('customers.index')->with('success', _lang('Updated Successfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Successfully'),'data'=>$customer, 'table' => '#customers_table']);
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
        $customer = Customer::find($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('success',_lang('Deleted Successfully'));
    }
}