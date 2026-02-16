@extends('admin.partials.app')

@section('title', 'Module Master')

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
    #modulesTable thead {
        background-color: #3f79b2;
        color: #fff;
    }

    #modulesTable thead th {
        border: none;
        padding: 14px 16px;
        font-weight: 600;
        font-size: 14px;
    }

    #modulesTable tbody td {
        padding: 18px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #5a6b7b;
    }

    #modulesTable {
        background: #fff;
        border-radius: 6px;
        overflow: hidden;
    }

    /* Module Code */
    .module-code {
        font-weight: 600;
        color: #5d738a;
    }

    /* Type Circle */
    .type-circle {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    .type-theory { background-color: #28a745; }
    .type-practical { background-color: #ffc107; }
    .type-mixed { background-color: #17a2b8; }

    /* Status Badge */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Action Buttons */
    .btn-icon {
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Card Design */
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stats-card h3 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    .stats-card p {
        margin: 5px 0 0 0;
        opacity: 0.9;
    }
</style>

<div class="app-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Module Master</h2>
        <div class="d-flex gap-2">
            
            <a href="{{ route('admin.module.master.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add Module
            </a>
        </div>
    </div>



    <!-- Modules Table -->
    <div class="card border-0 shadow-sm" style="width:127%;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="modulesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Module Name</th>
                            <th>Subject</th>
                            <th>Version</th>
                            <th>Total Sessions</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modules as $module)
                        <tr>
                            <td>{{$module->module_id}}</td>
                            <td>{{$module->module_name}}</td>
                            <td>{{$module->subject}}</td>
                            <td>{{$module->version ?? '1.0'}}</td>
                            <td>{{$module->total_sessions ?? 0}}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.module.master.edit', $module->module_id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="deleteModule({{$module->module_id}})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Module Modal -->
<div class="modal fade" id="addModuleModal" tabindex="-1" aria-labelledby="addModuleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addModuleForm" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="module_name" class="form-label">Module Name</label>
                            <input type="text" name="module_name" id="module_name" class="form-control" 
                                   placeholder="Enter Module Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" 
                                   placeholder="Enter Subject" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="level" class="form-label">Level</label>
                            <input type="text" name="level" id="level" class="form-control" 
                                   placeholder="Enter Level" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="total_sessions" class="form-label">Total Sessions</label>
                            <input type="number" name="total_sessions" id="total_sessions" class="form-control" 
                                   placeholder="Enter Total Sessions" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Planned">Planned</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="duration_days" class="form-label">Duration (Days)</label>
                            <input type="number" name="duration_days" id="duration_days" class="form-control" 
                                   placeholder="Enter Duration in Days" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sequence" class="form-label">Sequence</label>
                            <input type="number" name="sequence" id="sequence" class="form-control" 
                                   placeholder="Enter Sequence" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="mapped_domain" class="form-label">Mapped Domain</label>
                            <select name="mapped_domain" id="mapped_domain" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="mapped_functional" class="form-label">Mapped Functional</label>
                            <select name="mapped_functional" id="mapped_functional" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="mapped_behavioral" class="form-label">Mapped Behavioral</label>
                            <select name="mapped_behavioral" id="mapped_behavioral" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="module_objectives" class="form-label">Module Objectives (Dependencies)</label>
                        <textarea name="module_objectives" id="module_objectives" class="form-control" rows="3" 
                                  placeholder="Enter Module Objectives/Dependencies"></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Add Module
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Dependency Flow Modal -->
<div class="modal fade" id="dependencyFlowModal" tabindex="-1" aria-labelledby="dependencyFlowModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Module Dependency Flow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Module Dependencies</h6>
                        <div id="dependencyChart" style="height: 400px; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                            <!-- Dependency chart will be rendered here -->
                            <div class="text-center text-muted mt-5">
                                <i class="bi bi-diagram-3" style="font-size: 48px;"></i>
                                <p class="mt-3">Module dependency visualization will appear here</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Dependency Details</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Prerequisites</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>MOD001</td>
                                        <td>-</td>
                                        <td><span class="badge bg-success">No Dependencies</span></td>
                                    </tr>
                                    <tr>
                                        <td>MOD002</td>
                                        <td>MOD001</td>
                                        <td><span class="badge bg-warning">Has Prerequisites</span></td>
                                    </tr>
                                    <tr>
                                        <td>MOD003</td>
                                        <td>MOD001, MOD002</td>
                                        <td><span class="badge bg-warning">Has Prerequisites</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update statistics
    updateStatistics();
});

function updateStatistics() {
    const totalModules = document.querySelectorAll('#modulesTable tbody tr').length;
    const activeModules = document.querySelectorAll('#modulesTable tbody tr').length; // All modules are considered active for now
    const plannedModules = document.querySelectorAll('#modulesTable tbody tr').length; // Will be updated based on status field

    document.getElementById('totalModules').textContent = totalModules;
    document.getElementById('activeModules').textContent = activeModules;
    document.getElementById('theoryModules').textContent = plannedModules;
    document.getElementById('practicalModules').textContent = totalModules; // Update as needed
}

function showAddModuleModal() {
    const modal = new bootstrap.Modal(document.getElementById('addModuleModal'));
    modal.show();
}

function showDependencyFlow() {
    const modal = new bootstrap.Modal(document.getElementById('dependencyFlowModal'));
    modal.show();
}

function deleteModule(moduleId) {
    if(confirm('Are you sure you want to delete this module?')) {
        // Create form for DELETE method
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/module/module-master/' + moduleId;
        
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

// Handle form submission
document.getElementById('addModuleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Add your form submission logic here
    const formData = new FormData(this);
    
    // Simulate API call
    console.log('Adding module:', Object.fromEntries(formData));
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addModuleModal'));
    modal.hide();
    
    // Show success message
    alert('Module added successfully!');
});
</script>
@endsection
