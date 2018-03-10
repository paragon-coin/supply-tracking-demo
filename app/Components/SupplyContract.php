<?php

namespace App\Components;

use App\Models\Farmer;
use App\Models\Harvest;
use App\Models\HarvestExpertise;
use App\Models\Laboratory;
use Graze\GuzzleHttp\JsonRpc\Client;
use Illuminate\Support\Str;
use Log;

/**
 * https://github.com/ethereum/wiki/wiki/JSON-RPC
 *
 * Class PrgContract
 * @package App\Components
 */
class SupplyContract
{

    const GAZ = 4700000;

    protected $eth;
    protected $address;
    protected $convertHexToDecimal = true;
    protected $userAddress;
    protected $userSecretPhrase;

    /*

        "8a05af18": "createFarmer(uint256,string,string,string,string)",
        "c1b9d6f0": "createFarmerAdditionalInfo(uint256,string)",
        "00f88484": "createFarmerDocument(uint256,string)",
        "f15c5cd3": "createLab(uint256,string,string)",
        "139f1df8": "createLabAdditionalInfo(uint256,string)",
        "df345859": "createLabDocument(uint256,string)",
        "5a92c7e4": "createLabExpertise(uint256,uint256,string,string)",
        "af7e8f3a": "createProductLabel(uint256,bytes32,uint256,uint256)",
        "f08f7aa7": "createRawMaterial(uint256,uint256,string,string,string,string)",
        "507bd485": "farmerAdditionalInfo(uint256)",
        "0f05fb73": "farmerDocuments(uint256)",
        "8afdd669": "farmers(uint256)",
        "ae32a429": "labAdditionalInfo(uint256)",
        "c1789937": "labDocuments(uint256)",
        "4cb7551a": "labExpertises(uint256)",
        "4bb7c644": "labs(uint256)",
        "70e462a9": "productLabels(uint256)",
        "3d5422c4": "rawMaterials(uint256)",
        "f2fde38b": "transferOwnership(address)"

     */

    public function __construct()
    {
        $this->eth = app('eth');
        $this->address = config('eth.supply_contract_address');
        $this->userAddress = config('eth.coinbase_address');
        $this->userSecretPhrase = config('eth.coinbase_secret_phrase');
    }
    //FARM
    public function putFarmer( Farmer $farmer){
        $m = '0x8a05af18';
        # (
        #   uint256 newFarmerId,
        #   string newFirstName,
        #   string newLastName,
        #   string newEmail,
        #   string newCompanyAddress
        # )
        $request_params = [
            (int) $farmer->id,
            $farmer->firstname,
            $farmer->lastname,
            $farmer->email,
            $farmer->address
        ];

        $preparedData = $this->eth->prepareData($m, $request_params);
        Log::info('create farmer: ' . $preparedData);
        return $this->exec($preparedData);

    }

    public function putFarmerFiles( Farmer $farmer){

        $m = '0x00f88484';

        $files = $farmer->files->toArray();

        $result = null;
        if(count($files) > 0){

            $files = json_encode($files);

            $request_params = [(int) $farmer->id, $files];

            $result = $this->exec(
                $this->eth->prepareData($m, $request_params)
            );

        }

        return $result;

    }

    public function putFarmerProperties( Farmer $farmer){

        $m = '0xc1b9d6f0';

        $files = $farmer->properties->toArray();

        $result = null;
        if(count($files) > 0){

            $files = json_encode($files);

            $request_params = [(int) $farmer->id, $files];

            $result = $this->exec(
                $this->eth->prepareData($m, $request_params)
            );

        }

        return $result;

    }

    // LAB
    public function putLaboratory(Laboratory $laboratory){

        $m = '0xf15c5cd3';

        $request_params = [(int) $laboratory->id, $laboratory->name, $laboratory->address];

        return $this->exec(
            $this->eth->prepareData($m, $request_params)
        );

    }

    public function putLaboratoryFiles(Laboratory $lab)
    {

        $m = '0xdf345859';

        $files = $lab->files->toArray();

        $result = null;
        if(count($files) > 0){

            $files = json_encode($files);

            $request_params = [(int) $lab->id, $files];

            $result = $this->exec(
                $this->eth->prepareData($m, $request_params)
            );

        }

        return $result;

    }

    public function putLaboratoryProperties(Laboratory $lab){

        $m = '0x139f1df8';

        $result = null;

        $props = $lab->properties->toArray();

        if(!empty($props)){

            $props = json_encode($props);

            $request_params = [(int) $lab->id, $props];

            $result = $this->exec(
                $this->eth->prepareData($m, $request_params)
            );
        }

        return $result;

    }

    // HARVEST

    public function putHarvest(Harvest $harvest){

        /*
        "f08f7aa7": "createRawMaterial(uint256,uint256,string,string,string,string)",
        function createRawMaterial(
            uint256 newRawMaterialId,
            uint256 farmerId,
            string date,
            string amount,
            string units,
            string plantVariety
        )
         */

        return $this->exec(

            $this->eth->prepareData('0xf08f7aa7', [

                (int)       $harvest->id,
                (int)       $harvest->farmer_id,
                (string)    $harvest->created_at,
                (string)    $harvest->units,
                (string)    $harvest->amount,
                json_encode([
                    'text' => $harvest->plant_variety
                ])

            ])

        );

    }

    public function putExpertise(HarvestExpertise $expertise)
    {

        // "5a92c7e4": "
        //      uint256 newExpertiseId,
        //      uint256 labId,
        //      string date,
        //      string labExpertiseInfo"

        return $this->exec(

            $this->eth->prepareData('0x5a92c7e4', [

                (int)       $expertise->id,
                //(int)       $expertise->laboratory_id,
                (string)    $expertise->created_at,
                json_encode($expertise->batched_expertise)

            ])

        );

    }

//    public function balanceOf($address)
//    {
//        $m = '0x70a08231'; //$m = $this->method('balanceOf(address)');
//        $address =  $this->convertParam(ltrim($address, "0x"));
//        $result = $this->eth->eth_call([
//            'to' => $this->address,
//            'data' => $m.$address,
//        ], 'pending');
//
//        return $result && is_string($result) && $this->convertHexToDecimal
//            ? hexdec($result)
//            : $result;
//    }


    //Helpers

    public function method($signature)
    {
        $methodHash = $this->eth->web3_sha3('0x'.bin2hex($signature));
        $methodName =  substr($methodHash, 0, 10);
        return $methodName;
    }

    public function convertParam($param)
    {
        return sprintf('%0064s', $param);
    }

    private function exec($data){

        $this->eth->personal_unlockAccount($this->userAddress, $this->userSecretPhrase, 20);

        $result = $this->eth->eth_sendTransaction([
            'from' => $this->userAddress,
            'to' => $this->address,
            'data' => $data,
            'gas' => '0x'.dechex(static::GAZ)
        ]);

        $this->eth->personal_lockAccount($this->userAddress);

        return $result;

    }
}


