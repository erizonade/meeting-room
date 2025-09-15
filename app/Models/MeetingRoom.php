<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    protected $table = 'meeting_room';

    protected $fillable = [
        'name',
        'capacity',
    ];
}
