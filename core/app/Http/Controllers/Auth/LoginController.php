<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Helper;
use Illuminate\Support\Facades\Hash;
use Config;
use \GuzzleHttp\Client;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        
       
    }

    public function checkLdapService($username,$password){
        $client = new Client();
        $p_username = urlencode($username);
        $p_password = $password;
        $params['headers'] = ['Content-Type' => 'application/json'];
        $params['json'] = array('username' => $p_username, 'password' => $p_password);
        $apiURL = "https://arserv3.medicare.com.vn/api/get-status-user-domain";
        try{
            $res = $client->post($apiURL, $params);
            $data = json_decode($res->getBody()->getContents());
            if($data->l_success_msg=="Success") return $data;
        }catch(Exception $e){
            return false;
        }
        
        return false;
    }

    protected function attemptLogin(Request $request){
        $disableCaptcha = Config::get('captcha.disable');
        if(!$disableCaptcha){
            $rules = ['captcha' => 'required|captcha'];
            $validator = validator()->make(request()->all(), $rules);
            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
                return false;
            } 
        }
        $credentials = $this->credentials($request);
        if (Auth::attempt($credentials)) {
            return true;
        }/*else{
            $username = $credentials['email'];
            $password = $credentials['password'];
            
            $response = $this->checkLdapService($username,$password);
            if($response!=false){
                $name = $response->name;
                $mail = $response->mail;
                $userFound = \App\Models\User::where('email',$mail)->first();
                if(!$userFound){
                    $model = new \App\Models\User();
                    $model->name = $name;
                    $model->email = $mail;
                    $model->password = Hash::make($password);
                    $model->permissions_id = 3;
                    $model->status = 1;
                    $model->email_verified_at = date('Y-m-d H:i:s');
                    $model->provider = "ldap";
                    
                    $model->save();
                    
                }else{
                    $userFound->password = Hash::make($password);
                    $userFound->save();
                }
                Auth::attempt(['email'=>$mail,'password'=>$password]);
               
                return true;
            }
        
        }*/
        return false;
    }
}
