<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Students Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        h1 { text-align: center; font-size: 20px; color: #1e293b; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 3px solid #6366f1; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        th, td { border: 1px solid #d1d5db; padding: 6px 10px; text-align: left; }
        th { background: #1e293b; color: #fff; font-weight: 600; font-size: 10px; text-transform: uppercase; letter-spacing: .3px; }
        .student-header { background: #f0f4ff; font-weight: bold; font-size: 13px; color: #1e293b; }
        .student-header th { background: #e0e7ff; color: #1e293b; text-transform: none; font-size: 13px; letter-spacing: 0; }
        .label-cell { background: #f8fafc; font-weight: 600; width: 100px; color: #475569; }
        .footer { text-align: center; color: #94a3b8; font-size: 9px; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>Students Report</h1>

    @foreach ($students as $student)
        <table>
            <tr class="student-header">
                <th colspan="4">{{ $student->name }}</th>
            </tr>
            <tr>
                <td class="label-cell">Email</td>
                <td colspan="3">{{ $student->email }}</td>
            </tr>
            <tr>
                <td class="label-cell">Age</td>
                <td colspan="3">{{ $student->age }}</td>
            </tr>
            @if ($student->teachers->count())
                <tr>
                    <td class="label-cell" rowspan="{{ $student->teachers->count() }}">Teachers</td>
                    <td colspan="3">{{ $student->teachers->first()->name }} &lt;{{ $student->teachers->first()->email }}&gt;</td>
                </tr>
                @foreach ($student->teachers->skip(1) as $teacher)
                    <tr>
                        <td colspan="3">{{ $teacher->name }} &lt;{{ $teacher->email }}&gt;</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="label-cell">Teachers</td>
                    <td colspan="3" style="color: #94a3b8;">No teachers assigned</td>
                </tr>
            @endif
            @if ($student->subjects->count())
                <tr>
                    <td class="label-cell" rowspan="{{ $student->subjects->count() }}">Subjects</td>
                    <td colspan="3">{{ $student->subjects->first()->name }}</td>
                </tr>
                @foreach ($student->subjects->skip(1) as $subject)
                    <tr>
                        <td colspan="3">{{ $subject->name }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="label-cell">Subjects</td>
                    <td colspan="3" style="color: #94a3b8;">No subjects enrolled</td>
                </tr>
            @endif
        </table>
    @endforeach

    <div class="footer">
        Generated on {{ date('F j, Y') }} &middot; School Management System
    </div>
</body>
</html>
