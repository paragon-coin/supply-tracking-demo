<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ether_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address');
            $table->string('secret_phrase');
            $table->timestamps();
        });
        
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tx');
            $table->unsignedInteger('status')->default(\App\Models\Transaction::TX_EXEC_PENDING);
            $table->timestamps();
        });
        
        Schema::create('farmers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('address');
            $table->string('tx_farm_id')->nullable();
            $table->string('tx_props_id')->nullable();
            $table->string('tx_files_id')->nullable();
            $table->text('json_props')->nullable();
            $table->text('json_files')->nullable();
            $table->string('gm_lat')->nullable();
            $table->string('gm_lon')->nullable();
            $table->string('gm_place_id')->nullable();
            $table->string('eth_address')->nullable();
            $table->string('uuid')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('farmer_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value')->nullable();
            $table->string('eth_address')->nullable();
            $table->timestamps();
        });

        Schema::create('farmer_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('extension')->nullable();
            $table->string('bytes')->nullable();
            $table->string('crc32')->nullable();
            $table->string('sha512')->nullable();
            $table->string('md5')->nullable();
            $table->string('eth_address')->nullable();
            $table->timestamps();
        });

        Schema::create('harvests', function (Blueprint $table) {
            $table->increments('id');

            $table->string('strain_harvested');
            $table->integer('number_of_plants')->nullable();
            $table->string('weight_measurement');

            $table->float('wet_plant')->nullable();
            $table->float('wet_trim')->nullable();
            $table->float('wet_flower')->nullable();
            $table->float('dry_trim')->nullable();
            $table->float('dry_flower')->nullable();
            $table->integer('seeds')->nullable();
            $table->float('total_usable_flower')->nullable();
            $table->float('total_usable_trim')->nullable();

            $table->string('tx_id')->nullable();
            $table->string('eth_address')->nullable();
            $table->string('uid')->nullable();
            $table->string('uuid')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('laboratories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->unsignedInteger('tx_lab_id')->nullable();
            $table->unsignedInteger('tx_props_id')->nullable();
            $table->unsignedInteger('tx_files_id')->nullable();
            $table->text('json_props')->nullable();
            $table->text('json_files')->nullable();
            $table->string('gm_lat')->nullable();
            $table->string('gm_lon')->nullable();
            $table->string('gm_place_id')->nullable();
            $table->string('eth_address')->nullable();
            $table->string('uuid')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('laboratory_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value')->nullable();
            $table->string('eth_address')->nullable();
            $table->timestamps();
        });

        Schema::create('laboratory_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('extension')->nullable();
            $table->string('bytes')->nullable();
            $table->string('crc32')->nullable();
            $table->string('sha512')->nullable();
            $table->string('md5')->nullable();
            $table->string('eth_address')->nullable();
            $table->timestamps();
        });

        Schema::create('harvest_expertises', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('laboratory_id')->nullable();
            $table->text('conclusion')->nullable();
            $table->string('tx')->nullable();
            $table->string('farmer_name')->nullable();
            $table->string('farmer_address')->nullable();
            $table->string('farmer_harvest')->nullable();
            $table->unsignedInteger('type')->nullable();
            $table->unsignedInteger('tx_id')->nullable();
            $table->string('eth_address_lab')->nullable();
            $table->string('uid')->nullable();
            $table->string('eth_address')->nullable();
            $table->string('harvest_uid')->nullable();
            $table->string('uuid')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmer_files');
        Schema::dropIfExists('farmer_properties');
        Schema::dropIfExists('farmers');
        Schema::dropIfExists('harvest_expertises');
        Schema::dropIfExists('harvests');
        Schema::dropIfExists('laboratory_files');
        Schema::dropIfExists('laboratory_properties');
        Schema::dropIfExists('laboratories');
    }
}
