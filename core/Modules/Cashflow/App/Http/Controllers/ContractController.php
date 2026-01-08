<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Cashflow\App\Models\Contract;
use Modules\Cashflow\App\Models\Term;
use Modules\Cashflow\App\Models\ContractTerm;
use Modules\Cashflow\App\Models\ContractTermLevel;
use Modules\Cashflow\App\Models\ContractTermCondition;
use Modules\Cashflow\App\Models\TransactionSchedule;
use Validator;
use DataTables;
use App\Models\WebmasterSection;
use App\Models\WebmasterSectionField;
use App\Models\Topic;
use App\Models\TopicField;
use Modules\Cashflow\App\Models\Setting;
use Modules\Cashflow\App\Models\Note;
use Auth;

class ContractController extends Controller
{
    private $uploadPath = "uploads/contracts/";
	
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
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        //$contracts = Contract::all()->sortByDesc("id");
        return view('cashflow::backend.contract.list',compact('GeneralWebmasterSections'));
    }

    public function get_table_data(Request $request){
		$limit = $request->get('length',config('smartend.backend_pagination'));
        $start = $request->get('start',0);

		$contracts = Contract::with("vendor")
                        ->select('cashflow_contracts.*')
                        //->offset($start)
                        ->limit($limit);
        $total = $contracts->get()->count();
        $x = 0;
		return Datatables::eloquent($contracts)	
            ->addColumn('check', function ($contract) {
                return '<div class="row_checker $i"><label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="'.$contract['id'].'"><i class="dark-white"></i>
                                <input type="hidden" name="row_ids[]" value="'.$contract['id'].'" class="form-control row_no">
                            </label>
                        </div>';
                    
                })
            ->addColumn('options', function ($contract) use($total,&$x) {
                $x++;
                return '
                <div class="text-center">
                    <div class="dropdown ' . ((($x+2) >= $total) ? "dropup" : "") . '">
                        <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
                        <div class="dropdown-menu pull-right">
                        <a class="dropdown-item $key" href="'.action('\Modules\Cashflow\App\Http\Controllers\ContractController@show', $contract['id']) .'" target="_blank"><i class="material-icons"></i> Show</a>'.
                        (@Auth::user()->permissionsGroup->edit_status?'<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\ContractController@edit', $contract['id']) .'"><i class="material-icons"></i> Edit</a>':'').
                        '<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\ContractController@duplicate', $contract['id']) .'"><i class="material-icons"></i> Clone</a>'.
                        (@Auth::user()->permissionsGroup->delete_status?'<a class="dropdown-item text-danger" onclick="DeleteContract(\''.$contract['id'].'\')"><i class="material-icons"></i> Delete</a>':'').
                        '</div>
                    </div>
                </div>
                ';
            
            })
            ->setRowId(function ($contract) {
                return "row_".$contract->id;
            })
            ->rawColumns(['check','code','name','vendor','reference','effect_from','effect_to','options'])
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
        $contract_types= get_option('contract_types' );
        $terms = Term::where('status',1)->get();
        if($contract_types) $contract_types = unserialize($contract_types);
        if( ! $request->ajax()){
           return view('cashflow::backend.contract.create',compact('contract_types','terms','GeneralWebmasterSections'));
        }else{
           return view('cashflow::backend.contract.modal.create',compact('contract_types','terms'));
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
			//'number' => 'required|unique:contracts|max:10',
            'code' => 'required|unique:contracts,code',
            'vendor_id' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('contract.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
        $attachment = '';
		if($request->hasfile('attachment')){
		   $file = $request->file('attachment');
		   $attachment = time().$file->getClientOriginalName();
		   $file->move($this->getUploadPath(), $attachment);
		}
        
        $contract = new Contract();
        $contract->name = $request->input('name');
		//$contract->number = $request->input('number');
		$contract->code = $request->input('code');
		$contract->type = $request->input('type');
		$contract->vendor_id = $request->input('vendor_id');
		$contract->effect_from = $request->input('effect_from');
		$contract->effect_to = $request->input('effect_to');
		$contract->base_value = $request->input('base_value');
		$contract->included_vat = !empty($request->input('included_vat'))?1:0;
		$contract->attachment = $attachment;
		$contract->reference = $request->input('reference');
		$contract->note = $request->input('note');
        $contract->payment_term = $request->input('payment_term');
		$contract->new_store_sku_payment_days = $request->input('new_store_sku_payment_days');
        $contract->return = !empty($request->input('return'))?1:0;
        $contract->near_expiry_product = $request->input('near_expiry_product');
        $contract->stock_slow_selling = $request->input('stock_slow_selling');
        $contract->discontinued_items = $request->input('discontinued_items');
        $contract->customer_return = $request->input('customer_return');
        $contract->minimun_shelf_life = $request->input('minimun_shelf_life');
        $contract->cost_price_changes = $request->input('cost_price_changes');

        $contract->save();
        $selectedTermIDs = $request->input('termIDs');
        $params = $request->input('params');
        $parseParam = [];
        foreach((array)$selectedTermIDs as $termID){
            $row = array();
            foreach($params as $key=> $param){
                $row = array_merge($row,array($key=> $param[$termID]));
            }
            //key array
            $row = array_merge($row,array('term_id'=> $termID,'contract_id'=>$contract->id));
            $parseParam[] = $row;
        }
        if( is_array($parseParam) && count($parseParam)>0)
            try{
                ContractTerm::upsert($parseParam,['contract_id','term_id']);
            }catch (Exception $e){
                Log::debug($e->getMessage());
            }

        if(! $request->ajax()){
           return redirect()->route('cashflow.contract.index')->with('success', __('Saved Successfully'));
        }else{
           return response()->json(['stat'=>'success','msg'=> __("backend.addDone"),'action'=>'store','message'=>__('Saved Successfully'),'data'=>$contract, 'table' => '#contracts_table']);
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
        $contract = Contract::find($id);
        $notes = Note::where(['type'=>'contract','object_id'=>$id])->get();
        if(! $request->ajax()){
            return view('cashflow::backend.contract.view',compact('contract','id','GeneralWebmasterSections','notes'));
        }else{
            return view('cashflow::backend.contract.modal.view',compact('contract','id'));
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
        $contract_link_section_id = Setting::where('name', 'contract_link_section_id')->first()->value;
        $WebmasterSection = WebmasterSection::find($contract_link_section_id);
        $Topic= NULL;
        $contract = Contract::find($id);
        $contract_types= get_option('contract_types');
        if(!empty($contract->topic_id))
            $Topic = Topic::find($contract->topic_id);
        if($contract_types) $contract_types = unserialize($contract_types);
        if(! $request->ajax()){
            return view('cashflow::backend.contract.edit',compact('contract','id','contract_types','GeneralWebmasterSections','WebmasterSection','Topic'));
        }else{
            return view('cashflow::backend.contract.modal.edit',compact('contract','id','contract_types'));
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
			'effect_from' => 'required',
			'effect_to' => 'required',
			'vendor_id' => 'required',
			//'base_value' => 'required|numeric',
			//'number' => 'required',
            'code' => 'required|unique:cashflow_contracts,code,'.$id,
            'type' => 'required',
            //'included_vat' => 'required',
			'reference' => 'nullable|max:50',
			'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('cashflow.contract.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
        if($request->hasfile('attachment'))
		{
		  $file = $request->file('attachment');
		  $attachment = time().$file->getClientOriginalName();
		  $file->move($this->getUploadPath(), $attachment);
		}	
		
        $contract = Contract::where('id',$id)
						          //->where('category_id','!=',1)
						          ->first();
        $contract->name = $request->input('name');
		//$contract->number = $request->input('number');
		$contract->code = $request->input('code');
		$contract->type = $request->input('type');
		$contract->vendor_id = $request->input('vendor_id');
		$contract->effect_from = $request->input('effect_from');
		$contract->effect_to = $request->input('effect_to');
		$contract->base_value = $request->input('base_value');
		$contract->included_vat = !empty($request->input('included_vat'))?1:0;
        
		if($request->hasfile('attachment')){
			$contract->attachment = $attachment;
		}
		$contract->reference = $request->input('reference');
		$contract->note = $request->input('note');
        $contract->payment_term = $request->input('payment_term');
		$contract->new_store_sku_payment_days = $request->input('new_store_sku_payment_days');
        $contract->included_vat = !empty($request->input('included_vat'))?1:0;
        $contract->return = !empty($request->input('return'))?1:0;
        $contract->near_expiry_product = $request->input('near_expiry_product');
        $contract->stock_slow_selling = $request->input('stock_slow_selling');
        $contract->discontinued_items = $request->input('discontinued_items');
        $contract->customer_return = $request->input('customer_return');
        $contract->minimun_shelf_life = $request->input('minimun_shelf_life');
        $contract->cost_price_changes = $request->input('cost_price_changes');
        $contract->topic_id = $request->input('topic_id');
        $contract->save();

        $this->handleSetting($request,$id);
        //$this->handleContractOption($request);
		if(! $request->ajax()){
            if ($request->get('action') == 'save') {
                // Just save the record
                return redirect()->route('cashflow.contract.edit',$id)->with('success', __('Updated Successfully'));
            } elseif ($request->get('action') == 'save_and_close') {
                // Save the record, and redirect to index
                return redirect()->route('cashflow.contract.index')->with('success', __('Updated Successfully'));
            }
           
        }else{
		   //return response()->json(['result'=>'success','action'=>'update', 'message'=>__('Updated Successfully'),'data'=>$contract, 'table' => '#contracts_table']);
           return response()->json(array("stat" => "success", "msg" => __("backend.saveDone")));
        }
	    
    }
    public function handleContractOption(Request $request)
    {		
	    $settings = $request->get('settings');
        foreach($settings as $key => $value){
            $data = array();
            $data['value'] = is_array($value) ? serialize($value) : $value; 
            $data['updated_at'] = Carbon::now();
            if(Setting::where('name', $key)->exists()){				
                Setting::where('name','=',$key)->update($data);			
            }else{
                $data['name'] = $key; 
                $data['created_at'] = Carbon::now();
                Setting::insert($data); 
            }

        } //End $_POST Loop
	}
    public function handleSetting(Request $request,$contract_id){
        //var_dump($contract_id);die;
        $selectedTermIDs = $request->input('termIDs');
        $params = $request->input('params');
        $parseParam = [];
        foreach($selectedTermIDs as $termID){
            $row = array();
            foreach($params as $key=> $param){
                if(isset($param[$termID])){
                    $temp = $param[$termID];
                    if($key=='term_value'){
                        $temp = str_replace(',','',$temp);
                    }
                    $row = array_merge($row,array($key=> $temp));
                }
            }
            $row = array_merge($row,array('term_id'=> $termID,'contract_id'=>$contract_id));
            if( is_array($row) && count($row)>0)
                try{
                    $contractTerm = ContractTerm::updateOrCreate(['contract_id'=>$contract_id,'term_id'=>$termID],$row);
                    if(isset($params['frequency_cycle'][$termID]) && $params['billing_frequency'][$termID]==1)
                        $this->handleScheduledTransaction($request);
                    else
                        TransactionSchedule::where('contract_term_id',$contractTerm->id)->delete();
                    if(isset($params['type'][$termID]) && $params['type'][$termID]==1)
                        $this->handleContractTermLevel($request);
                    else
                        ContractTermLevel::where('contract_term_id', $contractTerm->id)->delete();
                    if(isset($params['type'][$termID]) && $params['type'][$termID]==2)
                        $this->handleContractTermCondition($request,$contractTerm->id);
                    else
                        ContractTermCondition::where('contract_term_id', $contractTerm->id)->delete();
                }catch (Exception $e){
                    Log::debug($e->getMessage());
                }
        }
    }
    public function handleScheduledTransaction(Request $request){
        $scheduled_transaction_date = $request->input('scheduled_transaction_date');
        if($scheduled_transaction_date){
            foreach($scheduled_transaction_date as $contract_term_id=> $list_dates){
                TransactionSchedule::where('contract_term_id',$contract_term_id)->delete();
                //if(!$params['billing_frequency'][$term]) continue;
                $date_arr= array();
                $dates = explode(',', $list_dates);
                foreach($dates as $date)
                    if(trim($date))
                        $date_arr[]= ['contract_term_id' => $contract_term_id, 'transaction_date' => trim($date)];
                //$date_arr[]=['contract_term_id' => $contract_term_id, 'transaction_date' => $date];
                TransactionSchedule::upsert($date_arr,['contract_term_id','transaction_date']);
            }
            
        }
    }
    public function handleContractTermLevel(Request $request){
        $levels = $request->input('levels');
        $level_row=[];
        if($levels)
            foreach($levels as $contract_term_id=> $level){
                foreach($level['target'] as $i=> $target){
                    if($target && isset($level['value'][$i]))
                    {
                        $target = str_replace(',', '', $target);
                        $value = str_replace(',', '', $level['value'][$i]);
                        $level_row = array("target"=> $target, "value"=> $value, "contract_term_id" => $contract_term_id, "level" => $i+1);
                        ContractTermLevel::upsert($level_row,['contract_term_id','level']);
                    }
                }
                
            }
    }
    public function handleContractTermCondition(Request $request,$contract_term_id=0){
        $conditions = $request->input('conditions');
        if($conditions)
            foreach($conditions as $key=> $condition){
                if(!isset($condition['contract_term_id']) || $contract_term_id!=$condition['contract_term_id']) continue;
                if(isset($condition['discount']) && $condition['discount']>0)
                {
                    $condition_row=[];
                    foreach($condition['condition_id'] as $i=> $condition_id){
                        if($condition_id && isset($condition['condition_eval'][$i]))
                        {
                            //$target = str_replace(',', '', $target);
                            $condition_eval = str_replace(',', '', $condition['condition_eval'][$i]);
                            $condition_row[] = array("condition_id"=> $condition_id, "condition_eval"=> $condition_eval);
                            
                        }
                    }
                    $id = $key;
                    if(!is_numeric($key)) $id=0;
                    ContractTermCondition::updateOrCreate(['id'=>$id],['attributes'=>json_encode($condition_row),'contract_term_id'=>$contract_term_id,'discount'=>str_replace(",","", $condition['discount'])]);
                }
            }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if ($id > 0) {
            $contract = Contract::find($id);
            $contract->delete();
            if(! $request->ajax()){
                return redirect()->route('cashflow.contract.index')->with('success',__("backend.deleteDone"));
                //return response()->json(array("stat" => "success", "msg" => __("backend.deleteDone")));
            }else{
                return response()->json(['stat'=>'success','msg'=> __('backend.deleteDone')]);
            }
            //return redirect()->route('cashflow.term.index')->with('success',__('Deleted Successfully'));
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyTerm($id,$contract_id)
    {
        $contractTerm = ContractTerm::find($id);
        $contractTerm->delete();
        return redirect()->route('cashflow.contract.edit',$contract_id)->with('success',__('Deleted Successfully'));
    }
    public function destroyContractTerm($term_id,$contract_id)
    {
        $contractTerm = ContractTerm::where(['term_id'=>$term_id,'contract_id'=>$contract_id]);
        $contractTerm->delete();
        return redirect()->route('cashflow.contract.edit',$contract_id)->with('success',__('Deleted Successfully'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id)
    {
       
        $contract = Contract::with('contract_terms')->find($id);
        /*$newContract = $contract->replicate();*/
        $newContract = $contract->cloneWithTerms();
        $newContract->vendor_id = 0; // the new vendor_id
        $newContract->save();
        
        return redirect()->route('cashflow.contract.index')->with('success',__('Duplicated Successfully'));
    }

    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = Config::get('app.APP_URL') . $uploadPath;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sign(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
			'signature' => 'required',
			'acceptance_firstname' => 'required',
			'acceptance_lastname' => 'required',

		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('cashflow.contract.show', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $contract = Contract::find($id);
        $contract->signed = 1;
        $contract->signature = $request->input('signature');
        $contract->acceptance_firstname = $request->input('acceptance_firstname');
        $contract->acceptance_lastname = $request->input('acceptance_lastname');
        $contract->acceptance_date = date('Y-m-d H:i:s');
        $contract->acceptance_ip = $request->ip();;
        $contract->save();
        session(['activeTab' => 'sign']);
        $route_back = $request->get('route_back');
        if(isset($route_back)) return redirect($route_back);
        if(! $request->ajax()){
            return view('cashflow::backend.contract.view',compact('contract','id','GeneralWebmasterSections','notes'));
        }else{
            return view('cashflow::backend.contract.modal.view',compact('contract','id'));
        } 
        
    }
}