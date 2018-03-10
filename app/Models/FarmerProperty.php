<?php
/**
 * Created by LINKeRxUA <lnker.ua@gmail.com>
 * linkedIn:    https://www.linkedin.com/in/bogdan-kotelva/
 * Date: 10.11.17
 * Time: 12:19
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FarmerProperty extends Model
{
    protected $fillable = [
        'name',
        'value',
        'eth_address',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'farmer_id',
        'eth_address',
    ];

    public function farmer()
    {
        return $this->hasOne(Farmer::class,'eth_address','eth_address');
    }

    public static function blockChainFormat(FarmerProperty $prop)
    {
        return $prop->only(['name', 'value']);
    }
}