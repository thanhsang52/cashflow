<?php

namespace Modules\Cashflow\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Cashflow\App\Models\Vendor;
use Validator;
use DataTables;
use Auth;
use App\Models\WebmasterSection;
use Helper;
use Redirect;

class VendorController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // Check Permissions
        if (!@Auth::user()->permissionsGroup->cashflow_status || !Helper::GeneralWebmasterSettings("cashflow_status")) {
            return Redirect::to(route('NoPermission'))->send();
        }
    }
	public function get_table_data(Request $request){
        $limit = $request->input('length',30);
        $start = $request->input('start',0);
		$vendors = Vendor::select('cashflow_vendors.*')
                                    ->offset($start)
                                    ->limit($limit);
									//->where("transactions.dr_cr","cr")
									//->orderBy("cashflow_vendors.code","asc");
        //$vendorCount = count($vendors);
        $total = $vendors->get()->count();
        $x = 0;
		return Datatables::eloquent($vendors)
                        ->addColumn('check', function ($vendor) {

                            return '<div class="row_checker"><label class="ui-check m-a-0">
                                            <input type="checkbox" name="ids[]" value="'.$vendor['id'].'"><i class="dark-white"></i>
                                                    <input type="hidden" name="row_ids[]" value="'.$vendor['id'].'" class="form-control row_no">
                                        </label>
                                    </div>';
                            
                        })
						->addColumn('options', function ($vendor) use($total,&$x){
                                $x++;
                                return '
                                
                                <div class="text-center">
                                    <div class="dropdown ' . ((($x+2) >= $total) ? "dropup" : "") . '">
                                        <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons"></i> Options</button>
                                        <div class="dropdown-menu pull-right">
                                        <a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\VendorController@show', $vendor['id']) .'" target="_blank"><i class="material-icons"></i> Show</a>'.
                                        (@Auth::user()->permissionsGroup->edit_status?'<a class="dropdown-item" href="'.action('\Modules\Cashflow\App\Http\Controllers\VendorController@edit', $vendor['id']) .'"><i class="material-icons"></i> Edit</a>':'').
                                        
                                        (@Auth::user()->permissionsGroup->delete_status?'<a class="dropdown-item text-danger" onclick="DeleteVendor(\''.$vendor['id'].'\')"><i class="material-icons"></i> Delete</a>':'').
                                        '</div>
                                    </div>
                                </div>
                                ';
							
						})

						->setRowId(function ($vendor) {
							return "row_".$vendor->id;
						})
						->rawColumns(['check','code','name','company_name','email','phone','options'])
						->make(true);							    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        //$vendors = Vendor::all()->sortByDesc("id");
        return view('cashflow::backend.vendor.list',compact('GeneralWebmasterSections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        if( ! $request->ajax()){
           return view('cashflow::backend.vendor.create',compact('GeneralWebmasterSections'));
        }else{
           return view('cashflow::backend.vendor.modal.create');
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
			'email' => 'required|email|unique:vendors|max:255',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('casflow.vendors.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        
        $vendor = new Vendor();
        $vendor->code = $request->input('code');
        $vendor->name = $request->input('name');
		$vendor->company_name = $request->input('company_name');
		$vendor->email = $request->input('email');
		$vendor->registration_no = $request->input('registration_no');
		$vendor->vendor_cat = $request->input('vendor_cat');
		$vendor->phone = $request->input('phone');
		$vendor->country = $request->input('country');
		$vendor->city = $request->input('city');
		$vendor->state = $request->input('state');
		$vendor->zip = $request->input('zip');
		$vendor->address = $request->input('address');
		$vendor->note = $request->input('note');

        $vendor->save();

        if(! $request->ajax()){
           return redirect()->route('cashflow.vendors.create')->with('success', __('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','doneMessage', __('backend.addDone'),'data'=>$vendor, 'table' => '#vendors_table']);
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
        $vendor = Vendor::find($id);
        if(! $request->ajax()){
            return view('cashflow::backend.vendor.view',compact('vendor','id','GeneralWebmasterSections'));
        }else{
            return view('cashflow::backend.vendor.modal.view',compact('vendor','id'));
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
        $vendor = Vendor::find($id);
        if(! $request->ajax()){
            return view('cashflow::backend.vendor.edit',compact('vendor','id','GeneralWebmasterSections'));
        }else{
            return view('cashflow::backend.vendor.modal.edit',compact('vendor','id'));
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
                //Rule::unique('cashflow_customers')->ignore($id),
            ],
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('cashflow.vendors.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	    	
		
        $vendor = Vendor::find($id);
        $vendor->code = $request->input('code');
		$vendor->name = $request->input('name');
		$vendor->company_name = $request->input('company_name');
		$vendor->email = $request->input('email');
		$vendor->registration_no = $request->input('registration_no');
		$vendor->vendor_cat = $request->input('vendor_cat');
		$vendor->phone = $request->input('phone');
		$vendor->country = $request->input('country');
		$vendor->city = $request->input('city');
		$vendor->state = $request->input('state');
		$vendor->zip = $request->input('zip');
		$vendor->address = $request->input('address');
		$vendor->note = $request->input('note');
	
        $vendor->save();
		
		if(! $request->ajax()){
           return redirect()->route('cashflow.vendors.index')->with('success', __('Updated Successfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'doneMessage', __('backend.saveDone'),'data'=>$vendor, 'table' => '#vendors_table']);
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
        $vendor = Vendor::find($id);
        $vendor->delete();
        return redirect()->route('cashflow.vendors.index')->with('success',__('Deleted Successfully'));
    }
}