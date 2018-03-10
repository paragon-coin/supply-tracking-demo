<?php

namespace App\Jobs;

use App\Components\ContractV2;
use App\Models\Harvest;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PutHarvsetNoRevisions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * @var Harvest
     */
    protected $harvest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Harvest $harvest)
    {

        $this->harvest = $harvest;

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

        if($tx_hash = $spc->putHarvest($this->harvest)){
            $this->harvest->tx = $tx_hash;
            $this->harvest->save();
        }

        app('spcv3')->putHarvest($this->harvest);
    }

}
