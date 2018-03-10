<?php

namespace App\Models;

use App\Components\Ethereum;
use Illuminate\Database\Eloquent\Model;

class EtherAccounts extends Model
{

    protected $fillable = [
        'address',
        'secret_phrase',
    ];

    protected $hidden = ['password'];

    public function __construct(array $attributes = [])
    {
        if(empty($attributes)){
            $eth = app('eth');
            $attributes = $eth->create_account();
        }
        parent::__construct($attributes);
    }
}
