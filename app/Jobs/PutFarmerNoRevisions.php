<?php

namespace App\Jobs;

use App\Components\ContractV2;
use App\Components\SupplyContract;
use App\Models\Farmer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PutFarmerNoRevisions implements ShouldQueue
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
     * @param Farmer $farmer
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
         * @var $spc ContractV2
         */
        $spc = app('spcv2');

        if($tx_hash = $spc->putFarmer($this->farmer)){
            $this->farmer->tx_farm = $tx_hash;
            $this->farmer->save();
        }

        app('spcv3')->putFarmer($this->farmer);

    }
}
