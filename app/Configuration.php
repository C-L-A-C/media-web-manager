<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $primaryKey = 'name';
    protected $keyType = 'string';
    protected $fillable = ['name', 'value'];
    protected $dispatchesEvents = [
        'saving' => Events\ConfigurationUpdated::class
    ];
    public $timestamps = false;
}
