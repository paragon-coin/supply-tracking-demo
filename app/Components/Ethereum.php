<?php

namespace App\Components;

use Graze\GuzzleHttp\JsonRpc\Client;
use Exception;
use Log;

class Ethereum
{
    protected $client;
    protected $id = 0;
    protected $convertHexToDecimal = true;

    private $farmer = array('farmer_id', 'firstname', 'lastname', 'email', 'address');
    private $farmer_types_mask = array('int', 'string', 'string', 'string', 'string');

    private $inputMapper = [

        'lab' => [
            'id' => 'int',
            'name' => 'string',
            'address' => 'string',
        ],
        'id-json' => [
            'id' => 'int',
            'json' => 'string',
        ],
        'labProps' => [
            'laboratoryID' => 'int',
            'properties' => 'string',
        ],
        'labFiles' => [
            'laboratoryID' => 'int',
            'files' => 'string',
        ],
        'farmProps' => [
            'farmerID' => 'int',
            'properties' => 'string',
        ],
        'farmFiles' => [
            'farmerID' => 'int',
            'files' => 'string',
        ],
        'harvest' => [
            'HarvestID' => 'int',
            'FarmerID' => 'int',
            'CreatedAt' => 'string',
            'Amount' => 'string',
            'Units' => 'string',
            'PlantVariety' => 'string',
        ],
        'expertise' => [
            'newExpertiseId' => 'int',
            'labId' => 'int',
            'date' => 'string',
            'labExpertiseInfo' => 'string',
        ],

    ];


    public function __construct()
    {
        $this->client = Client::factory(setting('eth.rpc_url'));
    }

    public function __call($method, $params)
    {
        return $this->call($method, $params);
    }

