<?php

namespace App\Models;

use App\Scopes\CurrentUserUUIDScope;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Laboratory extends Model
{

    const STORAGE_DIR = '/lab-docs';

    public static $applyBootEvents = true;

    protected $fillable = [
        'name',
        'address',
        'tx_lab',
        'tx_files',
        'tx_props',
        'tx_lab_id',
        'tx_files_id',
        'tx_props_id',
        'gm_lat', 'gm_lon', 'gm_place_id',
        'eth_address',
        'uuid',
    ];

    protected $appends = [
        'files_batched',
        'props_batched',
        'tx_lab',
        'tx_files',
        'tx_props',
    ];

    public function getFilesBatchedAttribute()
    {
        return (!empty($this->json_files))
            ? json_decode( $this->json_files, true )
            : [];
    }

    public function getPropsBatchedAttribute()
    {
        return (!empty($this->json_props))
            ? json_decode( $this->json_props, true )
            : [];
    }

    public function getTxLabAttribute()
    {
        if(is_null($this->tx_lab_id))
            return null;

        $record = $this->labTransaction()->get()->first();
        return (!empty($record))
            ? $record->tx
            : null;

    }

    public function setTxLabAttribute($value)
    {
        $tx = Transaction::updateOrCreate(
            ['tx' => $value],
            ['status' => Transaction::TX_EXEC_PENDING]
        );
        $this->tx_lab_id = $tx->id;
    }

    public function getTxPropsAttribute()
    {
        if(is_null($this->tx_props_id))
            return null;
        $record = $this->propsTransaction()->get()->first();
        return (!empty($record))
            ? $record->tx
            : null;
    }

    public function setTxPropsAttribute($value)
    {
        $tx = new Transaction();
        $tx->tx = $value;
        $tx->save();
        $this->tx_props_id = $tx->id;
    }

    public function getTxFilesAttribute()
    {
        if(is_null($this->tx_files_id))
            return null;
        $record = $this->filesTransaction()->get()->first();
        return (!empty($record))
            ? $record->tx
            : null;
    }

    public function setTxFilesAttribute($value)
    {
        $tx = new Transaction();
        $tx->tx = $value;
        $tx->save();
        $this->tx_files_id = $tx->id;
    }

    public function setEthAddressAttribute($value)
    {
        $this->attributes['eth_address'] = ($value instanceof EtherAccounts)
            ? $value->address
            : $this->attributes['eth_address'] = $value;
    }

    /**
     * @var array [UploadedFile, string]
     */
    protected $_files = [];
    protected $_props = [];

    public function newUpload(UploadedFile $file, $fileName)
    {
        $this->_files[] = [$file, $fileName];
    }

    public function newProperty($key, $value)
    {
        $this->_props[] = [ (string) $key, (string) $value ];
    }

    /**
     * @return HarvestExpertise
     */
    public function newExpertise(){
        $obj = new HarvestExpertise();
        $obj->uid = uniqueID_withMixing(32);
        $obj->eth_address_lab = $this->eth_address;
        return $obj;
    }

    # relations -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -

    public function files()
    {
        return $this->hasMany(LaboratoryFile::class,'eth_address','eth_address');
    }

    public function properties()
    {
        return $this->hasMany(LaboratoryProperty::class,'eth_address','eth_address');
    }

    public function expertise()
    {
        return $this->hasMany(HarvestExpertise::class,'eth_address_lab','eth_address');
    }

    public function labTransaction()
    {
        return $this->belongsTo(Transaction::class, 'tx_lab_id');
    }

    public function propsTransaction()
    {
        return $this->belongsTo(Transaction::class, 'tx_props_id');
    }

    public function filesTransaction()
    {
        return $this->belongsTo(Transaction::class, 'tx_files_id');
    }

    public static function storageLocation($labID, $makeIfNotExists = true)
    {
        $locationRoot = storage_path( $result = 'lab-docs/' . $labID . '/');

        if($makeIfNotExists and !is_dir($locationRoot)){
            mkdir($locationRoot,0777,true);
        }

        return $result;
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CurrentUserUUIDScope());

        if(static::$applyBootEvents){
            static::created([static::class, '_uploadFiles']);
            static::created([static::class, '_setProps']);
        }
    }

    protected function _uploadFiles(Laboratory $lab)
    {
        $json = [];

        foreach ($lab->_files as $file){
            /**
             * @var $file UploadedFile
             * @var $fileName string
             */
            list($file, $fileName) = $file;

            if($file->isValid()){
                $json[] = LaboratoryFile::saveUploadedFile($file, $lab, $fileName)->toArray();
            }

        }

        \DB::table($lab->getTable())
            ->where('id',$lab->id)
            ->update([
                'json_files' => json_encode($json)
            ]);
    }


    protected function _setProps($lab)
    {
        $json = [];
        foreach ($lab->_props as $propSet){
            $prop = new LaboratoryProperty();
            $prop->name = $propSet[0];
            $prop->value = $propSet[1];
            $prop->eth_address = $lab->eth_address;
            $prop->save();
            $json[] = $prop->toArray();
        }

        \DB::table($lab->getTable())
            ->where('id',$lab->id)
            ->update([
                'json_props' => json_encode($json)
        ]);
    }

    public static function blockChainFormat(Laboratory $lab){
        return $lab->only([
            'name', 'address',
            'gm_lat','gm_lon','gm_palce_id',
            'created_at',
            'eth_address',
            'files_batched', 'props_batched', 'uuid'
        ]);
    }
}
