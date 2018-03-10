<?php

namespace App\Models;

use App\Scopes\CurrentUserUUIDScope;
use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{

    protected $fillable = [
        'strain_harvested',
        'number_of_plants',
        'weight_measurement',
        'wet_plant',
        'wet_trim',
        'wet_flower',
        'dry_trim',
        'dry_flower',
        'seeds',
        'total_usable_flower',
        'total_usable_trim',

        'tx',
        'tx_id',
        'eth_address',
        'uid',
        'uuid'
    ];

    protected $appends = [
        'tx',
    ];

    ##      ORM Relations
    public function farmer(){

        return $this->belongsTo(Farmer::class,'eth_address','eth_address');

    }

    public function expertise(){

        return $this->hasMany(HarvestExpertise::class, 'harvest_uid', 'uid');

    }

    public function transaction(){

        return $this->belongsTo(Transaction::class, 'tx_id');

    }

    ##      Mutators

    public function getTxAttribute()
    {
        if(is_null($this->tx_id))
            return null;

        $record = $this->transaction()->get()->first();
        return (!empty($record))
            ? $record->tx
            : null;

    }

    public function setTxAttribute($value){

        $tx = Transaction::updateOrCreate(
            ['tx' => $value],
            ['status' => Transaction::TX_EXEC_PENDING]
        );
        $this->tx_id = $tx->id;

    }

    public static function blockChainFormat(Harvest $harvest){

        return $harvest->only([

            'strain_harvested',
            'number_of_plants',
            'weight_measurement',
            'wet_plant',
            'wet_trim',
            'wet_flower',
            'dry_trim',
            'dry_flower',
            'seeds',
            'total_usable_flower',
            'total_usable_trim',
            'eth_address',
            'uid',
            'created_at',
            'uuid',
        ]);

    }

    public static function boot()
    {
        parent::boot();

        static::creating([static::class, 'eventCreatingUID']);
        static::addGlobalScope(new CurrentUserUUIDScope());
    }

    protected function eventCreatingUID(Harvest $harvest){

        if(empty($harvest->uid)){

            $harvest->uid = uniqueID_withMixing(32, 0,[
                $harvest->strain_harvested,
                $harvest->weight_measurement,
                $harvest->created_at,
            ]);

        }

    }
}
