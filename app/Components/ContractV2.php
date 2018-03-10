<?php

namespace App\Components;

use App\Models\Farmer;
use App\Models\Harvest;
use App\Models\HarvestExpertise;
use App\Models\Laboratory;
use Graze\GuzzleHttp\JsonRpc\Client;
use Illuminate\Support\Str;

class ContractV2
{

    const GAZ = 4700000;

    /**
     * @var Ethereum
     */
    protected $eth;
    protected $address;
    protected $convertHexToDecimal = true;
    protected $userAddress;
    protected $userSecretPhrase;

    /**
     * @see supply_tracking/SmartContracts/new.sol


        FUNCTIONHASHES
        {
     *                     EVENTS:
            "f8aaa360": "expertises(address)"
            "277d5891": "labs(address)",
            "b38854be": "ownable()",
            "0a332353": "rawMaterials(address)",
     *                     METHODS
        }
        {
            "a6f9dae1": "changeOwner(address)",
            "a44e1a8c": "expertises(bytes32)",
            "94ef3969": "growers(address)",
            "277d5891": "labs(address)",
            "b38854be": "ownable()",
            "1fbe9c51": "rawMaterials(bytes32)",
            "8d010d74": "setExpertise(bytes32,bytes32,address,string)",
            "97a5a8c1": "setGrower(address,string)",
            "169c2b9b": "setLab(address,string)",
            "0e7805a6": "setRawMaterial(bytes32,address,string)"
        }

     */

    public function __construct()
    {
        $this->eth = app('eth');
        $this->address = config('eth.supply_contract_address');
        $this->userAddress = config('eth.coinbase_address');
        $this->userSecretPhrase = config('eth.coinbase_secret_phrase');
    }

    public function getAddress(){
        return $this->address;
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

    protected $functions = [
        'setGrower' => ['0x97a5a8c1', ['address', 'string']],
        'setLab' => ['0x169c2b9b', ['address', 'string']],
        'setRawMaterial' => ['0x0e7805a6', ['bytes32', 'address', 'string']],
        'setExpertise' => ['0x8d010d74', ['bytes32', 'bytes32', 'address', 'string']],

        'growers' => ['0x94ef3969', ['address']],
        'labs' => ['0x277d5891', ['address']],
        'expertises' => ['0xa44e1a8c', ['bytes32']],
        'rawMaterials' => ['0x1fbe9c51', ['bytes32']],

        'changeOwner' => ['0xa6f9dae1', ['address']],
    ];

    #  -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -

    public function putFarmer(Farmer $farmer){

        $storedObject = Farmer::blockChainFormat($farmer);

        return $this->exec(
            $this->eth->prepareData(
                'setGrower',
                [
                    $farmer->eth_address,
                    json_encode($storedObject),
                ]
            )
        );

    }

    public function putLaboratory(Laboratory $laboratory){

        $storedObject = Laboratory::blockChainFormat($laboratory);

        return $this->exec(
            $this->eth->prepareData(
                'setLab',
                [
                    $laboratory->eth_address,
                    json_encode($storedObject),
                ]
            )
        );

    }

    public function putHarvest(Harvest $harvest){

        $storedObject = Harvest::blockChainFormat($harvest);

        return $this->exec(
            $this->eth->prepareData(
                'setRawMaterial',
                [
                    $harvest->uid,
                    $harvest->eth_address,
                    json_encode($storedObject),
                ]
            )
        );

    }

    public function putExpertise(HarvestExpertise $exp){

        $storedObject = HarvestExpertise::blockChainFormat($exp);

        return $this->exec(
            $this->eth->prepareData(
                'setExpertise',
                [
                    (string) $exp->uid,
                    (string) $exp->harvest_uid, # can be NULL
                    (string) $exp->eth_address_lab,
                    json_encode($storedObject),
                ]
            )
        );

    }

    protected function _stringDecode($str){
        $stringLengthBin = substr($str,65, 65);
        $stringLength = hexdec($stringLengthBin);
        $result = '0x'.substr($str, 130, $stringLength*2);
        return $this->eth->hexToStr($result);
    }

    protected function _requestContractData($varMethodHash, array $params){

        return app('eth')->eth_call([
            'to' => $this->address,
            'data' => $this->eth->prepareData($varMethodHash, $params)
        ], 'pending');

    }

    protected function _requestFor($method, array $params){

        return json_decode(
            $result = $this->_stringDecode(
                $this->_requestContractData($method, $params)
            ),
            true
        );

    }

    public function getExpertise($uid)
    {

        return $this->_requestFor('expertises', [$uid]);

    }

    public function getLab($address){

        return $this->_requestFor('labs', [$address]);

    }

    public function getHarvest($uid){

        return $this->_requestFor('rawMaterials', [$uid]);

    }
    public function getFarmer($address){

        return $this->_requestFor('growers', [$address]);

    }

    public function getLogs($signature = null, $id = null)
    {
        $topics = [];
        $outputData = [];

        if ($signature) {
            $signatureList = $this->getLogSignatures();
            if (in_array($signature, array_keys($signatureList))) {
                $signature = $signatureList[$signature];

                //$topics[] = $this->eth->web3_sha3('0x'.bin2hex($signature));
                $topics[] = $signature;
            }
        }
        if ($id) {
            $topics[] = '0x'.$this->eth->convertParam($id);
        }

        $logs = $this->eth->eth_getLogs([
            'fromBlock' => '0x1',
            'toBlock' => 'latest',
            'address' => $this->address,
            'topics' => $topics
        ]);

        if ($logs) {

            $signatureHashes = array_flip($this->getLogSignatures());
            foreach ($logs as $log) {
                $topics = $log['topics'];
                $data = $log['data'];
                if ($data) {
                    $stringLengthBin = substr($data,65, 65);
                    $stringLength = hexdec($stringLengthBin);
                    $data = '0x'.substr($data, 130, $stringLength*2);
                    $data = $this->eth->hexToStr($data);
                    $outputLog = json_decode($data, true);
                    if (isset($topics[0]) && isset($signatureHashes[$topics[0]])) {
                        $outputLog['signature'] = $signatureHashes[$topics[0]];
                    } else {
                        $outputLog['signature'] = 'unknown';
                    }
                    $outputLog['logInfo'] = $log;
                    $outputData[] = $outputLog;
                }
            }
        }

        return collect($outputData)->reverse();
    }

    public function getLogSignatures()
    {
        return [
            //'grower' => 'Grower(address,string)',
            'grower' => '0x5df01dfbc6a3d8ff75be7eefe3e3272927b561cb0b837003e2507141cf695b00',
            //'lab' => 'Lab(address,string)',
            'lab' => '0xd1fbd84079957408e9520ca7badddaf6b50d97bcbcca504224f4f68eb3f843f3',
            //'expertise' => 'Expertise(bytes32,bytes32,address,string)',
            'expertise' => '0x31c623dbce18369cfd09150263fed264f1664da6650d4fc1a3af23ce442aa135',
            //'rawMaterial' => 'RawMaterial(bytes32,address,string)',
            'rawMaterial' => '0xd6db2520747d56a3a7500efbf6dc90a63983c520cdfe38193c78f2fc3472e0ee',
        ];
    }

    protected function withMapper($name, array $arguments = []){

        list($methodHash, $dataMapper) = $this->functions[$name];

        return $this->eth->dataMapper($methodHash, $dataMapper, $arguments);

    }
    public function getFunctions( $funcName )
    {
        return $this->functions[ $funcName ];
    }


}


