@extends('admin.partials.app')

@section('title', 'Subtopics')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="app-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Subtopics Management</h2>

        <a href="{{ route('admin.master.subtopics.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add New Subtopic
        </a>
    </div>

    <!-- Subtopics Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="subtopicsTable">
                        <thead>
                            <tr>
                                <th>Subtopic</th>
                                <th>Topic</th>
                                <th>Description</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subtopics as $subtopic)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-semibold">{{ $subtopic->subtopic ?? 'Unknown Subtopic' }}</div>
                                                <small class="text-muted">{{ $subtopic->topic_name ?? 'Unknown Topic' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $subtopic->topic_name ?? 'N/A' }}</td>
                                    <td>{{ $subtopic->description ?? 'N/A' }}</td>
                                    <td>{{ $subtopic->sort_order ?? 'N/A' }}</td>
                                    <td>
                                        @if(($subtopic->status ?? 1) == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.master.subtopics.edit', $subtopic->subtopic_id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="deleteSubtopic({{ $subtopic->subtopic_id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fi fi-sr-book fa-3x mb-3"></i>
                                            <h5>No subtopics found</h5>
                                            <p>Start by adding your first subtopic.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grid View -->
            <div id="gridView" style="display: none;">
                <div class="row g-3" id="subtopicsGrid">
                    <!-- Subtopics will be loaded here via JavaScript -->
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
               
                <nav>
                    <ul class="pagination mb-0" id="pagination">
                        <!-- Pagination will be generated here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Add Subtopic Modal -->

@endsection

<script>
// Delete subtopic function
function deleteSubtopic(subtopicId) {
    if (confirm('Are you sure you want to delete this subtopic? This action cannot be undone.')) {
        // Create form for DELETE method
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.master.subtopics.destroy", ":id") }}'.replace(':id', subtopicId);
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add DELETE method override
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
