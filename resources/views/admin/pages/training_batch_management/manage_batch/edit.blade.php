@extends('admin.partials.app')

@section('title', 'Edit Training Batch')

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
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Edit Training Batch</h1>
            <span>Update training batch information</span>
        </div>
      
    </div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.training_batch.update', $batch->batch_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Basic Information -->
                            <h5 class="card-title mb-3 bg-primary bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 0 0 1rem 0;">
                                <i class="fi fi-sr-graduation-cap me-2"></i>Basic Information
                            </h5>
                            
                            <div class="col-md-6">
                                <label for="batch_name" class="form-label">Batch Name *</label>
                                <input type="text" class="form-control" id="batch_name" name="batch_name" value="{{ $batch->batch_name ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="batch_code" class="form-label">Batch Code *</label>
                                <input type="text" class="form-control" id="batch_code" name="batch_code" value="{{ $batch->batch_code ?? '' }}" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ $batch->description ?? '' }}</textarea>
                            </div>
                            
                            <!-- Organization Information -->
                            <h5 class="card-title mb-3 bg-info bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 1rem 0;">
                                <i class="fi fi-sr-building me-2"></i>Organization Information
                            </h5>
                            
                            <div class="col-md-6">
                                <label for="batch_code_company" class="form-label">Company *</label>
                                <select class="form-select" id="batch_code_company" name="batch_code_company" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->company_id }}" {{ $batch->batch_code_company == $company->company_id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="batch_code_designation" class="form-label">Designation *</label>
                                <select class="form-select" id="batch_code_designation" name="batch_code_designation" required>
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->designation_id }}" {{ $batch->batch_code_designation == $designation->designation_id ? 'selected' : '' }}>{{ $designation->designation_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Module Information -->
                            <h5 class="card-title mb-3 bg-success bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 1rem 0;">
                                <i class="fi fi-sr-book me-2"></i>Module Information
                            </h5>
                            
                            <div class="col-md-6">
                                <label for="module_master_id" class="form-label">Module *</label>
                                <select class="form-select" id="module_master_id" name="module_master_id" required>
                                    <option value="">Select Module</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->topic_id }}" {{ $batch->module_master_id == $module->topic_id ? 'selected' : '' }}>{{ $module->topic }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="training_id" class="form-label">Training Type *</label>
                                <select class="form-select" id="training_id" name="training_id" required>
                                    <option value="">Select Training Type</option>
                                    @foreach($trainingTypes as $trainingType)
                                        <option value="{{ $trainingType->training_type_id }}" {{ $batch->training_id == $trainingType->training_type_id ? 'selected' : '' }}>{{ $trainingType->training_type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Schedule Information -->
                            <h5 class="card-title mb-3 bg-warning bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 1rem 0;">
                                <i class="fi fi-sr-calendar me-2"></i>Schedule Information
                            </h5>
                            
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $batch->start_date ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $batch->end_date ?? '' }}">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="daily_hours" class="form-label">Daily Hours *</label>
                                <input type="text" class="form-control" id="daily_hours" name="daily_hours" value="{{ $batch->daily_hours ?? '' }}" placeholder="e.g., 8 hours" required>
                            </div>
                            
                            <!-- Additional Information -->
                            <h5 class="card-title mb-3 bg-secondary bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 1rem 0;">
                                <i class="fi fi-sr-info me-2"></i>Additional Information
                            </h5>
                            
                            <div class="col-md-3">
                                <label for="batch_coordinator_id" class="form-label">Coordinator ID</label>
                                <input type="number" class="form-control" id="batch_coordinator_id" name="batch_coordinator_id" value="{{ $batch->batch_coordinator_id ?? '' }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="batch_manager_id" class="form-label">Manager ID</label>
                                <input type="number" class="form-control" id="batch_manager_id" name="batch_manager_id" value="{{ $batch->batch_manager_id ?? '' }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="venue" class="form-label">Venue ID</label>
                                <input type="number" class="form-control" id="venue" name="venue" value="{{ $batch->venue ?? '' }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="batch_type" class="form-label">Batch Type *</label>
                                <input type="number" class="form-control" id="batch_type" name="batch_type" min="1" value="{{ $batch->batch_type ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="instructor_id" class="form-label">Instructor ID</label>
                                <input type="number" class="form-control" id="instructor_id" name="instructor_id" value="{{ $batch->instructor_id ?? '' }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="max_capacity" class="form-label">Max Capacity</label>
                                <input type="number" class="form-control" id="max_capacity" name="max_capacity" min="1" value="{{ $batch->max_capacity ?? 50 }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Planning" {{ $batch->status == 'Planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="Active" {{ $batch->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Completed" {{ $batch->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ $batch->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ route('admin.training_batch.index') }}" class="btn btn-outline-secondary">
                                        <i class="fi fi-rr-cross me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fi fi-rr-save me-1"></i> Update Training Batch
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
