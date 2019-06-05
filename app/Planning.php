<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    protected $fillable = [
        'operator', 'activity', 'state',
    ];
}
