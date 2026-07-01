<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OptionController extends Controller
{
    /**
     * Display all options grouped by category
     */
    public function index(): View
    {
        $options = Option::ordered()->get()->groupBy('category');
        $categories = Option::select('category')->distinct()->orderBy('category')->pluck('category');
        return view('admin.options', compact('options', 'categories'));
    }

    /**
     * Store a new option
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category'   => 'required|string|max:50',
            'key'        => 'required|string|max:50',
            'label'      => 'required|string|max:100',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate
        $exists = Option::where('category', $data['category'])
            ->where('key', $data['key'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Option dengan category dan key yang sama sudah ada.');
        }

        Option::create($data);
        return back()->with('success', 'Option berhasil ditambahkan.');
    }

    /**
     * Update an existing option
     */
    public function update(Request $request, Option $option): RedirectResponse
    {
        $data = $request->validate([
            'label'      => 'required|string|max:100',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $option->update($data);
        return back()->with('success', 'Option berhasil diperbarui.');
    }

    /**
     * Delete an option
     */
    public function destroy(Option $option): RedirectResponse
    {
        $option->delete();
        return back()->with('success', 'Option berhasil dihapus.');
    }
}
