<?php

namespace App\Jobs;

use App\Components\ContractV2;
use App\Models\HarvestExpertise;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PutExpertiseNoRevisions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * @var HarvestExpertise
     */
    protected $exp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(HarvestExpertise $expertise)
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
         * @var $spc ContractV2
         */
        $spc = app('spcv2');

        if($tx_hash = $spc->putExpertise($this->exp)){
            $this->exp->tx = $tx_hash;
            $this->exp->save();
        }

        app('spcv3')->putExpertise($this->exp);

    }

}
