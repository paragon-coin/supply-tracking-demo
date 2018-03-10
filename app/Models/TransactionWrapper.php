<?php

namespace App\Models;

use App\Components\Ethereum;

class TransactionWrapper
{
    protected $tx;

    /**
     * @var Ethereum
     */
    protected $eth;

    protected function __construct()
    {
        $this->eth = app('eth');
    }

    public static function find($tx)
    {
        $object = new static();
        $object->tx = (string) $tx;
        return $object;
    }

    protected function _status()
    {
        return $this->eth->statusOf($this->tx);
    }


    public function isPending()
    {
        return ($this->_status() === null);
    }

    public function isAccepted()
    {
        return $this->_status();
    }

    public function isFailed()
    {
        return !$this->_status();
    }

    public function hash()
    {
        return $this->tx;
    }





}
