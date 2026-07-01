<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::with('students')->get();
        return view('teachers.index', compact('teachers'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('teachers.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'required|string|max:20',
        ]);

        Teacher::create($validated);

        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher): RedirectResponse
    {
        return redirect()->route('teachers.index');
    }

    public function edit(Teacher $teacher): RedirectResponse
    {
        return redirect()->route('teachers.index');
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'required|string|max:20',
        ]);

        $teacher->update($validated);

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
