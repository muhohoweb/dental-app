<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('services/index',[
            'services'=>Service::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'turnaround_days' => ['required', 'integer', 'min:1'],
        ]);

        Service::create($validated);

        return redirect()->back()->with('success', 'Service created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'turnaround_days' => ['required', 'integer', 'min:1'],
        ]);

        $service->update($validated);

        return redirect()->back()->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return redirect()->back()->with('success', 'Service deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to delete service. It may be associated with existing orders.');
        }
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
        //
    }



}
