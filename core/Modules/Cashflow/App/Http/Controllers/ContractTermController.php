<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Cashflow\App\Models\ContractTerm;
use Modules\Cashflow\App\Models\Contract;
use Validator;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ContractTermController extends Controller
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
        $terms = ContractTerm::all()->sortByDesc("id");
        return view('cashflow::backend.contract_term.list',compact('terms'));
    }

    

    public function get_table_data(Request $request){
		
		

		$terms = ContractTerm::with("term")
                                    ->with("contract")
                                    ->limit(1000000)
									->orderBy("id","desc");

		return Datatables::eloquent($terms)
                        ->setRowClass(function ($term) use ($request) {
                            $contractTermID = $request->get('contract_term_id',0);
                            return $term->id== $contractTermID ? 'selected' : '';
                        })
                        ->filter(function ($instance) use ($request) {
                            if (!empty($request->get('contract_id')) && $request->get('contract_id') != '0') {
                                $instance->where('contract_id', $request->get('contract_id'));
                            }
                            
                            $search = $request->get('search');
                            if (!empty($search['value'])){
                                $instance->orWhereHas('term', function ($query) use ($request,$search) {
                                    $query->where('name', 'LIKE' ,'%' .$search['value']. '%')->orWhere('code', 'LIKE', $search['value']);
                                });
                                $instance->orWhereHas('contract', function ($query) use ($request,$search) {
                                    $query->where('name', 'LIKE' ,'%' .$search['value']. '%')->orWhere('code', 'LIKE', $search['value']);
                                });
                            }
                        })
						->setRowId(function ($term) {
							return "row_".$term->id;
						})
						->rawColumns(['contract','code','name','category','action'])
						->make(true);							    
    }
    
}