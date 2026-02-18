@extends('admin.partials.app')

@section('title', 'Edit Training Program')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Edit Training Program</h1>
            <span>Update training program information</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.training_program.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Programs
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.training_program.update', $program->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Program Information -->
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Program Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required
                                       placeholder="Enter program title" value="{{ $program->title }}">
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <a href="{{ route('admin.training_program.index') }}" class="btn btn-outline-secondary">
                                    <i class="fi fi-rr-cross me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fi fi-rr-save me-1"></i> Update Program
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
