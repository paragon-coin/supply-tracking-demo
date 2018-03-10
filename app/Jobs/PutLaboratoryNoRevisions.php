<?php

namespace App\Jobs;

use App\Components\ContractV2;
use App\Models\Laboratory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PutLaboratoryNoRevisions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * @var Laboratory
     */
    protected $lab;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Laboratory $laboratory)
    {

        $this->lab = $laboratory;

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

        if($tx_hash = $spc->putLaboratory($this->lab)){
            $this->lab->tx_lab = $tx_hash;
            $this->lab->save();
        }

        app('spcv3')->putLaboratory($this->lab);

    }

}
