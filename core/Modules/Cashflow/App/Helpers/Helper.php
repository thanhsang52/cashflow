<?php

// if ( ! function_exists('__')){
// 	function __($string=''){
		
// 		$target__ = get__uage();
				
// 		if($target__ == ''){
// 			$target__ = "language";
// 		}
		
// 		if(file_exists(resource_path() . "/language/$target__.php")){
// 			include(resource_path() . "/language/$target__.php"); 
// 		}else{
// 			include(resource_path() . "/language/language.php"); 
// 		}
		
// 		if (array_key_exists($string,$language)){
// 			return $language[$string];
// 		}else{
// 			return $string;
// 		}
// 	}
// }


// if ( ! function_exists('_dlang')){
// 	function _dlang( $string = '' ){
		
// 		//Get Target language
// 		$target__ = get_option('language');

// 		if($target__ == ''){
// 			$target__ = 'language';
// 		}
		
// 		if(file_exists(resource_path() . "/language/$target__.php")){
// 			include(resource_path() . "/language/$target__.php"); 
// 		}else{
// 			include(resource_path() . "/language/language.php"); 
// 		}
		
// 		if (array_key_exists( $string, $language )){
// 			return $language[$string];
// 		}else{
// 			return $string;
// 		}
// 	}
// }


if ( ! function_exists('startsWith')){
	function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
}


if ( ! function_exists('get_initials')){
	function get_initials($string){
		$words = explode(" ", $string);
		$initials = null;
		foreach ($words as $w) {
			 $initials .= $w[0];
		}
		return $initials;
	}
}


if ( ! function_exists('create_option')){
	function create_option($table, $value, $display, $selected='', $where=NULL){
		$options = '';
		$condition = '';
		if($where != NULL){
			$condition .= "WHERE ";
			foreach( $where as $key => $v ){
				$condition.=$key."'".$v."' ";
			}
		}
        
		if (is_array($display)){
		   $display_array =  $display;
		   $display =  $display_array[0];
		   $display1 =  $display_array[1];
		}
		$table = DB::getTablePrefix().$table;
		$query = DB::select("SELECT * FROM $table $condition");
		foreach($query as $d){
			if( $selected != '' && $selected == $d->$value ){   
				if(! isset($display_array)){
					$options.="<option value='".$d->$value."' selected='true'>".ucwords($d->$display)."</option>";
			    }else{
					$options.="<option value='".$d->$value."' selected='true'>".ucwords($d->$display.' - '.$d->$display1)."</option>";
				}
			}else{
				if(! isset($display_array)){
					$options.="<option value='".$d->$value."'>".ucwords($d->$display)."</option>";
			    }else{
					$options.="<option value='".$d->$value."'>".ucwords($d->$display.' - '.$d->$display1)."</option>";
				}
			} 
		}
		
		echo $options;
	}
}

if ( ! function_exists('object_to_string')){
	function object_to_string($object,$col,$quote = false) 
	{
		$string = "";
		foreach($object as $data){
			if($quote == true){
				$string .="'".$data->$col."', ";
			}else{
				$string .=$data->$col.", ";
			}
		}
		$string = substr_replace($string, "", -2);
		return $string;
	}
}

if ( ! function_exists('get_table')){
	function get_table($table,$where=NULL) 
	{
		$condition = "";
		if($where != NULL){
			$condition .= "WHERE ";
			foreach( $where as $key => $v ){
				$condition.=$key."'".$v."' ";
			}
		}
		$query = DB::select("SELECT * FROM $table $condition");
		return $query;
	}
}


if ( ! function_exists('user_count')){
	function user_count($user_type) 
	{
		$count = \App\Models\User::where("user_type",$user_type)
						->selectRaw("COUNT(id) as total")
						->first()->total;
	    return $count;
	}
}

if ( ! function_exists('has_permission')){
	function has_permission($name) 
	{				
		$permission_list = \Auth::user()->role->permissions;
		$permission = $permission_list->firstWhere('permission', $name);

	    if ( $permission != null ) {
		   return true;
		}
		return false;
	}
}


if ( ! function_exists('get_logo')){
	function get_logo() 
	{
		$logo = get_option("logo");
		if($logo ==""){
			return asset("public/backend/images/company-logo.png");
		}
		return asset("public/uploads/media/$logo"); 
	}
}

if ( ! function_exists('get_favicon')){
	function get_favicon() 
	{
		$favicon = get_option("favicon");
		if($favicon == ""){
			return asset("public/backend/images/favicon.png");
		}
		return asset("public/uploads/media/$favicon"); 
	}
}

