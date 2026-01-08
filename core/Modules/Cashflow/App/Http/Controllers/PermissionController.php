<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Modules\Cashflow\App\Models\AccessControl;
use Auth;
use DB;
use App\Models\WebmasterSection;
use Redirect;
class PermissionController extends Controller
{
    public function index($role_id = '')
    {

		$GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
		$permission_list = array();
		if($role_id=='' && isset(Auth::user()->permissionsGroup)){
		    $role_id = Auth::user()->permissionsGroup->id;
		}
		if ($role_id !=''){
		    $permission_list = AccessControl::where("role_id",$role_id)
											->pluck('permission')
											->toArray(); 
		}else{
			return Redirect::to(route('NoPermission'))->send();
		}
		
		$notallowed = array(
		    '\App\Http\Controllers\Auth\LoginController',
		    'App\Http\Controllers\Auth\LoginController',
			'App\Http\Controllers\Auth\RegisterController',
			'App\Http\Controllers\Auth\ForgotPasswordController',
			'App\Http\Controllers\Auth\ResetPasswordController',
			'App\Http\Controllers\Auth\ConfirmPasswordController',
			'App\Http\Controllers\Auth\VerificationController',
			'App\Http\Controllers\DashboardController',
			//'App\Http\Controllers\ProfileController',
			'App\Http\Controllers\UserController',
			//'App\Http\Controllers\RoleController',
			'App\Http\Controllers\LanguageController',
			'App\Http\Controllers\UtilityController',
			//'App\Http\Controllers\EmailTemplateController',
			'App\Http\Controllers\PermissionController',
			'\Mews\Captcha\CaptchaController',
		);
				
		$ignoreRoute = array(
		    //'events.show',
			//'notices.show',
		);
		
		$app = app();

		$routeCollection = $app->routes->getRoutes();

		$routes = [];
		
		// loop through the collection of routes
		foreach ($routeCollection as $route) {
  
			// get the action which is an array of items
			$action = $route->getAction();
			if(!isset($action['prefix']) || $action['prefix']!="/cashflow") continue;
			// if the action has the key 'controller' 
			if (array_key_exists('controller', $action)) {

				// explode the string with @ creating an array with a count of 2
				$explodedAction = explode('@', $action['controller']);

				//If not needed so ignore
				if(in_array($explodedAction[0],$notallowed)){
					continue;
				}
				
				if (!isset($routes[$explodedAction[0]])) {
					$routes[$explodedAction[0]] = [];
				}
				
				if(isset($explodedAction[1]) && strpos($explodedAction[0], 'Modules\Cashflow') === 0){
					$test = new $explodedAction[0]();
					if(method_exists($test ,$explodedAction[1])){
						$routes[$explodedAction[0]][] = array("method" => $explodedAction[1], "action" => $route->action);
					}	
				}
			}
		}

		$permission = array();
		
		foreach($routes as $key => $route){
			foreach($route as $r){
				if (strpos($r['method'], 'get') === 0) {
				   continue;
				}	

                if(array_key_exists('as',$r['action'])){
					$routeName = $r['action']['as'];
                    //If not needed so ignore
					if(in_array($routeName, $ignoreRoute)){
						continue;
					}					
			    	$permission[$key][$routeName] = $r['method'];
				}

			}
		}

		
		foreach($permission as $key => $val){
			foreach($val as $name => $url){
				if($url == "store" && in_array("create", $val)){
					unset($permission[$key][$name]);
				}
				if($url == "update" && in_array("edit", $val)){
					unset($permission[$key][$name]);
				}
			}
		}
		
	
		return view('cashflow::backend.permission.create', compact('permission', 'permission_list', 'role_id','GeneralWebmasterSections'));
		
    }
	
	public function store(Request $request){
		$this->validate($request, [
            'role_id'     => 'required',
            'permissions' => 'required'
        ]);
		
		DB::beginTransaction();
		
		$permission = AccessControl::where('role_id', $request->role_id);
		$permission->delete();
		
		foreach($request->permissions as $role){
			$permission = new AccessControl();
			$permission->role_id = $request->role_id;
			$permission->permission = $role;
			$permission->save();
		}
		
		DB::commit();
		
		return redirect()->route('cashflow.permission.index')->with('success', __('Saved Successfully'));
		
	}
	
   
}
