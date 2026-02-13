@extends('admin.partials.app')

@section('title', 'Edit Designation')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Edit Designation</h1>
            <span>Update designation profile</span>
        </div>
      
    </div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.master.designations.update', $designation->designation_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Designation Information -->
                            <h5 class="card-title mb-3 bg-primary bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 0 0 1rem 0;">
                                <i class="fi fi-sr-briefcase me-2"></i>Designation Information
                            </h5>
                            
                            <div class="col-md-6">
                                <label for="designation_name" class="form-label">Designation Name *</label>
                                <input type="text" class="form-control" id="designation_name" name="designation_name" value="{{ $designation->designation_name ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="designation_code" class="form-label">Designation Code</label>
                                <input type="text" class="form-control" id="designation_code" name="designation_code" value="{{ $designation->designation_code ?? '' }}">
                            </div>
                            
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ $designation->description ?? '' }}</textarea>
                            </div>
                            
                            <!-- Additional Information -->
                            <h5 class="card-title mb-3 bg-info bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 1rem 0;">
                                <i class="fi fi-sr-info me-2"></i>Additional Information
                            </h5>
                            
                            <div class="col-md-6">
                                <label for="level_order" class="form-label">Level Order</label>
                                <input type="number" class="form-control" id="level_order" name="level_order" min="0" value="{{ $designation->level_order ?? 0 }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select" id="is_active" name="is_active">
                                    <option value="1" {{ $designation->is_active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $designation->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ route('admin.master.designations') }}" class="btn btn-outline-secondary">
                                        <i class="fi fi-rr-cross me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fi fi-rr-save me-1"></i> Update Designation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
