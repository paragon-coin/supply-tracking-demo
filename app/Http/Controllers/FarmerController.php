<?php

namespace App\Http\Controllers;

use App\Jobs\PutFarmerNoRevisions;
use App\Models\EtherAccounts;
use App\Models\Farmer;
use App\Models\FarmerFile;
use Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;
use \Illuminate\Routing\Controller;

class FarmerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $farmers = auth()->user()->farmers()->latest()->get();

        return view('farmer.index', compact('farmers'))
            ->with('dashboard_params', array('title'=>'Farmers', 'active_li_main'=>'farmers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('farmer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
                $result = $this->_store($request);
            DB::commit();
        } catch (\Exception $e) {
                DB::rollBack();
            throw $e;
        }
        return $result;
    }

    protected function _store(Request $request, Farmer $farmer = null, EtherAccounts $ethAccount = null)
    {
        if($doInsert = (is_null($farmer))){
            $farmer = new Farmer();
            $farmer->uuid = auth()->user()->uuid;
        }

        /* separated accounts table */
        if(empty($ethAccount) and $doInsert){
            // auto generated account
            $ethAccount = new EtherAccounts();
            $ethAccount->save();
            $farmer->eth_address = $ethAccount;
        }
        /* main farmer record */
        $farmer->firstname  = array_get($request->input('farmer'), 'firstname');
        $farmer->lastname   = array_get($request->input('farmer'), 'lastname');
        $farmer->email      = array_get($request->input('farmer'), 'email');
        $farmer->address    = array_get($request->input('farmer'), 'address');

        /* google map api */
        if(is_array($gApi = $request->input('googleMapAPI',null))){
            $farmer->gm_lat         = $gApi['lat'];
            $farmer->gm_lon         = $gApi['lon'];
            $farmer->gm_place_id    = $gApi['placeID'];
        }

        /* file manager (existing files)*/
        $existingFiles = $request->input('existingFile', []);
        foreach ($existingFiles as $fileID => $fileOptions){
            if ($fileOptions['delete'] == 'y') {
                $farmer->setForDeletion($fileID);
            } else {
                $farmer->setForRenaming($fileID, $fileOptions['name']);
            }
        }

        /* file manager (new files)*/
        $fileNames = $request->input('docsName',[]);
        foreach (($files = $request->files->get('docs', [])) as $idxFile => $file) {
            $fileName = trim(array_get($fileNames, $idxFile, ''));
            $farmer->newUpload($file, $fileName);

        }

        $props = $request->input('farmer_props', []);
        if (!empty($props)) {
            foreach ($props['key'] as $k => $optionName) {
                if (empty($optionName))
                    continue;

                $optionValue = $props['value'][$k];
                $farmer->newProperty($optionName, $optionValue);
            }
        }

        $farmer->save();
        dispatch(new PutFarmerNoRevisions($farmer));

        return response()->redirectTo(route('farmer.show', $farmer));

    }

    /**
     * Display the specified resource.
     *
     * @param  Farmer $farmer
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Farmer $farmer)
    {
        return view('farmer.show', compact('farmer'));
    }

    /**
     * @param Request $request
     * @param Farmer  $farmer
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, Farmer $farmer)
    {
        try {
            DB::beginTransaction();
            $this->_store($request, $farmer);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('farmer.show',$farmer);
    }

    public function hackUpdate(Request $request, Farmer $farmer) {
        $validatedData = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:farmers,email,'.$farmer->id,
            'address' => 'required',
        ]);
        $farmer->fill($validatedData);
        $farmer->save();
        return redirect()
            ->route('farmer.show', $farmer)
            ->with('message', 'Data hacked!!!');
    }

    public function download($farmer, $file){

        $filePath = storage_path(Farmer::storageLocation($farmer, false) . $file);

        $file = FarmerFile::where('eth_address',$farmer)
            ->where('sha512', $file)
            ->firstOrFail();

        if(\Illuminate\Support\Facades\File::exists($filePath)) {
            return response()->download(
                $filePath,
                implode('.',[$file->filename , $file->extension])
            );
        } else {
            abort(404);
        }
    }


    /**
     * This is a resource update which is a HTTP PUT METHOD.
     * @param  Request $request
     * @param  Farmer $farmer
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Farmer $farmer)
    {
        return view('farmer.edit', compact('farmer'));
    }

    public function checkDataIdentity(Farmer $farmer)
    {
        $dataFromDB = Farmer::blockChainFormat($farmer);
        if ($farmer->tx_farm) {
            $tx = app('eth')->eth_getTransactionByHash($farmer->tx_farm);
            $data = app('eth')->decodeData($tx['input'], '@setGrower', 'data');
            $dataFromBlockchain = $data['data']['arg_1_string'];
            return response()
                ->json(['equal' => compare_data($dataFromDB, $dataFromBlockchain)]);
        }
        return response()->json(['error' => __('Something goes wrong')]);
    }

}