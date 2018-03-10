<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerFile;
use App\Models\FarmerProperty;
use App\Models\Harvest;
use App\Models\HarvestExpertise;
use App\Models\Laboratory;
use App\Models\LaboratoryFile;
use App\Models\LaboratoryProperty;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Schema;

class RecoveryController extends Controller
{
    public function drop()
    {
        $owner = auth()->user()->uuid;
        $farmers = Farmer::where('uuid', $owner)->get();
        if ($farmers) {
            foreach ($farmers as $farmer) {
                $farmer->files()->delete();
                $farmer->harvest()->delete();
                $farmer->properties()->delete();
                $farmer->delete();
            }
        }

        $labs = Laboratory::where('uuid', $owner)->get();
        if ($labs) {
            foreach ($labs as $lab) {
                $lab->files()->delete();
                $lab->properties()->delete();
                $lab->expertise()->delete();
                $lab->delete();
            }
        }
        return redirect()->route('home')->with('error', 'All farmers have been deleted!');
    }

    public function recovery()
    {
        $growersCount = 0;
        $growersCount = $this->recoveryGrowers();
        $harvestsCount = 0;
        $harvestsCount = $this->recoveryHarvests();
        $labsCount=0;
        $labsCount = $this->recoveryLabs();
        $expertisesCount=0;
        $expertisesCount = $this->recoveryExpertises();
        return redirect()->route('home')
            ->with('message', "Recovered growers: {$growersCount}, harvests: {$harvestsCount}, labs: {$labsCount}, expertises: {$expertisesCount}");
    }

    public function recoveryGrowers()
    {
        // farmer table recovery
        $logs = app('spcv2')->getLogs('grower');

        Farmer::$applyBootEvents = false;

        $count = 0;

        if ($logs) {
            foreach ($logs as $log) {

                if (!$this->logHasHasCurrentUserId($log)) continue;

                $grower = Farmer::where('eth_address', $log['eth_address'])->first();
                if ($grower) {

                } else {
                    $grower = new Farmer($log);
                    $grower->created_at = Carbon::parse($log['created_at']);
                    $grower->json_props = json_encode($log['props_batched']);
                    $grower->json_files = json_encode($log['files_batched']);

                    if ($grower->save()) {

                        $count++;

                        if ($log['props_batched']) {
                            $grower->properties()->createMany($log['props_batched']);
                        }

                        if ($log['files_batched']) {
                            $grower->files()->createMany($log['files_batched']);
                        }

                        if (isset($log['logInfo']['transactionHash'])) {
                            $grower->tx_farm = $log['logInfo']['transactionHash'];
                            $grower->save();
                        }
                    }

                }
            }
        }

        return $count;
    }

    public function recoveryHarvests()
    {
        $logs = app('spcv2')->getLogs('rawMaterial');
        $count = 0;

        if ($logs) {
            foreach ($logs as $log) {
                if (isset($log['eth_address'])) {
                    $rawMaterial = Harvest::where('eth_address', $log['eth_address'])
                        ->where('uid', $log['uid'])
                        ->first();

                    /**
                     * Check if this harvest belongs to current_user;
                     */
                    $farmer = Farmer::where('eth_address', $log['eth_address'])->first();
                    if (!$farmer) continue;
                    if($farmer->uuid != auth()->user()->uuid) continue;

                    if (!$rawMaterial) {
                        $harvest = new Harvest($log);
                        $created_at = $log['created_at']['date'] ?? $log['created_at'];
                        $harvest->created_at = Carbon::parse($created_at);
                        if ($harvest->save()) {
                            $harvest->tx = $log['logInfo']['transactionHash'];
                            $harvest->save();
                            $count++;
                        }
                    }
                }
            }
        }
        return $count;
    }

    public function recoveryLabs()
    {
        $logs = app('spcv2')->getLogs('lab');

        Laboratory::$applyBootEvents = false;

        $count = 0;

        if ($logs) {
            foreach ($logs as $log) {

                if (!$this->logHasHasCurrentUserId($log)) continue;

                $lab = Laboratory::where('eth_address', $log['eth_address'])->first();
                if ($lab) {

                } else {
                    $lab = new Laboratory($log);
                    $created_at = $log['created_at']['date'] ?? $log['created_at'];
                    $lab->created_at = Carbon::parse($created_at);
                    $lab->json_props = json_encode($log['props_batched']);
                    $lab->json_files = json_encode($log['files_batched']);

                    if ($lab->save()) {

                        $count++;

                        if ($log['props_batched']) {
                            $lab->properties()->createMany($log['props_batched']);
                        }

                        if ($log['files_batched']) {
                            $lab->files()->createMany($log['files_batched']);
                        }

                        if (isset($log['logInfo']['transactionHash'])) {
                            $lab->tx_lab = $log['logInfo']['transactionHash'];
                            $lab->save();


                        }
                    }

                }
            }
        }

        return $count;
    }

    public function recoveryExpertises()
    {
        $logs = app('spcv2')->getLogs('expertise');
        $count = 0;

        if ($logs) {

            foreach ($logs as $log) {
                if (isset($log['uid'])) {
                    $expertise = HarvestExpertise::where('uid', $log['uid'])->first();

                    $laboratory = Laboratory::where('eth_address', $log['eth_address_lab'])->first();
                    if (!$laboratory) continue;
                    if($laboratory->uuid != auth()->user()->uuid) continue;


                    if (!$expertise) {
                        $expertise = new HarvestExpertise($log);
                        $created_at = $log['created_at']['date'] ?? $log['created_at'];
                        $expertise->created_at = Carbon::parse($created_at);
                        if ($expertise->save()) {
                            $count++;

                            $expertise->tx = $log['logInfo']['transactionHash'] ?? null;
                            $expertise->save();
                        }
                    }
                }
            }
        }
        return $count;
    }

    protected function logHasHasCurrentUserId($log)
    {
        return $log && !empty($log['uuid']) && $log ['uuid'] == auth()->user()->uuid;
    }
}
