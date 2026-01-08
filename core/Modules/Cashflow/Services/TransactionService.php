<?php

namespace Modules\Cashflow\App\Services;
use Modules\Cashflow\App\Models\Transaction;
use Http;

class TransactionService{
    public function __construct(
        public $transaction = null
    ) {
        $this->transaction = $transaction ?? null;
        $this->contract = $this->transaction->contract_term->Contract??null;
    }

    public function calculateAmount(){
        //$contract = $this->transaction->contract_term->Contract;
        switch($this->contract->type){
            case "C":   
                return 10;
            case "PI": 
                return 20;
        }
        return 0;
    }
    public function getContractType(){
        $contract_types= get_option('contract_types' );
		$contract_types = unserialize($contract_types);
		$contract_type = $this->contract->type?$contract_types[$this->contract->type]:$this->contract->type;
        return $contract_type;
    }
}