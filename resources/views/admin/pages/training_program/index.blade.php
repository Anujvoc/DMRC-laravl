@extends('admin.partials.app')

@section('title', 'Training Programs')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Training Programs</h1>
            <span>Manage training programs and courses</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.training_program.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add Training Program
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body">
                    <!-- Table View -->
                    <div id="tableView">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="programsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($programs as $program)
                                        <tr>
                                            <td>{{ $program->id }}</td>
                                            <td>{{ $program->title }}</td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <a href="{{ route('admin.training_program.edit', $program->id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <button class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="deleteProgram({{ $program->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                No training programs found
                                            </td>
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
</div>

<script>
function deleteProgram(programId) {
    if(confirm('Are you sure you want to delete this training program?')) {
        // Create form for DELETE method
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/training-program/' + programId;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add DELETE method override
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
