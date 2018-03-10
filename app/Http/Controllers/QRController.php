<?php

namespace App\Http\Controllers;

use App\Components\ContractV2;
use App\Models\HarvestExpertise;
use Illuminate\Http\Request;

class QRController extends Controller
{
    public function labelExpertise($uid){
        /** @var ContractV2 $spc */
        $spc = app('spcv2');

        $expertise = $spc->getExpertise( $uid );


        if(!empty($expertise)){

            $lab = $spc->getLab( $expertise['eth_address_lab'] );

            $harvest = (!empty($expertise['harvest_uid']))
                ? $spc->getHarvest( $expertise['harvest_uid'] )
                : ['plant_variety' => $expertise['farmer_harvest']];

            $farmer = [
                'existing' => ($expertise['type'] != 1),
                'data'      => ($expertise['type'] == 1)
                    ? array_only($expertise, ['farmer_name','farmer_address'])
                    : $spc->getFarmer( $expertise['eth_address'] )
            ];


            return view('qr.expertise_label', compact(
                'expertise',
                'lab',
                'harvest',
                'farmer'
            ));

        }else{

            return response(view('qr.404'), 404);

        }

    }

    public function labelHarvest($uid){
        /** @var ContractV2 $spc */
        $spc = app('spcv2');

        $harvest = $spc->getHarvest( $uid );


        if(!empty($harvest)){

            $farmer = $spc->getFarmer( $harvest['eth_address'] );

            return view('qr.harvest_label', compact(
                'harvest',
                'farmer'
            ));

        }else{

            return response(view('qr.404'), 404);

        }

    }
}
