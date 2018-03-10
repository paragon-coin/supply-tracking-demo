<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $eth;

    public function __construct()
    {
        $this->eth = app('eth');
    }

    public function index()
    {
        return view('dashboard.index')->with('dashboard_params', array('title'=>'Dashboard', 'active_li_main'=>'dashboard'));
    }
}
