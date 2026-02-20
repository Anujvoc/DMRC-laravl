@extends('admin.partials.app')

@section('title', 'Manage Training Batches')

@section('content')
<style>
    body {
        background-color: #f4f6f9;
    }

    h2 {
        font-weight: 600;
        color: #4a4a4a;
    }

    /* Header Table Design */
    #trainingBatchesTable thead {
        background-color: #3f79b2;
        color: #fff;
    }

    #trainingBatchesTable thead th {
        border: none;
        padding: 14px 16px;
        font-weight: 600;
        font-size: 14px;
    }

    #trainingBatchesTable tbody td {
        padding: 18px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #5a6b7b;
    }

    #trainingBatchesTable {
        background: #fff;
        border-radius: 6px;
        overflow: hidden;
    }

    /* Batch Code */
    .batch-code {
        font-weight: 600;
        color: #5d738a;
    }

    /* Type Circle */
    .type-circle {
        width: 34px;
        height: 34px;
        background: #6c757d;
        color: #fff;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }

    /* Status Pill */
    .status-pill {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        background: #6c757d;
        color: #fff;
        display: inline-block;
    }

    .status-Planning { background: #6c757d; }
    .status-Active { background: #28a745; }
    .status-Completed { background: #17a2b8; }
    .status-Cancelled { background: #dc3545; }

    /* Action Buttons */
    .btn-edit {
        background: #5bc0de;
        border: none;
        color: #fff;
    }

    .btn-modules {
        background: #337ab7;
        border: none;
        color: #fff;
    }

    .btn-delete {
        background: #e74c3c;
        border: none;
        color: #fff;
    }

    .btn-edit:hover,
    .btn-modules:hover,
    .btn-delete:hover {
        opacity: 0.85;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 4px;
    }

    .actions-wrapper {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: flex-end;
    }

    /* Add Button */
    .btn-primary {
        background-color: #1abc9c;
        border: none;
    }

    .btn-primary:hover {
        background-color: #16a085;
    }

    /* Filter Section */
    .card {
        border: none;
        border-radius: 6px;
    }

    .table-responsive {
        overflow-x: auto;
    }
</style>

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
        <h2 class="mb-0">Training Batches Management</h2>

        <a href="{{ route('admin.training_batch.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add New Batch
        </a>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.training_batch.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="status" class="form-label">Filter by Status</label>
                    <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                        <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="Planning" {{ $statusFilter == 'Planning' ? 'selected' : '' }}>Planning</option>
                        <option value="Active" {{ $statusFilter == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Completed" {{ $statusFilter == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ $statusFilter == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-filter"></i> Apply Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.training_batch.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Training Batches Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="trainingBatchesTable">
                       <thead>
<tr>
    <th>Batch Code</th>
    <th>Batch Name</th>
    <th>Type</th>
    <th>Instructor</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Status</th>
    <th class="text-end">Actions</th>
</tr>
</thead>

                     <tbody>
@forelse($batches as $batch)
<tr>
    <td class="batch-code">
        {{ $batch->batch_code }}
    </td>

    <td>{{ $batch->batch_name }}</td>

    <td>
        <span class="type-circle">
            {{ substr($batch->training_type_name ?? 'T', 0, 1) }}
        </span>
    </td>

    <td>{{ $batch->company_name }}</td>

    <td>
        {{ \Carbon\Carbon::parse($batch->start_date)->format('Y-m-d') }}
    </td>

    <td>
        {{ \Carbon\Carbon::parse($batch->end_date)->format('Y-m-d') }}
    </td>

    <td>
        <span class="status-pill status-{{ $batch->status }}">
            {{ $batch->status }}
        </span>
    </td>

    <td class="text-end">
        <div class="actions-wrapper">
            <a href="{{ route('admin.training_batch.edit', $batch->batch_id) }}"
               class="btn btn-sm btn-edit">
                <i class="fas fa-edit"></i> Edit
            </a>

            <a href="#" class="btn btn-sm btn-modules">
                <i class="fas fa-th"></i> Modules
            </a>

          <form action="{{ route('admin.training_batch.destroy', $batch->batch_id) }}" 
      method="POST" 
      style="display:inline-block;"
      onsubmit="return confirm('Are you sure you want to delete this batch?')">

    @csrf
    @method('DELETE')

    <button type="submit" class="btn btn-sm btn-delete">
        <i class="fas fa-trash"></i> Delete
    </button>
</form>

        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-4">
        No training batches found.
    </td>
</tr>
@endforelse
</tbody>

                    </table>
                </div>
            </div>

            <!-- Grid View -->
            <div id="gridView" style="display: none;">
                <div class="row g-3" id="trainingBatchesGrid">
                    <!-- Training batches will be loaded here via JavaScript -->
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

@push('scripts')
<script>
// Sample data - replace with actual API calls
let trainingBatches = [
    {
        id: 1,
        batchCode: "TB001",
        batchName: "Web Development Basics",
        company: "Tech Solutions Inc.",
        designation: "Software Developer",
        module: "HTML/CSS",
        trainingType: "Technical",
        startDate: "2024-02-01",
        endDate: "2024-02-15",
        status: "Active",
        capacity: 25,
        instructor: "John Smith",
        created_at: "2024-01-15",
        updated_at: "2024-01-20"
    },
    {
        id: 2,
        batchCode: "TB002",
        batchName: "Database Management",
        company: "Healthcare Plus",
        designation: "Database Administrator",
        module: "SQL Fundamentals",
        trainingType: "Technical",
        startDate: "2024-03-01",
        endDate: "2024-03-20",
        status: "Planning",
        capacity: 30,
        instructor: "Sarah Johnson",
        created_at: "2024-01-10",
        updated_at: "2024-01-18"
    },
    {
        id: 3,
        batchCode: "TB003",
        batchName: "Project Management",
        company: "Finance Corp",
        designation: "Project Manager",
        module: "Agile Methodology",
        trainingType: "Management",
        startDate: "2024-02-10",
        endDate: "2024-02-25",
        status: "Completed",
        capacity: 20,
        instructor: "Mike Wilson",
        created_at: "2024-01-05",
        updated_at: "2024-01-22"
    }
];

// View toggle functionality
function toggleView(viewType) {
    const tableView = document.getElementById('tableView');
    const gridView = document.getElementById('gridView');
    
    if (viewType === 'grid') {
        tableView.style.display = 'none';
        gridView.style.display = 'block';
        renderGridView();
    } else {
        tableView.style.display = 'block';
        gridView.style.display = 'none';
    }
}

// Render grid view
function renderGridView() {
    const gridContainer = document.getElementById('trainingBatchesGrid');
    gridContainer.innerHTML = '';
    
    trainingBatches.forEach(batch => {
        const card = `
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title">${batch.batchName}</h6>
                            ${getStatusBadge(batch.status)}
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Batch Code:</small>
                            <div class="fw-semibold">${batch.batchCode}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Company:</small>
                            <div>${batch.company}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Module:</small>
                            <div>${batch.module}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Dates:</small>
                            <div>${formatDate(batch.startDate)} - ${formatDate(batch.endDate)}</div>
                        </div>
                        <div class="d-flex gap-1 mt-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="editTrainingBatch(${batch.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTrainingBatch(${batch.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        gridContainer.innerHTML += card;
    });
}

// Status badge function
function getStatusBadge(status) {
    const badges = {
        'Planning': '<span class="badge bg-warning">Planning</span>',
        'Active': '<span class="badge bg-success">Active</span>',
        'Completed': '<span class="badge bg-info">Completed</span>',
        'Cancelled': '<span class="badge bg-danger">Cancelled</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">' + status + '</span>';
}

// Format date function
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Edit training batch function
function editTrainingBatch(batchId) {
    window.location.href = `/admin/training-batch/${batchId}/edit`;
}

// Delete training batch function
function deleteTrainingBatch(batchId) {
    if (confirm('Are you sure you want to delete this training batch? This action cannot be undone.')) {
        // Use fetch API for better error handling
        fetch(`/admin/training-batch/${batchId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '/admin/training-batch';
            } else {
                alert('Error deleting training batch. Please try again.');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            alert('Error deleting training batch. Please try again.');
        });
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add view toggle buttons
});
</script>
@endpush
