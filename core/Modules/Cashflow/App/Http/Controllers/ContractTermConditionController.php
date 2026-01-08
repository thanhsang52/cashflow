<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Cashflow\App\Models\ContractTermCondition;
use Modules\Cashflow\App\Models\Contract;
use Validator;
use DataTables;


class ContractTermConditionController extends Controller
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
	public function get_data_term_condition(Request $request){
        $term_conditions= get_option('term_conditions');
        if($term_conditions) $term_conditions = unserialize($term_conditions);
        $is_percentage = $request->get('is_percentage');
        $contract_term_id = $request->get('contract_term_id');
        //if($request->ajax()) {
            return view('cashflow::components.term-conditions',compact('term_conditions','is_percentage','contract_term_id'));
        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ContractTermCondition::where('id',$id);
        $model->delete();
        return response()->json(['result'=>'success','action'=>'delete','message'=>__('Deleted Successfully')]);
        
        //return redirect()->route('contract.index')->with('success',__('Deleted Successfully'));
    }
    public function destroy_by_contract_term($contract_term_id)
    {
        $model = ContractTermCondition::where('contract_term_id',$contract_term_id);
        $model->delete();
        return response()->json(['result'=>'success','action'=>'delete','message'=>__('Deleted Successfully')]);
        
        //return redirect()->route('contract.index')->with('success',__('Deleted Successfully'));
    }
}