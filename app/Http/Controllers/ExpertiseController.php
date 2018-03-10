<?php

namespace App\Http\Controllers;

use App\Jobs\PutExpertiseNoRevisions;
use App\Jobs\PutExprtiseResultToBlockchain;
use App\Models\Farmer;
use App\Models\Harvest;
use App\Models\HarvestExpertise;
use App\Models\Laboratory;
use DB;
use Illuminate\Http\Request;

class ExpertiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Laboratory $lab)
    {

        $list = $lab->expertise;

        return view('expertise.index', compact('lab', 'list'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexList()
    {

        $expertises = auth()->user()->expertises()->with('laboratory')->with('harvest')->get();

        return view('expertise.list', compact('expertises'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Laboratory $lab)
    {
        $farmers = auth()->user()->farmers;
        $harvests = auth()->user()->harvests;
        return view('expertise.create', array_merge(compact('lab'), [

            'farmers' => $farmers->toArray(),
            'harvests' => $harvests->toArray()

        ]));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Laboratory $lab)
    {

        $expertise = $lab->newExpertise();
        $expertise->conclusion = array_get($request->input('expertise', []), 'conclusion');

        $expertise->harvest_uid = array_get(
            $request->input('existing', []),
            'harvest_id'
        );
        $expertise->eth_address = array_get(
            $request->input('existing', []),
            'farmer_id'
        );
        $expertise->type = HarvestExpertise::TYPE_EXISTING_FARMER;
        $expertise->uuid = auth()->user()->uuid;


        try {

            DB::beginTransaction();

            $expertise->save();

            dispatch(new PutExpertiseNoRevisions($expertise));

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;

        }

        return response()->redirectTo(route(
            'expertise.show', [$lab, $expertise]
        ));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Laboratory $lab, HarvestExpertise $expertise)
    {
//        dd($expertise, $lab);
        return view('expertise.show', compact('lab', 'expertise'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
