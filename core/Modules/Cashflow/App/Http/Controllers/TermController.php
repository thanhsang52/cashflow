<?php

namespace Modules\Cashflow\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Cashflow\App\Models\Term;
use Modules\Cashflow\App\Models\TermImport;
use Modules\Cashflow\App\Models\Contract;
use Validator;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\WebmasterSection;
use Helper;
use Redirect;

class TermController extends Controller
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
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $terms = Term::all()->sortByDesc("id");
        return view('cashflow::backend.term.list',compact('terms','GeneralWebmasterSections'));
    }

    public function list_modal()
    {
        $terms = Term::all()->sortByDesc("id");
        return view('cashflow::backend.term.modal.list',compact('terms'));
    }

    public function get_table_data(Request $request){
		$limit = $request->input('length',30);
        $start = $request->input('start',0);
		//$currency = currency();

		$terms = Term::with("category")
                        ->select('cashflow_terms.*')
                        ->where("status","1")
                        ->offset($start)
                        ->limit($limit);
        $total = $terms->get()->count();
        $x = 0;
		return Datatables::eloquent($terms)
                    ->addColumn('check', function ($term) {
                        return '<div class="row_checker"><label class="ui-check m-a-0">
                                        <input type="checkbox" name="ids[]" value="'.$term['id'].'"><i class="dark-white"></i>
                                                <input type="hidden" name="row_ids[]" value="'.$term['id'].'" class="form-control row_no">
                                    </label>
                                </div>';
                            
                        })
                    ->addColumn('options', function ($term)  use($total,&$x){
                        $x++;
                        return '
                        
                        <div class="text-center">
                            <div class="dropdown ' . ((($x+2) >= $total) ? "dropup" : "") . '">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
                                <div class="dropdown-menu pull-right">
                                <a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\TermController@show', $term['id']) .'" target="_blank"><i class="material-icons"></i> Show</a>
                                <a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\TermController@edit', $term['id']) .'"><i class="material-icons"></i> Edit</a>
                                
                                <a class="dropdown-item text-danger" onclick="DeleteTerm(\''.$term['id'].'\')"><i class="material-icons"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                        ';
                        
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('category_id')) && $request->get('category_id') != '0') {
                            $instance->where('category_id', $request->get('category_id'));
                        }
                        $search = $request->get('search');
                        if (!empty($search['value'])){
                            $instance->where('code', 'LIKE' ,  "%" .$search['value']."%" )->orWhere('name', 'LIKE', "%" .$search['value']."%");
                        }
                    })
                    ->setRowId(function ($term) {
                        return "row_".$term->id;
                    })
                    ->rawColumns(['check','code','name','category','options'])
                    ->make(true);							    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $contracts = Contract::all()->sortByDesc("id");
        if( ! $request->ajax()){
           return view('cashflow::backend.term.create',compact('contracts'));
        }else{
           return view('cashflow::backend.term.modal.create',compact('contracts'));
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
            'code' => 'required|max:10',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                //return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
                $errors = $validator->errors()->all();
                $errors_msg = implode("\n",$errors);
                return response()->json(array("stat" => "error", "msg" => $errors_msg));
            }else{
                return redirect()->route('cashflow.term.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
        $attachment = '';
		if($request->hasfile('attachment')){
		   $file = $request->file('attachment');
		   $attachment = time().$file->getClientOriginalName();
		   $file->move(public_path()."/uploads/terms/", $attachment);
		}
        
        $term = new Term();
        $term->name = $request->input('name');
		$term->code = $request->input('code');
		//$term->type = $request->input('type');
		$term->category_id = $request->input('category_id');
		$term->credit_acc_no = $request->input('credit_acc_no');
		$term->dedit_acc_no = $request->input('dedit_acc_no');
        $term->created_user_id =  $request->user()->id;
		$term->attachment = $attachment;
		$term->note = $request->input('note');

        $term->save();

        if(! $request->ajax()){
            return redirect()->route('cashflow.term.create')->with('success', __('Saved Successfully'));
        }else{
            return response()->json(array("stat" => "success", "msg" => __("backend.addDone")));
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
        $term = Term::find($id);
        if(! $request->ajax()){
            return view('cashflow::backend.term.view',compact('term','id','GeneralWebmasterSections'));
        }else{
            return view('cashflow::backend.term.modal.view',compact('term','id'));
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
        $term = Term::find($id);
        if(! $request->ajax()){
            return view('cashflow::backend.term.edit',compact('term','id','GeneralWebmasterSections'));
        }else{
            return view('cashflow::backend.term.modal.edit',compact('term','id'));
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
			//'credit_acc_no' => 'required',
			'dedit_acc_no' => 'required',
			//'contract_id' => 'required',
			'code' => 'required',
            //'type' => 'required',
			'note' => 'nullable|max:150',
			'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				//return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
                return response()->json(array("stat" => "error", "msg" => __("backend.error")));
			}else{
				return redirect()->route('cashflow.terms.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
        if($request->hasfile('attachment'))
		{
		  $file = $request->file('attachment');
		  $attachment = time().$file->getClientOriginalName();
		  $file->move(public_path()."/uploads/terms/", $attachment);
		}	
		
        $term = Term::where('id',$id)
						          //->where('category_id','!=',1)
						          ->first();
        $term->name = $request->input('name');
		$term->code = $request->input('code');
		$term->category_id = $request->input('category_id');
		//$term->contract_id = $request->input('contract_id');
		$term->credit_acc_no = $request->input('credit_acc_no')??'';
		$term->dedit_acc_no = $request->input('dedit_acc_no')??'';
        $term->updated_user_id =  $request->user()->id;
		if($request->hasfile('attachment')){
			$term->attachment = $attachment;
		}

		$term->note = $request->input('note');
	
        $term->save();
        
		
		if(! $request->ajax()){
           return redirect()->route('cashflow.term.index')->with('success', __('Updated Successfully'));
        }else{
		   //return response()->json(['result'=>'success','action'=>'update', 'message'=>__('Updated Successfully'),'data'=>$term, 'table' => '#terms_table']);
           return response()->json(array("stat" => "success", "msg" => __("backend.saveDone")));
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
            $term = Term::find($id);
            $term->delete();
            if(! $request->ajax()){
                return response()->json(array("stat" => "success", "msg" => __("backend.deleteDone")));
            }else{
                return response()->json(['stat'=>'success','msg'=> __('Deleted Successfully')]);
            }
            //return redirect()->route('cashflow.term.index')->with('success',__('Deleted Successfully'));
        }
        return response()->json(array("stat" => "error", "msg" => __("backend.error")));
    }

    public function import_excel(Request $request){
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);
 
		$file = $request->file('file');
        $category_id = $request->input('category_id');
 
		$attachment = rand().$file->getClientOriginalName();
 
        $file->move(public_path()."/uploads/terms/", $attachment);
 
        //$model = new TermImport;
		// import data
		//Excel::import($model, public_path("/uploads/terms/".$attachment));
        //$array = (new TermImport)->toArray(public_path("/uploads/terms/".$attachment));
        $array = Excel::toArray(new TermImport, public_path("/uploads/terms/".$attachment));
        foreach($array[0] as $key => $row){
            if($key==0) continue;
            $term = new Term();
            $term->name = $row[1];
            $term->code = $row[0];
            $term->category_id = $category_id;
            $term->credit_acc_no = !empty($row[2])?$row[2]:'';
            $term->dedit_acc_no = !empty($row[3])?$row[3]:'';
            $term->created_user_id =  $request->user()->id;
            $term->save();
        }
 
		// alihkan halaman kembali
		return redirect()->route('cashflow.term.index')->with('success',__('Imported Successfully'));
    }
    /**
     * Remove the multi resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function multidelete(Request $request)
    {
        $ids = $request->input('id');
        foreach($ids as $id){
            $term = Term::find($id);
            $term->delete();
        }
        return response()->json(['result'=>'success','action'=>'delelete', 'message'=>__('Deleted Successfully'),'data'=>$request->input('id'), 'table' => '#terms_table']);
    }
}