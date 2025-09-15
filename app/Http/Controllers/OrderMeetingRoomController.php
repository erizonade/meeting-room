<?php

namespace App\Http\Controllers;

use App\Models\Consumption;
use App\Models\MeetingRoom;
use App\Models\OrderMeetingRoom;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderMeetingRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = OrderMeetingRoom::join('users', 'order_meeting.user_id', '=', 'users.id')
            ->join('unit', 'order_meeting.unit_id', '=', 'unit.id')
            ->join('meeting_room', 'order_meeting.meeting_room_id', '=', 'meeting_room.id')
            // ->join('consumption', function ($join) {
            //     $join->where('consumption.id', '=', 'order_meeting.consumption');
            // })
            ->selectRaw('order_meeting.*, users.name as user_name, unit.name as unit_name, meeting_room.name as room_name,
            CONCAT(order_meeting.start, " - ", order_meeting.end) as duration
            ');

        if (Auth::user()->role != 'super-admin') {
            $result->where('order_meeting.user_id', Auth::user()->id);
        }
        $order = $result->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10)->withQueryString();


        return Inertia::render('Meeting/OrderMeeting/Index', [
            'order' => $order,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Meeting/OrderMeeting/Form', [
            'unit' => Unit::all(),
            'room' => MeetingRoom::all(),
            'consumption' => Consumption::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'unit_id' => 'required',
            'room_id' => 'required',
            'start' => 'required',
            'end' => 'required',
            'participants' => 'required',
            'date' => 'required',
        ]);

        $start = $this->toMinutes($request->start);
        $end   = $this->toMinutes($request->end);

        if ($start > $end || $end < $start) {
            return back()->withErrors('Waktu selesai harus lebih besar dari waktu mulai');
        }

        $consumption = Consumption::get()->keyBy('id')->toArray();
        $listConsumption = [];
        $total = 0;
        if ($start < $this->toMinutes('11:00')) {
            $listConsumption[] = $consumption[1]['id'];
            $total += $consumption[1]['price'];
        }

        if ($start < $this->toMinutes('14:00') && $end >= $this->toMinutes('11:00')) {
            $listConsumption[] = $consumption[2]['id'];
            $total += $consumption[2]['price'];
        }

        if ($end >= $this->toMinutes('14:00')) {
            $listConsumption[] = $consumption[3]['id'];
            $total += $consumption[3]['price'];
        }
        $result = collect($listConsumption)->join(',');


        OrderMeetingRoom::create([
            'unit_id' => $request->unit_id,
            'meeting_room_id' => $request->room_id,
            'start' => $request->start,
            'end' => $request->end,
            'quantity_member' => $request->participants,
            'date' => $request->date,
            'consumption' => $result,
            'price' => ($total * $request->participants),
            'user_id' => Auth::user()->id,
        ]);

        return back()->with('success', 'Order meeting room created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderMeetingRoom $orderMeetingRoom) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return Inertia::render('Meeting/OrderMeeting/Form', [
            'unit' => Unit::all(),
            'room' => MeetingRoom::all(),
            'consumption' => Consumption::all(),
            'order' => OrderMeetingRoom::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'unit_id' => 'required',
            'room_id' => 'required',
            'start' => 'required',
            'end' => 'required',
            'participants' => 'required',
            'date' => 'required',
        ]);

        $start = $this->toMinutes($request->start);
        $end   = $this->toMinutes($request->end);

        if ($start > $end || $end < $start) {
            return back()->withErrors('Waktu selesai harus lebih besar dari waktu mulai');
        }

        $consumption = Consumption::get()->keyBy('id')->toArray();
        $listConsumption = [];
        $total = 0;
        if ($start < $this->toMinutes('11:00')) {
            $listConsumption[] = $consumption[1]['id'];
            $total += $consumption[1]['price'];
        }

        if ($start < $this->toMinutes('14:00') && $end >= $this->toMinutes('11:00')) {
            $listConsumption[] = $consumption[2]['id'];
            $total += $consumption[2]['price'];
        }

        if ($end >= $this->toMinutes('14:00')) {
            $listConsumption[] = $consumption[3]['id'];
            $total += $consumption[3]['price'];
        }
        $result = collect($listConsumption)->join(',');


        OrderMeetingRoom::findOrFail($id)->update([
            'unit_id' => $request->unit_id,
            'meeting_room_id' => $request->room_id,
            'start' => $request->start,
            'end' => $request->end,
            'quantity_member' => $request->participants,
            'date' => $request->date,
            'consumption' => $result,
            'price' => ($total * $request->participants),
            'user_id' => Auth::user()->id,
        ]);

        return back()->with('success', 'Order meeting room updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        OrderMeetingRoom::find($id)->delete();
        return back()->with('success', 'Order meeting room deleted successfully');
    }

    private function toMinutes($time)
    {
        $time = explode(':', $time);
        return $time[0] * 60 + $time[1];
    }
}
