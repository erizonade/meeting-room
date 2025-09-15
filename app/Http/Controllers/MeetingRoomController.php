<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MeetingRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $room = MeetingRoom::query();

        $rooms = $room->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Meeting/Room/Index', ['room' => $rooms]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Meeting/Room/Form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'capacity' => 'required|numeric',
        ]);

        MeetingRoom::create($request->all());
        // return to_route('meeting-room.index');
        return back()->with('success', 'Room created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(MeetingRoom $meetingRoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeetingRoom $meetingRoom)
    {
        return Inertia::render('Meeting/Room/Form', ['room' => $meetingRoom]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MeetingRoom $meetingRoom)
    {
        $request->validate([
            'name' => 'required',
            'capacity' => 'required|numeric',
        ]);

        $meetingRoom->update($request->all());
        return to_route('meeting-room.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeetingRoom $meetingRoom)
    {
        $meetingRoom->delete();
        // return to_route('meeting-room.index');
        return back()->with('success', 'Room deleted successfully');
    }
}