    protected function call($method, array $params = [])
    {
        try {
            $response = $this->client->send($this->client->request($this->id++, $method, $params));
            $data = json_decode($response->getBody(), true);
            if (isset($data['error'])) {
                $code = $data['error']['code'] ?? '';
                $message = $data['error']['message'] ?? '';
                Log::error("ETH API - code: $code, method: $method, message: $message");
            } else {
                Log::info("ETH API called {$method}");
            }
            return $data['result'] ?? null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates new account.
     *
     * @param string $secretPhrase
     * @return array|bool
     */
    public function create_account($secretPhrase = null)
    {
        $secretPhrase = $secretPhrase ?? str_random(rand(60, 80));
        if ($address = $this->personal_newAccount($secretPhrase)) {
            return [
                'address' => $address,
                'secret_phrase' => $secretPhrase,
            ];
        }
        return false;
    }

    /**
     * Returns Keccak-256 (not the standardized SHA3-256) of the given data.
     * @link https://github.com/ethereum/wiki/wiki/JSON-RPC#web3_sha3
     *
     * @param $data . The data to convert into a SHA3 hash.
     * @return string
     */
    public function web3_sha3($data)
    {
        return $this->call('web3_sha3', [$data]);
    }

    public function convertParam($param)
    {
        return sprintf('%0064s', $param);
    }

    public function leftPad32($string)
    {
        return str_pad($string, 64, '0', STR_PAD_LEFT);
    }

    /**
     * Test if a string is prefixed with "0x".
     *
     * @param string $string
     *   String to test prefix.
     *
     * @return bool
     *   TRUE if string has "0x" prefix or FALSE.
     */
    public function hasHexPrefix($string)
    {
        return substr($string, 0, 2) === '0x';
    }

    /**
     * Remove Hex Prefix "0x".
     *
     * @param string $string
     *   String with prefix.
     *
     * @return string
     *   String without prefix.
     */
    public function removeHexPrefix($string)
    {
        if (!$this->hasHexPrefix($string)) {
            throw new \InvalidArgumentException('String is not prefixed with "0x".');
        }
        return substr($string, 2);
    }

    /**
     * Add Hex Prefix "0x".
     *
     * @param string $string
     *   String without prefix.
     *
     * @return string
     *   String with prefix.
     */
    public function ensureHexPrefix($string)
    {
        return $this->hasHexPrefix($string) ? $string : '0x' . $string;
    }

    public function decimalToHex($value)
    {
        return '0x' . dechex($value);
    }

    /**
     * Converts a string to Hex.
     *
     * @param string $string
     *   String to be converted.
     *
     * @return string
     *   Hex representation of the string.
     */
    public function strToHex($string)
    {
        $hex = unpack('H*', $string);
        return '0x' . array_shift($hex);
    }

    /**
     * Converts Hex to string.
     *
     * @param string $string
     *   Hex string to be converted to UTF-8.
     *
     * @return string
     *   String value.
     *
     * @throws \Exception
     */
    public function hexToStr($string)
    {
        if (!$this->hasHexPrefix($string)) {
            throw new \Exception('String is missing Hex prefix "0x" : ' . $string);
        }
        $string = substr($string, strlen('0x'));
        $utf8 = '';
        $letters = str_split($string, 2);
        foreach ($letters as $letter) {
            $utf8 .= html_entity_decode("&#x$letter;", ENT_QUOTES, 'UTF-8');
        }
        return $utf8;
    }

    public function getDataHead($type)
    {
        $obj = new \stdClass();
        switch (true) {
            case (strpos($type, '@') === 0):
                list($method, $mapper) = app('spcv2')->getFunctions(substr($type, 1));

                $obj->types = $mapper;
                $model = array_fill(0, count($mapper), 'arg');
                foreach ($model as $key => &$value) {
                    $value = $value . '_' . $key . '_' . $mapper[$key];
                    unset($value);
                };
                $obj->model = $model;
                return $obj;
            case (in_array($type, ['farmer', 'farm'])):
                $obj->model = $this->farmer;
                $obj->types = $this->farmer_types_mask;
                return $obj;
            case (array_key_exists($type, $this->inputMapper)):
                $obj->model = array_keys($this->inputMapper[$type]);
                $obj->types = array_values($this->inputMapper[$type]);
                return $obj;
            default:
                return null;
        }
    }

    public function prepareData($method, $params)
    {
        $request_data = '';
        $body_request = array();

        list($method, $paramsTypes) = app('spcv2')->getFunctions($method);

        foreach ($params as $key => &$head_item) {
            $currentType = $paramsTypes[$key];
            switch ($currentType) {
                case 'bytes32':
                    $head_item = $this->right_zero_padded(bin2hex($head_item), 64);
                    break;
                case 'int':
                    $head_item = $this->left_zero_padded(base_convert($head_item, 10, 16), 64);
                    break;
                case 'address':
                    $head_item = $this->left_zero_padded($head_item, 64);
                    break;

                case 'string':
                    $head_item_value = $head_item;
                    $count_bytes_before_head = (count($params) + count($body_request)) * 32;
                    $head_item = $this->left_zero_padded(base_convert($count_bytes_before_head, 10, 16), 64);

                    $string_len = strlen($head_item_value);
                    $body_request[] = $this->left_zero_padded(base_convert($string_len, 10, 16), 64);

                    $k = ceil(strlen(implode(unpack("H*", $head_item_value))) / 64);
                    $value = $this->right_zero_padded(implode(unpack("H*", $head_item_value)), $k * 64);

                    $value_arr = str_split($value, 64);
                    foreach ($value_arr as $value_arr_element) {
                        $body_request[] = $value_arr_element;
                    }
                    break;

            }

        }

        $request_data .= $method;
        foreach ($params as $item)
            $request_data .= $item;

        foreach ($body_request as $item)
            $request_data .= $item;

        return $request_data;
    }

    public function decodeData($input, $type, $dataKey = null)
    {

        $encoderInfo = $this->getDataHead($type);

        $results = [];
        if (!empty($dataKey)) {
            $results[$dataKey] = [];
            $data = &$results[$dataKey];
        } else {
            $data = &$results;
        }

        $input = substr($input, 10);

        $blockPos = 0;
        foreach ($encoderInfo->model as $idx => $argumentName) {

            $argumentType = $encoderInfo->types[$idx];

            switch (strtolower($argumentType)) {

                case 'string':

                    $string_block_link = base_convert(
                            substr(
                                $input,
                                $blockPos * 64,
                                64
                            ),
                            16,
                            10
                        ) * 2;
                    $string_len = intval(base_convert(substr($input,
                        $string_block_link, 64), 16, 10));

                    $string = hex2bin(substr($input, $string_block_link + 64,
                        $string_len * 2));

                    $data[$argumentName] = $string;
                    $blockPos++; # block link : string first marker
                    $blockPos++; # block int : string second marker


                    break;

                case 'address':

                    $data[$argumentName] = substr($input, $blockPos * 64, 64);
                    $data[$argumentName] = '0x' . substr($data[$argumentName], 24);
                    $blockPos++; # block size is only 64 chars

                    break;

                case 'bytes32':

                    $data[$argumentName] = substr($input, $blockPos * 64, 64);
                    $data[$argumentName] = hex2bin($data[$argumentName]);
                    $blockPos++; # block size is only 64 chars

                    break;

                case 'int':
                    $data[$argumentName] = '0xTODO';
                    $blockPos++;

                    break;

            }

        }

//        dd($results, $encoderInfo);

        return $results;


    }

    public function decode_input($input, $type, $dataKey = null)
    {
        $head_types_arr = $this->getDataHead($type);
        $decoded_data = '';

        if (!is_null($head_types_arr)) {
            $method = substr($input, 0, 10);
            $dec_input = array('method' => $method);
            if ($dataKey) {
                $dec_input[$dataKey] = [];
                $storage = &$dec_input[$dataKey];

            } else {
                $storage = &$dec_input;
            }

            $input = substr($input, 10);

            foreach ($head_types_arr->types as $i => $head_type) {
                if ($head_type == 'int') {
                    $storage[$head_types_arr->model[$i]] = base_convert(substr($input, $i * 64, 64), 16, 10);
                } elseif ($head_type == 'string') {
                    $string_block_link = base_convert(substr($input, $i * 64, 64), 16, 10) * 2;
                    $string_len = intval(base_convert(substr($input, $string_block_link, 64), 16, 10));

                    $string = hex2bin(substr($input, $string_block_link + 64, $string_len * 2));
                    if (strlen($string) == $string_len)
                        $storage[$head_types_arr->model[$i]] = $string;

                }
            }

            $decoded_data = json_encode($dec_input);
        }
        return $decoded_data;
    }

    public function decode_hex($input)
    {
        if (substr($input, 0, 2) == '0x')
            $input = substr($input, 2);

        if (preg_match('/[a-f0-9]+/', $input))
            return hexdec($input);

        return $input;
    }

    public function encode_hex($input)
    {
        return "0x{$input}";
    }

    public function eth_sendTransaction($params)
    {
        $result = $this->call('eth_sendTransaction', [$params]);
        return (string)$result;
    }

    public function eth_getBalance($address, $blockNumberOrTag = 'latest')
    {
        $result = $this->call('eth_getBalance', [$address, $blockNumberOrTag]);
        return $result && is_string($result) && $this->convertHexToDecimal
            ? hexdec($result)
            : $result;
    }

    public function eth_getCode($address, $blockNumberOrTag = 'latest')
    {
        $result = $this->call('eth_getCode', [$address, $blockNumberOrTag]);
        return $result;
    }

    public function eth_getTransactionCount($address, $blockNumberOrTag = 'latest')
    {
        $result = $this->call('eth_getTransactionCount', [$address, $blockNumberOrTag]);
        return $result && is_string($result) && $this->convertHexToDecimal
            ? hexdec($result)
            : $result;
    }

    public function eth_gasPrice()
    {
        $data = $this->call('eth_gasPrice');
        $gasprice = isset($data) ? hexdec($data) : 0;

        return $gasprice;
    }

    public function fee($gas = 21000, $gasPrice = null)
    {
        $gasPrice = $gasPrice ?? $this->eth_gasPrice();
        return $gasPrice * $gas;
    }

    public function except_fee($value, $gas = 21000, $gasPrice = null)
    {
        return $value - $this->fee($gas, $gasPrice);
    }

    public function decimalWeiToEth($wei = 0)
    {
        return $wei * 1E-18;
    }

    public function decimalEthToWei($eth = 0)
    {
        return $eth * 1E18;
    }

    public function eth_getTransactionByHash($hash)
    {
        $result = $this->call('eth_getTransactionByHash', [$hash]);
        return $result && is_string($result) && $this->convertHexToDecimal
            ? hexdec($result)
            : $result;
    }

    public function right_zero_padded($param, $length)
    {

        if (substr($param, 0, 2) == "0x")
            $param = substr($param, 2);

        $param = str_pad($param, $length, "0", STR_PAD_RIGHT);
        return $param;
    }

    public function left_zero_padded($param, $length)
    {

        if (substr($param, 0, 2) == "0x")
            $param = substr($param, 2);

        $param = str_pad($param, $length, "0", STR_PAD_LEFT);
        return $param;
    }

    // RPC HELPERS

    /**
     * @param $tx string        transaction hash
     *
     * @return bool|null        TRUE|FALSE as status "inBlockchain" or null if pending
     */
    public function statusOf($tx)
    {

        $status = null; # NULL === "pending"

        $result = $this->eth_getTransactionReceipt($tx);

        if (!empty($result) and is_array($result)) {

            switch ($result['status']) {

                case ('0x1'):
                    $status = true;
                    break;
                case ('0x0'):
                    $status = false;
                    break;

            }

        }

        return $status;
    }

    public function dataMapper($method, array $types, array $arguments)
    {
        $request_data = '';
        $body_request = array();


        foreach ($types as $idx => $type) {

            $value = $arguments[$idx];

            switch ($type) {

                case ('string'):

                    $head_item_value = $value;
                    $count_bytes_before_head = (count($arguments) + count($body_request)) * 32;
                    $head_item = $this->left_zero_padded(base_convert($count_bytes_before_head, 10, 16), 64);

                    $string_len = strlen($head_item_value);
                    $body_request[] = $this->left_zero_padded(base_convert($string_len, 10, 16), 64);

                    $k = ceil(strlen(implode(unpack("H*", $head_item_value))) / 64);
                    $value = $this->right_zero_padded(implode(unpack("H*", $head_item_value)), $k * 64);

                    $value_arr = str_split($value, 64);
                    foreach ($value_arr as $value_arr_element) {
                        $body_request[] = $value_arr_element;
                    }

                    break;

            }


        }

        foreach ($params as &$head_item) {
            if (is_int($head_item))
                $head_item = $this->left_zero_padded(base_convert($head_item, 10, 16), 64);
            else if (is_string($head_item)) {
                $head_item_value = $head_item;
                $count_bytes_before_head = (count($params) + count($body_request)) * 32;
                $head_item = $this->left_zero_padded(base_convert($count_bytes_before_head, 10, 16), 64);

                $string_len = strlen($head_item_value);
                $body_request[] = $this->left_zero_padded(base_convert($string_len, 10, 16), 64);

                $k = ceil(strlen(implode(unpack("H*", $head_item_value))) / 64);
                $value = $this->right_zero_padded(implode(unpack("H*", $head_item_value)), $k * 64);

                $value_arr = str_split($value, 64);
                foreach ($value_arr as $value_arr_element) {
                    $body_request[] = $value_arr_element;
                }
            }
        }

        $request_data .= $method;
        foreach ($params as $item)
            $request_data .= $item;

        foreach ($body_request as $item)
            $request_data .= $item;

        return $request_data;
    }

}