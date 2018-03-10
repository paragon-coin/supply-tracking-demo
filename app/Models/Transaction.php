<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    /**
     * executed tx, but in pending process
     */
    const TX_EXEC_PENDING = 0;
    /**
     * executed and confirmed (exists in blockchain)
     */
    const TX_EXEC_SUCCESS = 1;
    /**
     * executed, but something went wrong! (not exists in blockchain )
     */
    const TX_EXEC_FAILED = 2;

    protected $fillable = [
        'tx',
        'status',
    ];

    public function setStatusPending()
    {
        $this->status = self::TX_EXEC_PENDING;
    }
    public function setStatusConfirmed()
    {
        $this->status = self::TX_EXEC_SUCCESS;
    }

    public function setStatusFailed()
    {
        $this->status = self::TX_EXEC_FAILED;
    }

    public function getPendingAttribute()
    {
        return $this->status === self::TX_EXEC_PENDING;
    }

    public function getConfirmedAttribute()
    {
        return $this->status === self::TX_EXEC_SUCCESS;
    }

    public function getFailedAttribute()
    {
        return $this->status === self::TX_EXEC_FAILED;
    }
}
