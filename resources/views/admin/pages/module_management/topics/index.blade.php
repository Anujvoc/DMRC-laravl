@extends('admin.partials.app')

@section('title', 'Topics')

@section('content')
<style>
    th{
          font-size: 14px;
    font-weight: 800;
    color: gray;
    }

      
</style>

<div class="app-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Topics Management</h2>

    <a href="{{ route('admin.module.topics.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add New Topic
    </a>
</div>

    
 

    <!-- Statistics Cards -->


    <!-- Topics Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="topicsTable">
                        <thead>
                            <tr>
                               
                                <th>Topic</th>
                                <th>Subject</th>
                                <th>Subtopic</th>
                                <th>Description</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topics as $topic)
                                <tr>
                                 
                                    <td>
                                        <div class="d-flex align-items-center">
                                          
                                            <div>
                                                <div class="fw-semibold">{{ $topic->topic ?? 'Unknown Topic' }}</div>
                                                <small class="text-muted">{{ $topic->subtopic ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $topic->subject_title ?? 'N/A' }}</td>
                                    <td>{{ $topic->subtopic ?? 'N/A' }}</td>
                                    <td>{{ $topic->description ?? 'N/A' }}</td>
                                    <td>{{ $topic->sort_order ?? 'N/A' }}</td>
                                    <td>
                                        @if(($topic->status ?? 1) == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.module.topics.edit', $topic->topic_id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="deleteTopic({{ $topic->topic_id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty7
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fi fi-sr-book fa-3x mb-3"></i>
                                            <h5>No topics found</h5>
                                            <p>Start by adding your first topic.</p>
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
                <div class="row g-3" id="topicsGrid">
                    <!-- Topics will be loaded here via JavaScript -->
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

<!-- Add Topic Modal -->

@endsection

<script>
// Delete topic function
function deleteTopic(topicId) {
    if (confirm('Are you sure you want to delete this topic? This action cannot be undone.')) {
        // Create form for DELETE method
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.module.topics.destroy", ":id") }}'.replace(':id', topicId);
        
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
