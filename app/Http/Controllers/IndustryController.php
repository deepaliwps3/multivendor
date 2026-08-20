<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndustryRequest;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndustryController extends Controller
{
    /**
     * Display a listing of the industries.
     */
    public function index(Request $request)
    {
        $industries = Industry::latest()->get();

        if ($request->wantsJson()) {
            return IndustryResource::collection($industries);
        }

        return view('industries.index', compact('industries'));
    }

    /**
     * Store a newly created industry in storage.
     */
    public function store(IndustryRequest $request)
    {
        $industry = Industry::create($request->validated());

        if ($request->wantsJson()) {
            return new IndustryResource($industry);
        }

        return redirect()->route('industries.index')->with('success', 'Industry created successfully.');
    }

    /**
     * Display the specified industry.
     */
    public function show(Industry $industry)
    {
        return new IndustryResource($industry);
    }

    /**
     * Update the specified industry in storage.
     */
    public function update(IndustryRequest $request, Industry $industry)
    {
        $industry->update($request->validated());

        if ($request->wantsJson()) {
            return new IndustryResource($industry);
        }

        return redirect()->route('industries.index')->with('success', 'Industry updated successfully.');
    }

    /**
     * Remove the specified industry from storage.
     */
    public function destroy(Request $request, Industry $industry)
    {
        $industry->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Industry deleted successfully.']);
        }

        return redirect()->route('industries.index')->with('success', 'Industry deleted successfully.');
    }
}
