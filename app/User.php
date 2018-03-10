<?php

namespace App;

use App\Models\Farmer;
use App\Models\Harvest;
use App\Models\HarvestExpertise;
use App\Models\Laboratory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->uuid = user_unique_id();
        });
    }

    public function verificationToken()
    {
        return $this->hasOne(VerificationToken::class);
    }

    public function hasVerified()
    {
        return $this->verified;
    }

    public function farmers()
    {
        return $this->hasMany(Farmer::class, 'uuid', 'uuid');
    }

    public function laboratories()
    {
        $this->hasMany(Laboratory::class, 'uuid', 'uuid');
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class, 'uuid', 'uuid');
    }

    public function expertises()
    {
        return $this->hasMany(HarvestExpertise::class, 'uuid', 'uuid');
    }
}
