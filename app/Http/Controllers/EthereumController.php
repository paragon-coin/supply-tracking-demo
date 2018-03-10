<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EthereumController extends Controller
{
    public $eth;

    public function __construct()
    {
        $this->eth = app('eth');
    }

//    public function index()
//    {
//        return view('ethereum.index', ['eth' => $this->eth]);
//    }

    public function index()
    {
        return view('ethereum.index', ['eth' => $this->eth]);
    }
}
