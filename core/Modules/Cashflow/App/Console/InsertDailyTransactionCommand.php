<?php

namespace Modules\Cashflow\App\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Log;
use Modules\Cashflow\App\Models\Transaction;
use Modules\Cashflow\App\Models\TransactionSchedule;

class InsertDailyTransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:insert-daily-transactions';

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::debug('Checking daily transactions...');
        $model = TransactionSchedule::whereDate('transaction_date', now()->toDateString())
                ->where('status', 0);
        if($model->count() > 0){

            $items = $model->get();
            foreach($items as $item){
                $attributes = $item->getAttributes();
                $transaction = new Transaction();
                $transaction->trans_date = $attributes['transaction_date'];
                $transaction->category_id = 4;
                $transaction->contract_term_id = $attributes['contract_term_id'];
                $transaction->type = 'income';
                $transaction->dr_cr = 'cr';
                $transaction->amount = 0;
                $transaction->currency_rate = 1;
                $transaction->status = 'pending';
                $transaction->payment_method_id = 2;
                $transaction->note = 'schedule transaction';
                $rs = $transaction->save();
                //Log::debug("rs: $rs");
                
            }
            $model->update(['status'=>1]);
            $this->info('Daily transactions inserted successfully.');
            //Log::debug('Daily transactions inserted successfully.');
        }

        return 0;
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
