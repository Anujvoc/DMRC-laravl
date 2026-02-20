@extends('admin.partials.app')

@section('title', 'Instructors')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Instructors</h1>
            <span>Manage instructors</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.master.instructors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add Instructor
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="instructorsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Employee ID</th>
                                    <th>Subject</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($instructors as $instructor)
                                    <tr>
                                        <td>{{ $instructor->id }}</td>
                                        <td>{{ $instructor->name }}</td>
                                        <td>{{ $instructor->designation }}</td>
                                        <td>{{ $instructor->{'employee id'} }}</td>
                                        <td>{{ $instructor->subject_titles ?? '' }}</td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="{{ route('admin.master.instructors.edit', $instructor->id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="deleteInstructor({{ $instructor->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No instructors found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteInstructor(id) {
    if(confirm('Are you sure you want to delete this instructor?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/master/instructors/' + id;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
