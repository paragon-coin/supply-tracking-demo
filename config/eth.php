<?php

/**
 * expertise 0xC05194d555b82e70260Ee4F3a165412900B07B43
 * grower 0x3B9dB6408739e5e3d36EEE0353a4330019452416
 * lab 0xC2c49dC671d7D851eaD546159889a9cd89E2Cb8F
 * raw 0x2EDf1Fe74e65338EA139A89054F5cc8f4935cb40
 * main 0x17A7787878Bace75a8a790b036C7EFEfb05899BB
 */

return [
    'rpc_url' => env('ETH_RPC_URL', 'http://localhost:8545'),
    'coinbase_address' => env('ETH_C',''),
    'coinbase_secret_phrase' => env('ETH_CSF',''),
    'supply_contract_address' => env('ETH_SUPPLY_CONTRACT_ADDRESS',''),
    'supply_contract_address_new' => env('ETH_SUPPLY_CONTRACT_ADDRESS_NEW',''),
    'coinbase_password' => env('ETH_CP', ''),
];