// if ( ! function_exists('profile_picture')){
// 	function profile_picture($profile_picture = '') 
// 	{
// 		if($profile_picture == ''){
// 			$profile_picture = Auth::user()->profile_picture;
// 		}
		
//         if($profile_picture == ''){
// 			return asset('public/backend/images/avatar.png');
// 		}	
        
// 		return asset('public/uploads/profile/' . $profile_picture);		
// 	}
// }


if ( ! function_exists('sql_escape')){
	function sql_escape($unsafe_str) 
	{
		if (get_magic_quotes_gpc())
		{
			$unsafe_str = stripslashes($unsafe_str);
		}
		return $escaped_str = str_replace("'", "", $unsafe_str);
	}
}


if ( ! function_exists('get_option')){
	function get_option($name, $optional = '' ) 
	{
		$value = Cache::get($name); 
		
		if($value == ""){
			$setting = DB::table('cashflow_settings')->where('name', $name)->get();
			if ( ! $setting->isEmpty() ) {
			    $value = $setting[0]->value;
				Cache::put($name, $value);
			}else{
				$value = $optional;
			}
		}
		return $value;

	}
}

if ( ! function_exists('get_setting')){
	function get_setting($settings, $name, $optional = '' ) 
	{
		$row = $settings->firstWhere('name', $name);
	    if ( $row != null ) {
		   return $row->value;
		}
		return $optional;

	}
}

if ( ! function_exists('get_array_option')){
	function get_array_option($name, $key = '', $optional = '' ) 
	{
		if($key == ''){
			if(session('language') == ''){		
				$key = get_option('language');
                session(['language' => $key]);
			}else{
				$key = session('language');
			}
		}
		$setting = DB::table('settings')->where('name', $name)->get();
	    if ( ! $setting->isEmpty() ) {

		   $value =  $setting[0]->value;
		   if(@unserialize($value) !== false){
		   	   $value =  @unserialize($setting[0]->value);

		   	   return isset($value[$key]) ? $value[$key] : $value[array_key_first($value)];
		   }

		   return $value;
		}
		return $optional;

	}
}

if ( ! function_exists('get_array_data')){
	function get_array_data($data, $key = '') 
	{
       if($key == ''){
			if(session('language') == ''){	
				$key = get_option('language');
                session(['language' => $key]);
			}else{
				$key = session('language');
			}
		}
		
	   if(@unserialize($data) !== false){
	   	   $value =  @unserialize($data);
	   	   return isset($value[$key]) ? $value[$key] : $value[array_key_first($value)];
	   }

	   return $data;

	}
}


if ( ! function_exists('update_option')){
	function update_option($name, $value) 
	{
		date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
		
	    $data = array();
		$data['value'] = $value; 
		$data['updated_at'] = \Carbon\Carbon::now();
		if(\App\Models\Setting::where('name', $name)->exists()){				
			\App\Models\Setting::where('name', $name)->update($data);			
		}else{
			$data['name'] = $name; 
			$data['created_at'] = \Carbon\Carbon::now();
			\App\Models\Setting::insert($data); 
		}
	}
}


if ( ! function_exists('timezone_list'))
{

 function timezone_list() {
  $zones_array = array();
  $timestamp = time();
  foreach(timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
    $zones_array[$key]['ZONE'] = $zone;
    $zones_array[$key]['GMT'] = 'UTC/GMT ' . date('P', $timestamp);
  }
  return $zones_array;
}

}

if ( ! function_exists('create_timezone_option'))
{

 function create_timezone_option($old="") {
  $option = "";
  $timestamp = time();
  foreach(timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
	$selected = $old == $zone ? "selected" : "";
	$option .= '<option value="'. $zone .'"'.$selected.'>'. 'GMT ' . date('P', $timestamp) .' '.$zone.'</option>';
  }
  echo $option;
}

}


if ( ! function_exists( 'get_country_list' ))
{
    function get_country_list( $old_data='' ) {
		if( $old_data == '' ){
			echo file_get_contents( module_path('Cashflow').'/App/Helpers/country.txt' );
		}else{
			$pattern='<option value="'.$old_data.'">';
			$replace='<option value="'.$old_data.'" selected="selected">';
			$country_list=file_get_contents( module_path('Cashflow').'/App/Helpers/country.txt' );
			$country_list=str_replace($pattern,$replace,$country_list);
			echo $country_list;
		}
    }	
}

