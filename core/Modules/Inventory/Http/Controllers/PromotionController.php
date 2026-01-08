<?php

namespace Modules\Inventory\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Http\Requests\UpdateInventoryRequest;
use Modules\Inventory\Http\Services\Backend\InventoryServices;
use App\Models\WebmasterSection;
use Modules\Inventory\Entities\ItemBranch;
use Modules\Inventory\Entities\Promotion;
use Throwable;
use DataTables;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $all_inventory_products = NULL;
        //$promotion =  Promotion::find(43557);
        return view('inventory::backend.promotion.index', compact('all_inventory_products','GeneralWebmasterSections'));
    }
    public function get_table_data(Request $request){
		$limit = $request->input('length',config('smartend.backend_pagination'));
        $start = $request->input('start',0);
        $dir = $request->input('order.0.dir');
        $order = $request->input('order.0.column');
        if ($order == "") {
            $order = "last_modified";
        }

		$promotions = Promotion::with("promotionType")->select('PROMOTION.*')->limit($limit);
        $total = $promotions->count();
        $x = 0;
		return Datatables::eloquent($promotions)	
            
            ->setRowId(function ($promotion) {
                return "row_".$promotion->promo_code;
            })
            ->addColumn('check', function ($promotion) {
                return '<div class="row_checker $i"><label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="'.$promotion['promo_code'].'"><i class="dark-white"></i>
                                <input type="hidden" name="row_ids[]" value="'.$promotion['promo_code'].'" class="form-control row_no">
                            </label>
                        </div>';
                    
                })
           /* ->filter(function ($instance) use ($request) {
               
                if (!empty($request->get('branch_no')) && $request->get('branch_no') != '0') {
                    $instance->where('branch_no', $request->get('branch_no'));
                }
                if (!empty($request->get('item_code')) && $request->get('item_code') != '0') {
                    $instance->where('item_code', $request->get('item_code'));
                }
                
                $search = $request->get('search');
                $division = $request->get('division');

                    $instance->whereHas('product', function ($query) use ($search,$division ) {
                        if(isset($division))
                            $query->where('cat1', $division);
                        if (!empty($search['value']))
                            $query->where('item_code', $search['value'])->orWhere('description', 'LIKE' ,  "%" .$search['value']."%");
                    });
                    
                
            })*/
            ->rawColumns(['check','promo_code','promotionType','type','name','start_date','end_date','discount_type','value','discounted_bin','region'])
            ->make(true);							    
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductInventory $item
     * @return Application|Factory|View
     */
    public function edit(ProductInventory $item)
    {

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInventoryRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateInventoryRequest $request)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return bool
     */
    public function destroy(Request $request)
    {
        $id=$request->query('id');
        //return (bool) ProductInventory::find($id)->delete();
    }

    public function detail(Request $request,$id){
        //$id=$request->query('id');
        $promotion = Promotion::find($id);
        return view('inventory::backend.promotion.modal.detail',compact('promotion'));
    }

    
}
