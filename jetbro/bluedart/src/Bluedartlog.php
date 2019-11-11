<?php

namespace jetbro\bluedart;

use Illuminate\Database\Eloquent\Model;

class Bluedartlog extends Model
{
    protected $table = 'bluedartlogs';

    protected $fillable = [
        'request','response','errormesssage'
    ];

}