if ( ! function_exists('decimalPlace'))
{
	function decimalPlace($number, $symbol = ''){
		if($symbol == ''){
			return money_format_2( $number );
		}
			
		if(get_currency_position() == 'right'){	
			return money_format_2( $number ).' '.get_currency_symbol($symbol);	
		}else{
			return get_currency_symbol($symbol).' '.money_format_2( $number );
		}
	}
}


if (!function_exists('money_format_2')) {
	function money_format_2($floatcurr){
		$decimal_place = get_option('decimal_places',2);
		$decimal_sep = get_option('decimal_sep','.');
		$thousand_sep = get_option('thousand_sep',',');

		return number_format($floatcurr, $decimal_place, $decimal_sep, $thousand_sep);	
	}
}

if( !function_exists('formatinr') ){
	// custom function to generate: ##,##,###.##
	function formatinr($input)
	{
		$dec = "";
		$pos = strpos($input, ".");
		if ($pos === FALSE)
		{
			//no decimals
		}
		else
		{
			//decimals
			$dec   = substr(round(substr($input, $pos), 2), 1);
			$input = substr($input, 0, $pos);
		}
		$num   = substr($input, -3);    // get the last 3 digits
		$input = substr($input, 0, -3); // omit the last 3 digits already stored in $num
		// loop the process - further get digits 2 by 2
		while (strlen($input) > 0)
		{
			$num   = substr($input, -2).",".$num;
			$input = substr($input, 0, -2);
		}
		return $num.$dec;
	}
}

if( !function_exists('load__uage') ){
	function load__uage($active=''){
		$path = resource_path() . "/language";
		$files = scandir($path);
		$options="";
		
		foreach($files as $file){
		    $name = pathinfo($file, PATHINFO_FILENAME);
			if($name == "." || $name == "" || $name == "language"){
				continue;
			}
			
			$selected = "";
			if($active == $name){
				$selected = "selected";
			}else{
				$selected = "";
			}
			
			$options .= "<option value='$name' $selected>".$name."</option>";
		        
		}
		echo $options;
	}
}

if( !function_exists('get__uage_list') ){
	function get__uage_list(){
		$path = resource_path() . "/language";
		$files = scandir($path);
		$array = array();
		
		foreach($files as $file){
		    $name = pathinfo($file, PATHINFO_FILENAME);
			if($name == "." || $name == "" || $name == "language" || $name == "flags"){
				continue;
			}
	
			$array[] = $name;
		        
		}
		return $array;
	}
}

if( !function_exists('process_string') ){

 function process_string($search_replace,$string){
   $result = $string;
   foreach($search_replace as $key=>$value){
		$result = str_replace($key,$value,$result);
   }
   return $result;
 }

}


if ( ! function_exists('permission_list')){
	function permission_list()
	{
		  
		$permission_list =  \App\Models\AccessControl::where("role_id", Auth::user()->role_id)
											  ->pluck('permission')->toArray();	
	    return $permission_list;
	}
}

if ( ! function_exists('get_base_currency')){
	function get_base_currency() 
	{
		$currency = \Modules\Cashflow\App\Models\Currency::where("base_currency",1)->first();
		if(! $currency){
			$currency = \Modules\Cashflow\App\Models\Currency::all()->first();
		}
	    return $currency->name;
	}
}

if ( ! function_exists( 'get_currency_list' ))
{
	function get_currency_list( $old_data='', $serialize = false ) {	
		$currency_list = file_get_contents( module_path('cashflow').'/App/Helpers/currency.txt' );
		
		if( $old_data == "" ){
			echo $currency_list;
		}else{
			if($serialize == true){
				$old_data = unserialize($old_data);
				for($i=0; $i<count($old_data); $i++){
					$pattern = '<option value="'.$old_data[$i].'">';
					$replace = '<option value="'.$old_data[$i].'" selected="selected">';
				    $currency_list = str_replace($pattern,$replace,$currency_list);
				}
				echo $currency_list;
			}else{
				$pattern = '<option value="'.$old_data.'">';
				$replace = '<option value="'.$old_data.'" selected="selected">';
				$currency_list = str_replace($pattern,$replace,$currency_list);
				echo $currency_list;
			}
		}
	}	
}

if ( ! function_exists( 'get_currency_symbol' ))
{
	function get_currency_symbol( $currency_code ) {
		include(module_path('cashflow').'/App/Helpers/currency_symbol.php');
        
		if (array_key_exists($currency_code, $currency_symbols)){
			return $currency_symbols[$currency_code];
		}
		return "";
		
	}
}	


