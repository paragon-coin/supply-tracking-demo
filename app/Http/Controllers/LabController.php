<?php

namespace App\Http\Controllers;

use App\Jobs\PutLaboratoriesToBlockchain;
use App\Jobs\PutLaboratoryFilesToBlockchain;
use App\Jobs\PutLaboratoryNoRevisions;
use App\Jobs\PutLaboratoryPropertiesToBlockchain;
use App\Models\EtherAccounts;
use App\Models\Laboratory;
use App\Models\LaboratoryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $laboratories = Laboratory::latest()->get();

        return view('lab.index', compact('laboratories'))->render();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('lab.create')->render();

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

    protected function _store(Request $request)
    {

        $lab = new Laboratory();
        $lab->uuid = auth()->user()->uuid;
        $lab->name = array_get($request->input('lab'), 'name');
        $lab->address = array_get($request->input('lab'), 'address');

        /* google map api */
        if(is_array($gApi = $request->input('googleMapAPI',null))){
            $lab->gm_lat         = $gApi['lat'];
            $lab->gm_lon         = $gApi['lon'];
            $lab->gm_place_id    = $gApi['placeID'];
        }

        /* eth auto generated account */
        $ethAccount = new EtherAccounts();
        $ethAccount->save();
        $lab->eth_address = $ethAccount;

        $fileNames = $request->input('docsName',[]);
        foreach (($files = $request->files->get('docs', [])) as $idxFile => $file) {
            $fileName = trim(array_get($fileNames, $idxFile, ''));
            $lab->newUpload($file, $fileName);

        }

        $props = $request->input('lab_props', []);
        if (!empty($props)) {

            foreach ($props['key'] as $k => $optionName) {

                if (empty($optionName)) {
                    continue;
                }

                $optionValue = $props['value'][$k];

                $lab->newProperty($optionName, $optionValue);

            }

        }

        $lab->save();

        dispatch(new PutLaboratoryNoRevisions($lab));

        return response()->redirectTo(route('lab.show', $lab));

    }

    /**
     * Display the specified resource.
     *
     * @param  Laboratory $laboratory
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Laboratory $lab)
    {

        return view('lab.show', ['laboratory' => $lab])->render();

    }

    public function download(Request $request, $lab, $file){

        $filePath = storage_path(
                Laboratory::storageLocation($lab, false)
                . $file
            );

        $file = LaboratoryFile::where('eth_address',$lab)->where('sha512',$file)->get();
        
        if(!$file){
            abort(404);
        }
        $file = $file[0];

        if(\Illuminate\Support\Facades\File::exists($filePath)){

            return response()->download(
                $filePath,
                implode('.',[$file->filename , $file->extension])
            );

        }else{

            abort(404);

        }

    }

}
