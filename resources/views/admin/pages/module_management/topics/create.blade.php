@extends('admin.partials.app')

@section('title', 'Add Topic')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Add Topic</h1>
            <span>Create a new topic</span>
        </div>
      
    </div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.module.topics.store') }}">
                        @csrf
                        <div class="row g-4">
                            <!-- Topic Information -->
                            <h5 class="card-title mb-3 bg-primary bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 0 0 1rem 0;">
                                <i class="fi fi-sr-book me-2"></i>Topic Information
                            </h5>
                            
                            <div class="col-md-6">
                                <label for="topic" class="form-label">Topic *</label>
                                <input type="text" class="form-control" id="topic" name="topic" required>
                            </div>

                            <div class="col-md-6">
                                <label for="subject_id" class="form-label">Subject</label>
                                <select class="form-select" id="subject_id" name="subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="subtopic" class="form-label">Subtopic</label>
                                <input type="text" class="form-control" id="subtopic" name="subtopic">
                            </div>
                            
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            
                            <!-- Additional Information -->
                            <h5 class="card-title mb-3 bg-info bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 1rem 0;">
                                <i class="fi fi-sr-info me-2"></i>Additional Information
                            </h5>
                            
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="0">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ route('admin.module.topics') }}" class="btn btn-outline-secondary">
                                        <i class="fi fi-rr-cross me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fi fi-rr-save me-1"></i> Save Topic
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
@endsection
