<?php

namespace App\Jobs;

use App\Components\SupplyContract;
use App\Models\Laboratory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PutLaboratoryFilesToBlockchain implements ShouldQueue
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
     * @param Laboratory $lab
     */
    public function __construct( Laboratory $lab )
    {
        $this->lab = $lab;
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

        if($hash = $spc->putLaboratoryFiles($this->lab)){
            $this->lab->tx_files = $hash;
            $this->lab->save();
        }

    }

}
