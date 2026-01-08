<?php

namespace Modules\Cashflow\App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Modules\Cashflow\App\Models\Transaction;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $prefix = DB::getTablePrefix();
        $data = array();
        $data['total_income'] = Transaction::where('type','income')
                                           ->where('category_id','!=',1)
                                           ->selectraw('ISNULL(SUM(('.$prefix.'transactions.amount/currency_rate) * 1),0) as amount')
                                           ->first()->amount;

        $data['total_expense'] = Transaction::where('type','expense')
                                            ->where('category_id','!=',1)
                                            ->selectraw('ISNULL(SUM(('.$prefix.'transactions.amount/currency_rate) * 1),0) as amount')
                                            ->first()->amount;

        $data['recent_income'] = Transaction::where('type','income')
                                            ->where('category_id','!=',1)
                                            ->orderBy('id','desc')
                                            ->limit(5)
                                            ->get();

        $data['recent_expense'] = Transaction::where('type','expense')
                                             ->where('category_id','!=',1)
                                             ->orderBy('id','desc')
                                             ->limit(5)
                                             ->get();

        return view('backend.dashboard',$data);
    }

    public function json_month_wise_income_expense(){
        $income = $this->month_wise_income();
        $expense = $this->month_wise_expense();

        $month_arr = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $income_arr= array();
        $expense_arr = array();
        $profit_arr = array();

        foreach($income as $i){
            $income_arr[] =$i->amount; 
        }

        foreach($expense as $e){
            $expense_arr[] =$e->amount;
        }  

        $index = 0;
        foreach($income as $p){
            $profit_arr[] =$p->amount - $expense[$index]->amount;
            $index++;
        }  

        return response(json_encode(["Months"=>$month_arr,"Income"=> $income_arr, "Expense"=>$expense_arr, "Profit"=>$profit_arr]))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }


    public function json_income_by_category(){
        $prefix = DB::getTablePrefix();
        $transactions = Transaction::where('type','income')
                                    ->selectRaw("{$prefix}transactions.category_id, ROUND(ISNULL(SUM(({$prefix}transactions.amount/currency_rate) * 1),0),2) as amount")
                                    ->with('income_type')
                                    ->where('category_id','!=',1)
                                    ->groupBy('category_id')
                                    ->get();
        $category = array();
        $data = array();

        foreach($transactions as $transaction){
            array_push($category, $transaction->income_type->name);
            array_push($data, array('value' => (double) $transaction->amount, 
                'name' => $transaction->income_type->name, 
                'itemStyle' => array('color' => $transaction->income_type->color)
                ));
        }

        echo json_encode(array('data' => $data, 'category' => $category));

    }

     public function json_expense_by_category(){
        $prefix = DB::getTablePrefix();
        $transactions = Transaction::where('type','expense')
                                    ->selectRaw("{$prefix}transactions.category_id, ROUND(ISNULL(SUM(({$prefix}transactions.amount/currency_rate) * 1),0),2) as amount")
                                    ->with('income_type')
                                    ->where('category_id','!=',1)
                                    ->groupBy('category_id')
                                    ->get();
        $category = array();
        $data = array();

        foreach($transactions as $transaction){
            array_push($category, $transaction->expense_type->name);
            array_push($data, array('value' => (double) $transaction->amount, 
                'name' => $transaction->expense_type->name,
                'itemStyle' => array('color' => $transaction->expense_type->color)
            ));
        }

        echo json_encode(array('data' => $data, 'category' => $category));

    }

    private function month_wise_income(){
        $prefix = DB::getTablePrefix();
        $date = date("Y-m-d");
        $query = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$prefix}transactions.amount/currency_rate) * 1),0),2) as amount 
        FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
        UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
        UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
        LEFT JOIN {$prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$prefix}transactions.trans_date)=YEAR('$date') 
        AND dr_cr='cr' and category_id !=1 GROUP BY m.month ORDER BY m.month ASC");
        return $query;
    }
    
    private function month_wise_expense(){
        $prefix = DB::getTablePrefix();
        $date = date("Y-m-d");
        $query = DB::select("SELECT m.month, ROUND(ISNULL(SUM(({$prefix}transactions.amount/currency_rate) * 1),0),2) as amount
        FROM ( SELECT 1 AS MONTH UNION SELECT 2 AS MONTH UNION SELECT 3 AS MONTH UNION SELECT 4 AS MONTH 
        UNION SELECT 5 AS MONTH UNION SELECT 6 AS MONTH UNION SELECT 7 AS MONTH UNION SELECT 8 AS MONTH 
        UNION SELECT 9 AS MONTH UNION SELECT 10 AS MONTH UNION SELECT 11 AS MONTH UNION SELECT 12 AS MONTH ) AS m 
        LEFT JOIN {$prefix}transactions ON m.month = MONTH(trans_date) AND YEAR({$prefix}transactions.trans_date)=YEAR('$date') 
        AND dr_cr='dr' and category_id !=1 GROUP BY m.month ORDER BY m.month ASC");
        return $query;
    }
}
