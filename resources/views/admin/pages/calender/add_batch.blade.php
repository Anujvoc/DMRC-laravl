@extends('admin.partials.app')

@section('title','Add Batch')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Add New Batch</h1>
            <span>Create a new training batch</span>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card border-primary" style="width:60%; margin-left:147px;">
            <div class="card-header text-white" style="background-color:#337ab7;">
                <strong>Add New Batch</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('admin.timetable-management.batch.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Batch Name *</label>
                        <input type="text" class="form-control" name="batch_name" placeholder="Enter batch name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Batch Code *</label>
                        <input type="text" class="form-control" name="batch_code" placeholder="Enter batch code" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Start Date *</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">End Date *</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Capacity *</label>
                        <input type="number" class="form-control" name="capacity" placeholder="Enter batch capacity" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Enter batch description"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Batch
                    </button>
                    <a href="{{ route('admin.timetable-management.index') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
