<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMeetingRoom extends Model
{
    protected $table = 'order_meeting';

    protected $fillable = [
        'unit_id',
        'meeting_room_id',
        'start',
        'end',
        'date',
        'quantity_member',
        'consumption',
        'price',
        'user_id'
    ];
}
