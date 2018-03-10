<?php

namespace App\Http\Controllers;

use App\Components\Ethereum;
use Illuminate\Http\Request;

class EthController extends Controller
{
    /**
     * @var Ethereum
     */
    public $eth;

    public function __construct()
    {
        $this->eth = app('eth');
    }

    public function getTransaction(Request $request)
    {
        $data = $request->all();
        if ($request->ajax()) {
            if (isset($data['hash'])) {
                $transaction = $this->eth->eth_getTransactionByHash($data['hash']);
                if(is_null($transaction) || $transaction == '0x0'){
                    $response = array('status' => false);
                }
                else{
                    $transaction['gas'] = $this->eth->decode_hex($transaction['gas']);
                    $transaction['gasPrice'] = $this->eth->decode_hex($transaction['gasPrice']);
                    $transaction['decoded_input'] = $this->eth->decode_input($transaction['input'], $data['tType']);
                    $response = array(
                        'status' => true,
                        'data' => $transaction,
                    );
                }
            }
            else{
                $response = array('status' => false);
            }

            return response()->json($response);
        }
        else{
            abort(404);
        }
    }

    public function readTransaction(Request $request, $hash, $type){

        $tx = $this->_tx_data($hash, $type);

        return ($request->query('render', 'false') === 'true')
            ? $this->_tx_render($tx)
            : $tx;

    }

    protected function _tx_data($hash, $type){

        $tx = $this->eth->eth_getTransactionByHash($hash);
        if( !empty($tx) ){
            $tx['gas'] = $this->eth->decode_hex($tx['gas']);
            $tx['gasPrice'] = $this->eth->decode_hex($tx['gasPrice']);
            $tx['decoded_input'] = $this->eth->decodeData($tx['input'], $type, 'data');
        }

        return $tx;

    }

    protected function _tx_render($tx){

        $input = $tx['decoded_input'];
        $tx['decoded_input'] = @json_decode( $input, true );
        $tx['decoded_input'] = (is_array( $tx['decoded_input'] ))
            ? $tx['decoded_input']
            : $input;
        if(
            array_key_exists('data', $tx['decoded_input']) and
            array_key_exists('json', $tx['decoded_input']['data'])
        ){
            $json = $tx['decoded_input']['data']['json'];
            $tx['decoded_input']['data']['json'] = @json_decode( $json, true );
            $tx['decoded_input']['data']['json'] = (is_array($tx['decoded_input']['data']['json']))
                ? $tx['decoded_input']['data']['json']
                : $json;

            //$tx['decoded_input']['data'] = array_dot($tx['decoded_input']['data']);
        }


        return view('transaction.layout', compact('tx'));

    }

}
