<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mpdf\Mpdf;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::with('teachers', 'subjects')->get();
        $teachers = Teacher::all();
        $subjects = Subject::all();
        return view('students.index', compact('students', 'teachers', 'subjects'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('students.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'age'        => 'required|numeric|min:1|max:150',
            'email'      => 'required|email|unique:students,email',
            'teachers'   => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
            'subjects'   => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $student = Student::create([
            'name'  => $validated['name'],
            'age'   => $validated['age'],
            'email' => $validated['email'],
        ]);

        $student->teachers()->sync($request->teachers ?? []);
        $student->subjects()->sync($request->subjects ?? []);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show(Student $student): RedirectResponse
    {
        return redirect()->route('students.index');
    }

    public function edit(Student $student): RedirectResponse
    {
        return redirect()->route('students.index');
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'age'        => 'required|numeric|min:1|max:150',
            'email'      => 'required|email|unique:students,email,' . $student->id,
            'teachers'   => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
            'subjects'   => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $student->update([
            'name'  => $validated['name'],
            'age'   => $validated['age'],
            'email' => $validated['email'],
        ]);

        $student->teachers()->sync($request->teachers ?? []);
        $student->subjects()->sync($request->subjects ?? []);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->teachers()->detach();
        $student->subjects()->detach();
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function pdf()
    {
        $students = Student::with('teachers', 'subjects')->get();

        $html = view('pdf.students', compact('students'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('students-report.pdf', 'I');
    }
}
