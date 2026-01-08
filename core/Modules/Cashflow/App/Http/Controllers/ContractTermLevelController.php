<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Cashflow\App\Models\ContractTermLevel;
use Modules\Cashflow\App\Models\Contract;
use Validator;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ContractTermLevelController extends Controller
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($contract_term_id)
    {
        $model = ContractTermLevel::where('contract_term_id',$contract_term_id);
        $model->delete();
        return response()->json(['result'=>'success','action'=>'delete','message'=>_lang('Deleted Successfully')]);
        
        //return redirect()->route('contract.index')->with('success',_lang('Deleted Successfully'));
    }
    public function destroy_level($contract_term_id,$level)
    {
        $model = ContractTermLevel::where('contract_term_id',$contract_term_id)->where('level',$level);
        $model->delete();
        return response()->json(['result'=>'success','action'=>'delete','message'=>_lang('Deleted Successfully')]);
        
        //return redirect()->route('contract.index')->with('success',_lang('Deleted Successfully'));
    }
}