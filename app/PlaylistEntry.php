<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaylistEntry extends Model
{
    protected $fillable = ['type', 'path'];
}
