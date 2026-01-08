<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Category;
use Validator;

class CategoryController extends Controller
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
        $categorys = Category::all()->sortByDesc("id");
        return view('backend.income_expense_category.list',compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.income_expense_category.create');
        }else{
           return view('backend.income_expense_category.modal.create');
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
            'type' => 'required',
			'color' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('transaction_categories.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        

        $category = new Category();
        $category->name = $request->input('name');
		$category->type = $request->input('type');
        $category->color = $request->input('color');

        $category->save();

        $category->type = ucwords($category->type);
        $category->color = '<div class="rounded-circle color-circle" style="background:'.$category->color.'"></div>';

        if(! $request->ajax()){
           return redirect()->route('transaction_categories.create')->with('success', _lang('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Successfully'),'data'=>$category, 'table' => '#income_expense_categories_table']);
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
        $category = Category::find($id);
        if(! $request->ajax()){
            return view('backend.income_expense_category.edit',compact('category','id'));
        }else{
            return view('backend.income_expense_category.modal.edit',compact('category','id'));
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
            'color' => 'required',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('transaction_categories.edit', $id)
							     ->withErrors($validator)
							     ->withInput();
			}			
		}
	
        	
		
        $category = Category::find($id);
		$category->name = $request->input('name');
		$category->color = $request->input('color');
	
        $category->save();

        $category->type = ucwords($category->type);
        $category->color = '<div class="rounded-circle color-circle" style="background:'.$category->color.'"></div>';
		
		if(! $request->ajax()){
           return redirect()->route('income_expense_categories.index')->with('success', _lang('Updated Successfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Successfully'),'data'=>$category, 'table' => '#income_expense_categories_table']);
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
        $category = Category::find($id);
        
        if($category->system == 0){
            $category->delete();
            return redirect()->route('transaction_categories.index')->with('success',_lang('Deleted Successfully'));
        }
        
        return redirect()->route('transaction_categories.index')->with('success',_lang('Sorry, You cannot remove this category'));
    }
}