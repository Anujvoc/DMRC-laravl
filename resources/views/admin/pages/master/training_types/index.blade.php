@extends('admin.partials.app')

@section('title', 'Training Types')

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
    <h2 class="mb-0">Training Types Management</h2>

    <a href="{{ route('admin.master.training_types.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add New Training Type
    </a>
</div>

    
 

    <!-- Statistics Cards -->


    <!-- Training Types Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="trainingTypesTable">
                        <thead>
                            <tr>
                               
                                <th>Training Type Name</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Duration (Days)</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trainingTypes as $trainingType)
                                <tr>
                                 
                                    <td>
                                        <div class="d-flex align-items-center">
                                          
                                            <div>
                                                <div class="fw-semibold">{{ $trainingType->training_type_name ?? 'Unknown Training Type' }}</div>
                                                <small class="text-muted">{{ $trainingType->training_type_code ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $trainingType->training_type_code ?? 'N/A' }}</td>
                                    <td>{{ $trainingType->description ?? 'N/A' }}</td>
                                    <td>{{ $trainingType->duration_days ?? 'N/A' }}</td>
                                   
                                    <td>
                                        @if(($trainingType->is_active ?? 1) == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.master.training_types.edit', $trainingType->training_type_id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="deleteTrainingType({{ $trainingType->training_type_id }})">
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
                                            <h5>No training types found</h5>
                                            <p>Start by adding your first training type.</p>
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
                <div class="row g-3" id="trainingTypesGrid">
                    <!-- Training types will be loaded here via JavaScript -->
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

<!-- Add Training Type Modal -->

@endsection

<script>
// Delete training type function
function deleteTrainingType(trainingTypeId) {
    if (confirm('Are you sure you want to delete this training type? This action cannot be undone.')) {
        // Create form for DELETE method
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.master.training_types.destroy", ":id") }}'.replace(':id', trainingTypeId);
        
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
