<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerificationToken extends Model
{
    protected $fillable = ['token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

}
