<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cashflow\App\Models\Transaction;
use DB;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $prefix="";
    public function __construct()
    {
		 date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
         $this->prefix = DB::getTablePrefix();
    }

    /** Show Income Summary **/
    public function income_summary(Request $request){
        if($request->isMethod('get')){
            $year = date('Y');

            $income_list = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$this->prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
            FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
            UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
            UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
            LEFT JOIN {$this->prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$this->prefix}transactions.trans_date) = $year 
            AND dr_cr='cr' and category_id != 1 GROUP BY m.month ORDER BY m.month ASC");
            
            $months = '"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"';
            $income_string = '';

            foreach($income_list as $i){
                $income_string = $income_string.$i->amount.",";
            }

            $income_string = rtrim($income_string, ",");

            $data = array();
            $data['Months'] = $months;
            $data['Income'] = $income_string;

            return view('backend.reports.income_summary',$data);
        }else{

            $year = isset($request->year)?$request->year:date('Y');
            $account = $request->account;
            $customer = $request->customer;
            $category = $request->category;

            $customer_query = $customer != '' ? 'AND customer_id = '. $customer : '';
            $account_query = $account != '' ? 'AND account_id = '. $account : '';
            $category_query = $category != '' ? 'AND category_id = '. $category : 'AND category_id != 1';

            $income_list = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$this->prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
            FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
            UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
            UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
            LEFT JOIN {$this->prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$this->prefix}transactions.trans_date) = $year 
            AND dr_cr='cr' $category_query $account_query $customer_query GROUP BY m.month ORDER BY m.month ASC");
            
            $months = '"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"';
            $income_string = '';

            foreach($income_list as $i){
                $income_string = $income_string.$i->amount.",";
            }

            $income_string = rtrim($income_string, ",");

            $data = array();
            $data['Months'] = $months;
            $data['Income'] = $income_string;
            $data['year'] = $year;
            $data['account'] = $account;
            $data['customer'] = $customer;
            $data['category'] = $category;

            return view('backend.reports.income_summary',$data);
        }
    }


    /** Show Expense Summary **/
    public function expense_summary(Request $request){
        if($request->isMethod('get')){
            $year = date('Y');

            $expense_list = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$this->prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
            FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
            UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
            UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
            LEFT JOIN {$this->prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$this->prefix}transactions.trans_date) = $year 
            AND dr_cr='dr' and category_id != 1 GROUP BY m.month ORDER BY m.month ASC");
            
            $months = '"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"';
            $expense_string = '';

            foreach($expense_list as $e){
                $expense_string = $expense_string.$e->amount.",";
            }

            $expense_string = rtrim($expense_string, ",");

            $data = array();
            $data['Months'] = $months;
            $data['Expense'] = $expense_string;

            return view('backend.reports.expense_summary',$data);
        }else{

            $year = isset($request->year)?$request->year:date('Y');
            $account = $request->account;
            $vendor = $request->vendor;
            $category = $request->category;

            $vendor_query = $vendor != '' ? 'AND vendor_id = '. $vendor : '';
            $account_query = $account != '' ? 'AND account_id = '. $account : '';
            $category_query = $category != '' ? 'AND category_id = '. $category : 'AND category_id != 1';

            $expense_list = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$this->prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
            FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
            UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
            UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
            LEFT JOIN {$this->prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$this->prefix}transactions.trans_date) = $year 
            AND dr_cr='dr' $category_query $account_query $vendor_query GROUP BY m.month ORDER BY m.month ASC");
            
            $months = '"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"';
            $expense_string = '';

            foreach($expense_list as $e){
                $expense_string = $expense_string.$e->amount.",";
            }

            $expense_string = rtrim($expense_string, ",");

            $data = array();
            $data['Months'] = $months;
            $data['Expense'] = $expense_string;
            $data['year'] = $year;
            $data['account'] = $account;
            $data['vendor'] = $vendor;
            $data['category'] = $category;

            return view('backend.reports.expense_summary',$data);
        }
    }

    /** Show Expense Summary **/
    public function income_expense_summary(Request $request){
        if($request->isMethod('get')){
            $year = date('Y');

            $income_list = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$this->prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
            FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
            UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
            UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
            LEFT JOIN {$this->prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$this->prefix}transactions.trans_date) = $year 
            AND dr_cr='cr' and category_id != 1 GROUP BY m.month ORDER BY m.month ASC");

            $expense_list = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$this->prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
            FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
            UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
            UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
            LEFT JOIN {$this->prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$this->prefix}transactions.trans_date) = $year 
            AND dr_cr='dr' and category_id != 1 GROUP BY m.month ORDER BY m.month ASC");
            
            $months = '"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"';
            $income_string = '';
            $expense_string = '';

            foreach($income_list as $i){
                $income_string = $income_string.$i->amount.",";
            }

            $income_string = rtrim($income_string, ",");

            foreach($expense_list as $e){
                $expense_string = $expense_string.$e->amount.",";
            }

            $expense_string = rtrim($expense_string, ",");

            $data = array();
            $data['Months'] = $months;
            $data['Income'] = $income_string;
            $data['Expense'] = $expense_string;

            return view('backend.reports.income_expense_summary',$data);
        }else{

            $year = isset($request->year)?$request->year:date('Y');
            $account = $request->account;
            $vendor = $request->vendor;
            $customer = $request->customer;
            $category = $request->category;


            $account_query = $account != '' ? 'AND account_id = '. $account : '';
            $customer_query = $customer != '' ? 'AND customer_id = '. $customer : '';
            $category_query = $category != '' ? 'AND category_id = '. $category : 'AND category_id != 1';

            $income_list = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$this->prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
            FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
            UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
            UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
            LEFT JOIN {$this->prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$this->prefix}transactions.trans_date) = $year 
            AND dr_cr='cr' $category_query $account_query $customer_query GROUP BY m.month 
            ORDER BY m.month ASC");


            $vendor_query = $vendor != '' ? 'AND vendor_id = '. $vendor : '';

            $expense_list = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$this->prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
            FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
            UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
            UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
            LEFT JOIN {$this->prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$this->prefix}transactions.trans_date) = $year 
            AND dr_cr='dr' $category_query $account_query $vendor_query GROUP BY m.month ORDER BY m.month ASC");
            
            $months = '"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"';
            $income_string = '';
            $expense_string = '';

            foreach($income_list as $i){
                $income_string = $income_string.$i->amount.",";
            }

            $income_string = rtrim($income_string, ",");

            foreach($expense_list as $e){
                $expense_string = $expense_string.$e->amount.",";
            }

            $expense_string = rtrim($expense_string, ",");

            $data = array();
            $data['Months'] = $months;
            $data['Expense'] = $expense_string;
            $data['Income'] = $income_string;
            $data['year'] = $year;
            $data['account'] = $account;
            $data['vendor'] = $vendor;
            $data['customer'] = $customer;
            $data['category'] = $category;

            return view('backend.reports.income_expense_summary',$data);
        }
    }

}
