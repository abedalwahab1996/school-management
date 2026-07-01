@extends('layouts.app')

@section('title', 'Teachers')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center">
    <div>
        <h4 class="mb-1 fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i>Teachers</h4>
        <p class="text-muted mb-0 small">Manage all teachers in the system</p>
    </div>
    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#createTeacherModal">
        <i class="bi bi-plus-lg me-1"></i>Add Teacher
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-stat-teachers d-flex align-items-center">
            <i class="bi bi-people-fill stat-icon me-3"></i>
            <div>
                <div class="stat-number">{{ $teachers->count() }}</div>
                <div class="stat-label">Total Teachers</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-stat-students d-flex align-items-center">
            <i class="bi bi-person-vcard-fill stat-icon me-3"></i>
            <div>
                <div class="stat-number">{{ $teachers->sum(fn($t) => $t->students->count()) }}</div>
                <div class="stat-label">Total Students Assigned</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-stat-subjects d-flex align-items-center">
            <i class="bi bi-mortarboard-fill stat-icon me-3"></i>
            <div>
                <div class="stat-number">{{ round($teachers->avg(fn($t) => $t->students->count()), 1) }}</div>
                <div class="stat-label">Avg Students / Teacher</div>
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-center">Students</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>
                                <span class="text-decoration-none fw-semibold text-dark">
                                    {{ $teacher->name }}
                                </span>
                            </td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->phone }}</td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-1">
                                    {{ $teacher->students->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info btn-action" title="View"
                                    data-bs-toggle="modal" data-bs-target="#showTeacherModal"
                                    data-name="{{ $teacher->name }}"
                                    data-email="{{ $teacher->email }}"
                                    data-phone="{{ $teacher->phone }}"
                                    data-students='{{ $teacher->students->map(fn($s) => ['name' => $s->name, 'email' => $s->email]) }}'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning btn-action" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#editTeacherModal"
                                    data-id="{{ $teacher->id }}"
                                    data-name="{{ $teacher->name }}"
                                    data-email="{{ $teacher->email }}"
                                    data-phone="{{ $teacher->phone }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-action" title="Delete"
                                    data-bs-toggle="modal" data-bs-target="#deleteTeacherModal"
                                    data-id="{{ $teacher->id }}"
                                    data-name="{{ $teacher->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state text-center">
                                    <i class="bi bi-people"></i>
                                    <p class="mb-0">No teachers found.</p>
                                    <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createTeacherModal">
                                        <i class="bi bi-plus-lg"></i> Add the first teacher
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
<div class="modal fade" id="showTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-person-badge me-2"></i>Teacher Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-5 text-center border-end">
                        <div class="display-4 text-info mb-2"><i class="bi bi-person-circle"></i></div>
                        <h5 class="fw-bold" id="showTeacherName"></h5>
                        <p class="text-muted small">Teacher</p>
                        <hr>
                        <div class="text-start small px-3">
                            <p class="mb-2"><i class="bi bi-envelope me-2 text-info"></i><span id="showTeacherEmail"></span></p>
                            <p class="mb-0"><i class="bi bi-telephone me-2 text-info"></i><span id="showTeacherPhone"></span></p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-people-fill me-2 text-info"></i>Assigned Students</h6>
                        <div id="showTeacherStudents">
                            <p class="text-muted small mb-0">Loading...</p>
                        </div>
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
<div class="modal fade" id="createTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('teachers.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Teacher</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="create_email" class="form-control" placeholder="Enter email address" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_phone" class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" id="create_phone" class="form-control" placeholder="Enter phone number" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="editTeacherForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" required>
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
<div class="modal fade" id="deleteTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="deleteTeacherForm">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Delete Teacher</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-person-x display-4 text-danger mb-3 d-block"></i>
                    <p class="mb-1 fw-semibold">Are you sure?</p>
                    <p class="text-muted small mb-0" id="deleteTeacherName"></p>
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
    const showModal = document.getElementById('showTeacherModal');
    showModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('showTeacherName').textContent = btn.dataset.name;
        document.getElementById('showTeacherEmail').textContent = btn.dataset.email;
        document.getElementById('showTeacherPhone').textContent = btn.dataset.phone;

        const students = JSON.parse(btn.dataset.students || '[]');
        const container = document.getElementById('showTeacherStudents');
        if (students.length === 0) {
            container.innerHTML = '<p class="text-muted small mb-0">No students assigned.</p>';
        } else {
            let html = '<div class="list-group list-group-flush">';
            students.forEach(function(s, i) {
                html += '<div class="list-group-item d-flex align-items-center px-0 border-start-0 border-end-0"><span class="badge bg-secondary me-2">' + (i+1) + '</span><div><strong>' + s.name + '</strong><br><small class="text-muted">' + s.email + '</small></div></div>';
            });
            html += '</div>';
            container.innerHTML = html;
        }
    });

    // Edit modal
    const editModal = document.getElementById('editTeacherModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('edit_name').value = btn.dataset.name;
        document.getElementById('edit_email').value = btn.dataset.email;
        document.getElementById('edit_phone').value = btn.dataset.phone;
        document.getElementById('editTeacherForm').action = '/teachers/' + btn.dataset.id;
    });

    // Delete modal
    const deleteModal = document.getElementById('deleteTeacherModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('deleteTeacherName').textContent = btn.dataset.name;
        document.getElementById('deleteTeacherForm').action = '/teachers/' + btn.dataset.id;
    });
});
</script>
@endpush
