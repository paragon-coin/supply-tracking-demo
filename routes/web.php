<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', 'DashboardController@index')->name('home');

    Route::get('/etx/tx/{hash}/{type}', 'EthController@readTransaction')
        ->name('tx.render')
        ->where('hash', '\d{1}x.+');

    Route::get('farmer/{farmer}/check-data-identity', 'FarmerController@checkDataIdentity')->name('farmer.check-data-identity');
    Route::post('farmer/{farmer}/hack', 'FarmerController@hackUpdate')->name('farmer.hack');
    Route::resource('farmer', 'FarmerController',[
        'only' => ['index', 'show', 'create', 'store', 'update', 'edit'],
    ]);
    Route::get('farmer/{farmer}/file/{file}/download','FarmerController@download')
        ->name('farmer.download');


    Route::resource('farmer/{farmer}/harvest', 'HarvestContorller',[
        'only' => ['index', 'show', 'create', 'store'],
    ]);
    Route::get('farmer/email/validate', function(Illuminate\Http\Request $request){

        $email = trim( array_get( $request->query('farmer',[]), 'email' ));
        //john.way@way-farmers-group.com
        $farmerID = trim($request->query('farmer_id'));

        $count = \App\Models\Farmer::where('email',  $email);
        if($farmerID){
            $count->where('id', '<>', $farmerID);
        }

        $count = $count
            ->get()
            ->count();

        return  $count > 0 ? "false"  : "true";
    })->name('farmer.validate');

    Route::resource('lab','LabController',[
        'only' => ['index', 'show', 'create', 'store'],
    ]);
    Route::get('lab/{lab}/file/{file}/download','LabController@download')->name('lab.download');

    Route::resource('lab/{lab}/expertise', 'ExpertiseController',[
        'only' => ['index', 'show', 'create', 'store'],
    ]);

    Route::get('transaction', 'TransactionController@index')->name('transaction.index');
    Route::get('harvest_list', 'HarvestContorller@indexList')->name('harvest.list');
    Route::get('expertise_list', 'ExpertiseController@indexList')->name('expertise.list');

    Route::get('drop', 'RecoveryController@drop')->name('drop');
    Route::get('recovery', 'RecoveryController@recovery')->name('recovery');
});

Route::get('/harvest/label/{uid}', 'QRController@labelHarvest')->name('qr.hLabel');
Route::get('/expertise/label/{uid}', 'QRController@labelExpertise')->name('qr.eLabel');


Auth::routes();

Route::get('verify/token/{token}', 'Auth\VerificationController@verify')->name('auth.verify');
Route::get('verify/resend', 'Auth\VerificationController@resend')->name('auth.resend');