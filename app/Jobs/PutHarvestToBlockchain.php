<?php

namespace App\Jobs;

use App\Components\SupplyContract;
use App\Models\Harvest;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PutHarvestToBlockchain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

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
         * @var $spc SupplyContract
         */
        $spc = app('spc');

        if($tx_hash = $spc->putHarvest($this->harvest)){
            $this->harvest->tx = $tx_hash;
            $this->harvest->save();
        }

    }
}
