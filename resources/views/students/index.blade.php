@extends('layouts.app')

@section('title', 'Students')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center">
    <div>
        <h4 class="mb-1 fw-bold"><i class="bi bi-person-vcard-fill me-2 text-success"></i>Students</h4>
        <p class="text-muted mb-0 small">Manage all students in the system</p>
    </div>
    <button class="btn btn-success btn-rounded" data-bs-toggle="modal" data-bs-target="#createStudentModal">
        <i class="bi bi-plus-lg me-1"></i>Add Student
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-stat-students d-flex align-items-center">
            <i class="bi bi-person-vcard-fill stat-icon me-3"></i>
            <div>
                <div class="stat-number">{{ $students->count() }}</div>
                <div class="stat-label">Total Students</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-stat-teachers d-flex align-items-center">
            <i class="bi bi-people-fill stat-icon me-3"></i>
            <div>
                <div class="stat-number">{{ $students->sum(fn($s) => $s->teachers->count()) }}</div>
                <div class="stat-label">Total Teacher Assignments</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-stat-subjects d-flex align-items-center">
            <i class="bi bi-book-fill stat-icon me-3"></i>
            <div>
                <div class="stat-number">{{ $students->sum(fn($s) => $s->subjects->count()) }}</div>
                <div class="stat-label">Total Subject Enrollments</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Teachers</th>
                        <th>Subjects</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $student->name }}</span>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">{{ $student->age }}</span></td>
                            <td>{{ $student->email }}</td>
                            <td>
                                @foreach ($student->teachers->take(2) as $teacher)
                                    <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill me-1">{{ $teacher->name }}</span>
                                @endforeach
                                @if ($student->teachers->count() > 2)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">+{{ $student->teachers->count() - 2 }}</span>
                                @endif
                            </td>
                            <td>
                                @foreach ($student->subjects->take(2) as $subject)
                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill me-1">{{ $subject->name }}</span>
                                @endforeach
                                @if ($student->subjects->count() > 2)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">+{{ $student->subjects->count() - 2 }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info btn-action" title="View"
                                    data-bs-toggle="modal" data-bs-target="#showStudentModal"
                                    data-name="{{ $student->name }}"
                                    data-age="{{ $student->age }}"
                                    data-email="{{ $student->email }}"
                                    data-teachers='{{ $student->teachers->map(fn($t) => ["name" => $t->name, "email" => $t->email]) }}'
                                    data-subjects='{{ $student->subjects->map(fn($s) => ["name" => $s->name, "description" => $s->description]) }}'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning btn-action" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#editStudentModal"
                                    data-id="{{ $student->id }}"
                                    data-name="{{ $student->name }}"
                                    data-age="{{ $student->age }}"
                                    data-email="{{ $student->email }}"
                                    data-teachers='{{ $student->teachers->pluck("id") }}'
                                    data-subjects='{{ $student->subjects->pluck("id") }}'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-action" title="Delete"
                                    data-bs-toggle="modal" data-bs-target="#deleteStudentModal"
                                    data-id="{{ $student->id }}"
                                    data-name="{{ $student->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state text-center">
                                    <i class="bi bi-person-vcard"></i>
                                    <p class="mb-0">No students found.</p>
                                    <button class="btn btn-success btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createStudentModal">
                                        <i class="bi bi-plus-lg"></i> Add the first student
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Show Modal --}}
<div class="modal fade" id="showStudentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-person-vcard me-2"></i>Student Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-4 text-center border-end">
                        <div class="display-4 text-info mb-2"><i class="bi bi-person-circle"></i></div>
                        <h5 class="fw-bold" id="showStudentName"></h5>
                        <p class="text-muted small">Student</p>
                        <hr>
                        <div class="text-start small px-3">
                            <p class="mb-2"><i class="bi bi-calendar me-2 text-info"></i>Age: <strong id="showStudentAge"></strong></p>
                            <p class="mb-0"><i class="bi bi-envelope me-2 text-info"></i><span id="showStudentEmail"></span></p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="fw-semibold mb-2"><i class="bi bi-people-fill me-2 text-primary"></i>Teachers</h6>
                        <div id="showStudentTeachers" class="mb-4"><p class="text-muted small">Loading...</p></div>
                        <h6 class="fw-semibold mb-2"><i class="bi bi-book-fill me-2 text-warning"></i>Subjects</h6>
                        <div id="showStudentSubjects"><p class="text-muted small">Loading...</p></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div class="modal fade" id="createStudentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="create_name" class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" id="create_name" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="create_age" class="form-label fw-semibold">Age</label>
                            <input type="number" name="age" id="create_age" class="form-control" placeholder="Age" required min="1" max="150">
                        </div>
                        <div class="col-md-4">
                            <label for="create_email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="create_email" class="form-control" placeholder="Email address" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Teachers</label>
                            <select name="teachers[]" class="form-select" multiple size="5">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subjects</label>
                            <select name="subjects[]" class="form-select" multiple size="5">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="editStudentForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="edit_name" class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_age" class="form-label fw-semibold">Age</label>
                            <input type="number" name="age" id="edit_age" class="form-control" required min="1" max="150">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Teachers</label>
                            <select name="teachers[]" id="edit_teachers" class="form-select" multiple size="5">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subjects</label>
                            <select name="subjects[]" id="edit_subjects" class="form-select" multiple size="5">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg me-1"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteStudentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="deleteStudentForm">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Delete Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-person-x display-4 text-danger mb-3 d-block"></i>
                    <p class="mb-1 fw-semibold">Are you sure?</p>
                    <p class="text-muted small mb-0" id="deleteStudentName"></p>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Show modal
    const showModal = document.getElementById('showStudentModal');
    showModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('showStudentName').textContent = btn.dataset.name;
        document.getElementById('showStudentAge').textContent = btn.dataset.age;
        document.getElementById('showStudentEmail').textContent = btn.dataset.email;

        const teachers = JSON.parse(btn.dataset.teachers || '[]');
        const tContainer = document.getElementById('showStudentTeachers');
        if (teachers.length === 0) {
            tContainer.innerHTML = '<p class="text-muted small mb-0">No teachers assigned.</p>';
        } else {
            let html = '<div class="list-group list-group-flush">';
            teachers.forEach(function(t, i) {
                html += '<div class="list-group-item d-flex align-items-center px-0 border-start-0 border-end-0"><span class="badge bg-primary me-2">' + (i+1) + '</span><div><strong>' + t.name + '</strong><br><small class="text-muted">' + t.email + '</small></div></div>';
            });
            html += '</div>';
            tContainer.innerHTML = html;
        }

        const subjects = JSON.parse(btn.dataset.subjects || '[]');
        const sContainer = document.getElementById('showStudentSubjects');
        if (subjects.length === 0) {
            sContainer.innerHTML = '<p class="text-muted small mb-0">No subjects enrolled.</p>';
        } else {
            let html = '<div class="list-group list-group-flush">';
            subjects.forEach(function(s, i) {
                html += '<div class="list-group-item d-flex align-items-center px-0 border-start-0 border-end-0"><span class="badge bg-warning me-2">' + (i+1) + '</span><div><strong>' + s.name + '</strong><br><small class="text-muted">' + (s.description || 'N/A') + '</small></div></div>';
            });
            html += '</div>';
            sContainer.innerHTML = html;
        }
    });

    // Edit modal
    const editModal = document.getElementById('editStudentModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('edit_name').value = btn.dataset.name;
        document.getElementById('edit_age').value = btn.dataset.age;
        document.getElementById('edit_email').value = btn.dataset.email;
        document.getElementById('editStudentForm').action = '/students/' + btn.dataset.id;

        const teacherIds = JSON.parse(btn.dataset.teachers || '[]');
        Array.from(document.getElementById('edit_teachers').options).forEach(opt => opt.selected = teacherIds.includes(parseInt(opt.value)));

        const subjectIds = JSON.parse(btn.dataset.subjects || '[]');
        Array.from(document.getElementById('edit_subjects').options).forEach(opt => opt.selected = subjectIds.includes(parseInt(opt.value)));
    });

    // Delete modal
    const deleteModal = document.getElementById('deleteStudentModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('deleteStudentName').textContent = btn.dataset.name;
        document.getElementById('deleteStudentForm').action = '/students/' + btn.dataset.id;
    });
});
</script>
@endpush
