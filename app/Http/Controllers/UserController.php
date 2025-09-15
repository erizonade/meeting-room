<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::leftJoin('unit', 'users.unit_id', '=', 'unit.id')
            ->select('users.*', 'unit.name as unit_name');

        $users = $user->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('Meeting/User/Index', ['users' => $users->toArray()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Meeting/User/Form', [
            'unit' => Unit::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
            'unit_id' => $request->role == 'admin' ? 'required' : 'nullable'
        ]);


        User::create($validate);
        return back()->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Meeting/User/Form', [
            'users' => User::find($id),
            'unit' => Unit::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'unit_id' => $request->role == 'admin' ? 'required' : 'nullable'
        ]);

        if ($request->password) {
            $validate['password'] = bcrypt($request->password);
        }

        User::find($id)->update($validate);
        return back()->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return back()->with('success', 'User deleted successfully');
    }
}
