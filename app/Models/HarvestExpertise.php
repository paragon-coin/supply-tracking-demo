<?php

namespace App\Models;

use App\Scopes\CurrentUserUUIDScope;
use Illuminate\Database\Eloquent\Model;

class HarvestExpertise extends Model
{

    const  TYPE_EXISTING_FARMER = 0;
    const  TYPE_UNKNOWN_FARMER = 1;

    protected $fillable = [
        'tx',
        'tx_id',
        'conclusion',
        'type',
        'eth_address_lab',
        'harvest_uid',
        'farmer_name',
        'farmer_address',
        'farmer_harvest',
        'eth_address',
        'uid',
        'uuid'
    ];

    protected $appends = [
        'tx',
        'batched_expertise',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserUUIDScope());
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class, 'eth_address_lab', 'eth_address');
    }

    public function harvest()
    {
        return $this->belongsTo(Harvest::class, 'harvest_uid', 'uid');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tx_id');
    }

    public function getTxAttribute()
    {
        if(is_null($this->tx_id))
            return null;

        $record = $this->transaction()->get()->first();
        return (!empty($record))
            ? $record->tx
            : null;
    }

    public function setTxAttribute($value)
    {
        $tx = Transaction::updateOrCreate(
            ['tx' => $value],
            ['status' => Transaction::TX_EXEC_PENDING]
        );
        $this->tx_id = $tx->id;
    }

    public function getBatchedExpertiseAttribute(){

        $result = [];
        switch ($this->type){

            case (static::TYPE_EXISTING_FARMER): $result = $this->_batchExistingFarmer(); break;
            case (static::TYPE_UNKNOWN_FARMER): $result = $this->_batchUnknownFarmer(); break;
            default: abort(500,'Something went wrong');

        }

        $result['type'] = $this->type;
        $result['conclusion'] = $this->conclusion;

        return $result;
    }

    protected function _batchExistingFarmer(){
        return [
            'harvest_id' => $this->harvest_id
        ];
    }

    protected function _batchUnknownFarmer(){
        return [
            'farmer_name'       => $this->farmer_name,
            'farmer_address'    => $this->farmer_address,
            'farmer_harvest'    => $this->farmer_harvest,
        ];
    }

    public static function blockChainFormat(HarvestExpertise $exp){
        return $exp->only([
            'uid',              # expertise UID
            'created_at',
            'conclusion',
            'farmer_name',
            'farmer_address',
            'farmer_harvest',
            'type',
            'eth_address',      # farmer eth address
            'eth_address_lab',  # lab eth address
            'harvest_uid',      # harvest UID of farmer with eth address
            'uuid'
        ]);
    }
}