if ( ! function_exists('status')){
	function status($status)
	{
		if($status == 1){
			return "<span class='badge badge-success'>". __('Active') ."</span>"; 
		}else if($status == 0){
			return "<span class='badge badge-danger'>". __('In Active') ."</span>"; 
		}
	}
}

if ( ! function_exists('user_status')){
	function user_status($status)
	{
		if($status == 1){
			return "<span class='badge badge-success'>". __('Active') ."</span>"; 
		}else if($status == 0){
			return "<span class='badge badge-danger'>". __('In Active') ."</span>"; 
		}
	}
}


if ( ! function_exists('file_icon')){
	function file_icon($mime_type)
    {
        static $font_awesome_file_icon_classes = [
            // Images
            'image'=> 'fa-file-image',
            // Audio
            'audio'=> 'fa-file-audio',
            // Video
            'video'=> 'fa-file-video',
            // Documents
            'application/pdf'=> 'fa-file-pdf',
            'application/msword'=> 'fa-file-word',
            'application/vnd.ms-word'=> 'fa-file-word',
            'application/vnd.oasis.opendocument.text'=> 'fa-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml'=> 'fa-file-word',
            'application/vnd.ms-excel'=> 'fa-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml'=> 'fa-file-excel',
            'application/vnd.oasis.opendocument.spreadsheet'=> 'fa-file-excel',
            'application/vnd.ms-powerpoint'=> 'fa-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml'=> 'ffa-file-powerpoint',
            'application/vnd.oasis.opendocument.presentation'=> 'fa-file-powerpoint',
            'text/plain'=> 'fa-file-alt',
            'text/html'=> 'fa-file-code',
            'application/json'=> 'fa-file-code',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'=> 'fa-file-word',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'=> 'fa-file-excel',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'=> 'fa-file-powerpoint',
            // Archives
            'application/gzip'=> 'fa-file-archive',
            'application/zip'=> 'fa-file-archive',
            'application/x-zip-compressed'=> 'fa-file-archive',
            // Misc
            'application/octet-stream'=> 'fa-file-archive',
        ];

        if (isset($font_awesome_file_icon_classes[$mime_type]))
            return $font_awesome_file_icon_classes[$mime_type];

        $mime_group = explode('/', $mime_type, 2)[0];
        return (isset($font_awesome_file_icon_classes[$mime_group])) ? $font_awesome_file_icon_classes[$mime_group] : 'fa-file';
    }
}


if ( ! function_exists('update_currency_exchange_rate')){
	function update_currency_exchange_rate()
	{
		date_default_timezone_set(get_option('timezone','Asia/Dhaka'));

		$start  = new \Carbon\Carbon( get_option('currency_update_time',date("Y-m-d H:i:s", strtotime('-24 hours', time())) ) );
		$end    = \Carbon\Carbon::now();
  
		$last_run = $start->diffInHours($end);

		if( $last_run >= 12 ){
			// Set API Endpoint and API key 
			$endpoint = 'latest';
			$access_key = get_option('fixer_api_key');

			// Initialize CURL:
			$ch = curl_init('http://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key.'');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response:
			$exchangeRates = json_decode($json, true);

			if($exchangeRates['success'] == false){
				return false;
			}

			$base_currency =  $exchangeRates['base'];
			
			$currency_rates = array();
			
			foreach($exchangeRates['rates'] as $currency => $rate){
				$currency_rates[] = array(
										"currency" => $currency, 
										"rate" => $rate,
										"created_at" => date('Y-m-d H:i:s'),
										"updated_at" => date('Y-m-d H:i:s')
									);
				//echo $currency." - ".$rate."<br>";
			}
			
			DB::beginTransaction();
			
			\App\Models\CurrencyRate::getQuery()->delete();
			
			DB::statement("ALTER TABLE currency_rates AUTO_INCREMENT = 1");
			
			\App\CurrencyRate::insert($currency_rates);
			
			//Store Last Update time
			update_option("currency_update_time", \Carbon\Carbon::now());
			
			DB::commit();
		}
	}
}

if ( ! function_exists('convert_currency'))
{
    function convert_currency($from_currency_id, $to_currency_id, $amount){
		$currency1 = \App\Models\Currency::find($from_currency_id)->exchange_rate;
		$currency2 = \App\Models\Currency::find($to_currency_id)->exchange_rate;

		$converted_output = ($amount/$currency1) * $currency2;
        return $converted_output;
    }
}

if ( ! function_exists('cc'))
{
    function cc($from_rate, $to_rate, $amount){
    	if($from_rate == 0){
    		return $amount;
    	}
		$converted_output = ($amount/$from_rate) * $to_rate;
        return $converted_output;
    }
}



if ( ! function_exists('xss_clean')){
	function xss_clean($data)
	{
		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
		    // Remove really unwanted tags
		    $old_data = $data;
		    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);

		// we are done...
		return $data;
	}
}


