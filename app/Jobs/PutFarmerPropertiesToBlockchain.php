<?php

namespace App\Jobs;

use App\Components\SupplyContract;
use App\Models\Farmer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PutFarmerPropertiesToBlockchain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * @var Farmer
     */
    protected $farmer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Farmer $farmer)
    {

        $this->farmer = $farmer;

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

        if($tx_hash = $spc->putFarmerProperties($this->farmer)){
            $this->farmer->tx_props = $tx_hash;
            $this->farmer->save();
        }


    }
}
