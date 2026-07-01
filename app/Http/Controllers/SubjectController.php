<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::with('students')->get();
        return view('subjects.index', compact('subjects'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('subjects.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Subject::create($validated);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject): RedirectResponse
    {
        return redirect()->route('subjects.index');
    }

    public function edit(Subject $subject): RedirectResponse
    {
        return redirect()->route('subjects.index');
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->students()->detach();
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
