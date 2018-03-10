<?php

namespace App\Console\Commands;

use App\Components\Ethereum;
use App\Models\Transaction;
use App\Models\TransactionWrapper;
use DB;
use Illuminate\Console\Command;

class SyncStatusOfPendingTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'txStatus:sync {--pending=yes} {--failed=no} {--success=no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $query = DB::table('transactions')->orderBy('id');

        $this->_applyFilter($query, $this->option('pending'), Transaction::TX_EXEC_PENDING);
        $this->_applyFilter($query, $this->option('failed'),  Transaction::TX_EXEC_FAILED);
        $this->_applyFilter($query, $this->option('success'), Transaction::TX_EXEC_SUCCESS);

        $query->chunk(100, function($transactions){

//            $this->line('chunk------------------------------');

            foreach ($transactions as $transaction) {

                $txWrapper = TransactionWrapper::find($transaction->tx);

                $updateQuery = DB::table('transactions')
                    ->where('id',$transaction->id);

                $msgHead = "{$transaction->tx}/ [ {$transaction->id} ]:> status=";

                if($transaction->status == Transaction::TX_EXEC_PENDING){
                    $msgHead .= 'pending->';
                }elseif ($transaction->status == Transaction::TX_EXEC_SUCCESS){
                    $msgHead .= 'confirmed->';
                }elseif ($transaction->status == Transaction::TX_EXEC_FAILED){
                    $msgHead .= 'failed->';
                }

                if($txWrapper->isPending()){

                    $updateQuery->update(['status'=>Transaction::TX_EXEC_PENDING]);
                    $msgHead .= 'pending;exec';

                }else if($txWrapper->isAccepted()){

                    $updateQuery->update(['status'=>Transaction::TX_EXEC_SUCCESS]);
                    $msgHead .= 'confirmed;exec';

                }else if($txWrapper->isFailed()){

                    $updateQuery->update(['status'=>Transaction::TX_EXEC_FAILED]);
                    $msgHead .= 'failed;exec';

                }

//                $this->line($msgHead);


            }

        });

    }

    protected function _applyFilter(\Illuminate\Database\Query\Builder $query, $optionValue, $dbValue){
        if( strtolower($optionValue) == 'yes'){
            $query->orWhere('status', $dbValue);
        }

    }

}
