<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $units = Unit::query();

        // optional search
        if ($request->search) {
            $units->where('name', 'like', "%{$request->search}%");
        }

        // simple pagination (you can also do server-side sorting if needed)
        $unit = $units->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();
            
        return Inertia::render('Meeting/Unit/Index', [
            'units'   => $unit,
            'filters' => $request->only('search'),
        ]);
        // return Inertia::render('Meeting/Unit/Index', ['units' => $units]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Meeting/Unit/Form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Unit::create($request->all());
        return back()->with('success', 'Unit created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return Inertia::render('Meeting/Unit/Form', ['unit' => $unit]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $unit->update($request->all());
        return back()->with('success', 'Unit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit deleted successfully');
    }
}
