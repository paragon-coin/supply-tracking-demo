<?php

namespace App\Jobs;

use App\Components\SupplyContract;
use App\Models\HarvestExpertise;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PutExprtiseResultToBlockchain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $exp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( HarvestExpertise $expertise )
    {

        $this->exp = $expertise;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        /**
         * @var $spc SupplyContract
         */
        $spc = app('spc');

        if($tx_hash = $spc->putExpertise($this->exp)){
//            dd($tx_hash);
            $this->exp->tx = $tx_hash;
            $this->exp->save();
        }



    }
}
