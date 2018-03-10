<?php

namespace App\Http\Controllers;

use App\Jobs\PutHarvestToBlockchain;
use App\Jobs\PutHarvsetNoRevisions;
use App\Models\Farmer;
use App\Models\Harvest;
use DB;
use Exception;
use Illuminate\Http\Request;

class HarvestContorller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Farmer $farmer)
    {

        $harvest = $farmer->harvest;

        return view('harvest.index', compact('farmer','harvest'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexList()
    {

        $harvests = auth()->user()->harvests()->with('farmer')->get();

        return view('harvest.list', compact('harvests'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Farmer $farmer)
    {

        return view('harvest.create', compact('farmer'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Farmer $farmer)
    {

        $input      = $request->input('harvest',[]);

        $harvest    = $farmer->newHarvest();
        $harvest->strain_harvested         = array_get($input, 'strain_harvested');
        $harvest->number_of_plants         = array_get($input, 'number_of_plants');
        $harvest->weight_measurement       = array_get($input, 'weight_measurement');
        $harvest->wet_plant                = array_get($input, 'wet_plant');
        $harvest->wet_trim                 = array_get($input, 'wet_trim');
        $harvest->wet_flower               = array_get($input, 'wet_flower');
        $harvest->dry_trim                 = array_get($input, 'dry_trim');
        $harvest->dry_flower               = array_get($input, 'dry_flower');
        $harvest->seeds                    = array_get($input, 'seeds');
        $harvest->total_usable_flower      = array_get($input, 'total_usable_flower');
        $harvest->total_usable_trim        = array_get($input, 'total_usable_trim');
        $harvest->uuid = auth()->user()->uuid;

        DB::beginTransaction();
        try{
            $harvest->save();

            DB::commit();

            dispatch(new PutHarvsetNoRevisions($harvest));

        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        return response()->redirectTo(route('harvest.show',[$farmer, $harvest]));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Farmer $farmer, Harvest $harvest)
    {
        return view('harvest.show',compact('farmer','harvest'));
    }


}
