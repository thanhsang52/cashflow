<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Note;
use Validator;

class NoteController extends Controller
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
    /*public function index()
    {
        $notes = Note::all()->sortByDesc("id");
        return view('backend.note.list',compact('notes'));
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.note.create');
        }else{
           return view('backend.note.modal.create');
        }
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'type' => 'required',
			'object_id' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('cashflow.note.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        $note = new Note();
        $note->content = $request->input('content');
		$note->type = $request->input('type');
        //$note->created_by = $request->input('created_by');
        $note->object_id = $request->input('object_id');
        $note->save();

        session(['activeTab' => 'notes']);
        $route_back = $request->get('route_back');
        if(isset($route_back)) return redirect($route_back);

        if(! $request->ajax()){
           return redirect()->route('cashflow.contract.show',0)->with('success', __('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>__('Saved Successfully'),'data'=>$category, 'table' => '#income_expense_categories_table']);
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
		
	    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Note::find($id);
        $note->delete();
        return redirect()->back()->with('success',__('Deleted Successfully'));
        
    }
}