// convert seconds into time
if ( ! function_exists('time_from_seconds')){
	function time_from_seconds($seconds) { 
	    $h = floor($seconds / 3600); 
	    $m = floor(($seconds % 3600) / 60); 
	    $s = $seconds - ($h * 3600) - ($m * 60); 
	    return sprintf('%02d:%02d:%02d', $h, $m, $s); 
	} 
}


if ( ! function_exists('get_financial_balance')){

 	function get_financial_balance(){
		$prefix = DB::getTablePrefix();
		$result = DB::select("SELECT b.*,{$prefix}cashflow_currency.name as currency,((SELECT ISNULL(openning_balance, 0) 
		FROM {$prefix}cashflow_accounts WHERE id = b.id) + (SELECT ISNULL(SUM(amount),0) 
		FROM {$prefix}cashflow_transactions WHERE dr_cr = 'cr' AND account_id = b.id))-(SELECT ISNULL(SUM(amount),0) 
		FROM {$prefix}cashflow_transactions WHERE dr_cr = 'dr' AND account_id = b.id) as balance 
		FROM {$prefix}cashflow_accounts as b INNER JOIN {$prefix}currency ON {$prefix}currency.id=b.currency_id");
		return $result;

		}

}

/* Intelligent Functions */
if ( ! function_exists('get__uage')){
	function get__uage() { 
	    $language = Cache::get('language'); 
		
		if($language == ''){	
			$language = get_option('language','language');
			\Cache::put('language', $language);
		}
		
		return $language;
	} 
}

if ( ! function_exists('get_currency_position')){
	function get_currency_position() { 
	    $currency_position = Cache::get('currency_position'); 
		
		if($currency_position == ''){	
			$currency_position = get_option('currency_position');
			\Cache::put('currency_position', $currency_position);
		}
		
		return $currency_position;
	} 
}

if ( ! function_exists('currency')){
	function currency() { 
	    $currency = Cache::get('currency'); 
		
		if($currency == ''){	
			//$currency = get_option('currency');
			$currency = get_base_currency();
			\Cache::put('currency', $currency);
		}
		
		return $currency;
	} 
}

if ( ! function_exists('get_date_format')){
	function get_date_format() { 
	    $date_format = Cache::get('date_format'); 
		
		if($date_format == ''){	
			$date_format = get_option('date_format','Y-m-d');
			\Cache::put('date_format', $date_format);
		}
		
		return $date_format;
	} 
}

if ( ! function_exists('get_time_format')){
	function get_time_format() { 
	    $time_format = Cache::get('time_format'); 
		
		if($time_format == ''){	
			$time_format = get_option('time_format');
			\Cache::put('time_format', $time_format);
		}
		
		$time_format = $time_format == 24 ? 'H:i' : 'h:i A';
		
		return $time_format;
	} 
}

if ( ! function_exists('checkRoute')){
	function checkRoute($route) {
		$notAllowed = [
			url('profile'),
			url('administration'),
			url('reports')
		];
		
		if (in_array(url($route), $notAllowed)){
			return false;
		}
		return true;
		
	}
}

if ( ! function_exists('can_access')){
	function can_access($route='')
	{
		$user_type = \Request::session()->get('user_type');
		if($user_type=='admin'){
			return true;
		}
		$permissions = \Request::session()->get('permissions');
		if($user_type=='user'){
			if(!empty($route) && !in_array($route,$permissions))
				return false;
			elseif(in_array($route,$permissions))
				return true;
			$currentRoute = Route::current();
			$currentRouteName = $currentRoute->getName();
			if(in_array($currentRouteName,$permissions))
				return true;
		}
		return false;
	}
}
if ( ! function_exists('convertToLatin')){
	function convertToLatin($value)
	{
		$detect = mb_detect_encoding($value);
		if(!isset($value)) return "";
		if($detect=="UTF-8") return $value;
		return mb_convert_encoding($value, "Latin1", ["UTF-8","ISO-8859-1","ASCII","unicode"]);
	}
}