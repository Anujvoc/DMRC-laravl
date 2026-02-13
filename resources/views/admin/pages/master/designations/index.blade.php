@extends('admin.partials.app')

@section('title', 'Designations')

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
    <h2 class="mb-0">Designations Management</h2>

    <a href="{{ route('admin.master.designations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add New Designation
    </a>
</div>

    
 

    <!-- Statistics Cards -->


    <!-- Designations Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="designationsTable">
                        <thead>
                            <tr>
                               
                                <th>Designation Name</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Level Order</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($designations as $designation)
                                <tr>
                                 
                                    <td>
                                        <div class="d-flex align-items-center">
                                          
                                            <div>
                                                <div class="fw-semibold">{{ $designation->designation_name ?? 'Unknown Designation' }}</div>
                                                <small class="text-muted">{{ $designation->designation_code ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $designation->designation_code ?? 'N/A' }}</td>
                                    <td>{{ $designation->description ?? 'N/A' }}</td>
                                    <td>{{ $designation->level_order ?? 'N/A' }}</td>
                                   
                                    <td>
                                        @if(($designation->is_active ?? 1) == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.master.designations.edit', $designation->designation_id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="deleteDesignation({{ $designation->designation_id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fi fi-sr-building fa-3x mb-3"></i>
                                            <h5>No designations found</h5>
                                            <p>Start by adding your first designation.</p>
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
                <div class="row g-3" id="designationsGrid">
                    <!-- Designations will be loaded here via JavaScript -->
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

<!-- Add Designation Modal -->

@endsection

<script>
// Delete designation function
function deleteDesignation(designationId) {
    if (confirm('Are you sure you want to delete this designation? This action cannot be undone.')) {
        // Create form for DELETE method
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.master.designations.destroy", ":id") }}'.replace(':id', designationId);
        
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
