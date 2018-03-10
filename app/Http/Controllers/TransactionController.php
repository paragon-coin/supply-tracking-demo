<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public $eth;

    public function __construct()
    {
        $this->eth = app('eth');
    }

    public function index(){
        $logs = app('spcv2')->getLogs();
        return view('transactions.index', ['eth' => $this->eth, 'logs' => $logs]);
    }

}
