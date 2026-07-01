@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center">
    <div>
        <h4 class="mb-1 fw-bold"><i class="bi bi-book-fill me-2 text-warning"></i>Subjects</h4>
        <p class="text-muted mb-0 small">Manage all subjects in the system</p>
    </div>
    <button class="btn btn-warning btn-rounded" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
        <i class="bi bi-plus-lg me-1"></i>Add Subject
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card bg-stat-subjects d-flex align-items-center">
            <i class="bi bi-book-fill stat-icon me-3"></i>
            <div>
                <div class="stat-number">{{ $subjects->count() }}</div>
                <div class="stat-label">Total Subjects</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card bg-stat-students d-flex align-items-center">
            <i class="bi bi-person-vcard-fill stat-icon me-3"></i>
            <div>
                <div class="stat-number">{{ $subjects->sum(fn($s) => $s->students->count()) }}</div>
                <div class="stat-label">Total Student Enrollments</div>
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
                        <th>Description</th>
                        <th class="text-center">Students</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjects as $subject)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $subject->name }}</span>
                            </td>
                            <td class="text-muted small">{{ Str::limit($subject->description ?? 'N/A', 60) }}</td>
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-1">
                                    {{ $subject->students->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info btn-action" title="View"
                                    data-bs-toggle="modal" data-bs-target="#showSubjectModal"
                                    data-name="{{ $subject->name }}"
                                    data-description="{{ $subject->description }}"
                                    data-students='{{ $subject->students->map(fn($s) => ["name" => $s->name, "email" => $s->email]) }}'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning btn-action" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#editSubjectModal"
                                    data-id="{{ $subject->id }}"
                                    data-name="{{ $subject->name }}"
                                    data-description="{{ $subject->description }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-action" title="Delete"
                                    data-bs-toggle="modal" data-bs-target="#deleteSubjectModal"
                                    data-id="{{ $subject->id }}"
                                    data-name="{{ $subject->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state text-center">
                                    <i class="bi bi-book"></i>
                                    <p class="mb-0">No subjects found.</p>
                                    <button class="btn btn-warning btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                                        <i class="bi bi-plus-lg"></i> Add the first subject
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
<div class="modal fade" id="showSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-book me-2"></i>Subject Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-4 text-center border-end">
                        <div class="display-4 text-warning mb-2"><i class="bi bi-book"></i></div>
                        <h5 class="fw-bold" id="showSubjectName"></h5>
                        <hr>
                        <div class="text-start small px-3">
                            <p class="mb-0"><i class="bi bi-card-text me-2 text-warning"></i><span id="showSubjectDescription"></span></p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="fw-semibold mb-2"><i class="bi bi-people-fill me-2 text-success"></i>Enrolled Students</h6>
                        <div id="showSubjectStudents"><p class="text-muted small">Loading...</p></div>
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
<div class="modal fade" id="createSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="Enter subject name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_description" class="form-label fw-semibold">Description <span class="text-muted">(optional)</span></label>
                        <textarea name="description" id="create_description" class="form-control" rows="3" placeholder="Enter a brief description"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="editSubjectForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label fw-semibold">Description <span class="text-muted">(optional)</span></label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3" placeholder="Enter a brief description"></textarea>
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
<div class="modal fade" id="deleteSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="deleteSubjectForm">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Delete Subject</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-book-x display-4 text-danger mb-3 d-block"></i>
                    <p class="mb-1 fw-semibold">Are you sure?</p>
                    <p class="text-muted small mb-0" id="deleteSubjectName"></p>
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
    const showModal = document.getElementById('showSubjectModal');
    showModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('showSubjectName').textContent = btn.dataset.name;
        document.getElementById('showSubjectDescription').textContent = btn.dataset.description || 'No description provided.';

        const students = JSON.parse(btn.dataset.students || '[]');
        const container = document.getElementById('showSubjectStudents');
        if (students.length === 0) {
            container.innerHTML = '<p class="text-muted small mb-0">No students enrolled.</p>';
        } else {
            let html = '<div class="list-group list-group-flush">';
            students.forEach(function(s, i) {
                html += '<div class="list-group-item d-flex align-items-center px-0 border-start-0 border-end-0"><span class="badge bg-success me-2">' + (i+1) + '</span><div><strong>' + s.name + '</strong><br><small class="text-muted">' + s.email + '</small></div></div>';
            });
            html += '</div>';
            container.innerHTML = html;
        }
    });

    // Edit modal
    const editModal = document.getElementById('editSubjectModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('edit_name').value = btn.dataset.name;
        document.getElementById('edit_description').value = btn.dataset.description || '';
        document.getElementById('editSubjectForm').action = '/subjects/' + btn.dataset.id;
    });

    // Delete modal
    const deleteModal = document.getElementById('deleteSubjectModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('deleteSubjectName').textContent = btn.dataset.name;
        document.getElementById('deleteSubjectForm').action = '/subjects/' + btn.dataset.id;
    });
});
</script>
@endpush
