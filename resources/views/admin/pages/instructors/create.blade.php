@extends('admin.partials.app')

@section('title', 'Add Instructor')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Add Instructor</h1>
            <span>Create a new instructor</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.master.instructors.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.master.instructors.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" required value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Designation *</label>
                            <select class="form-control" name="designation" required>
                                <option value="">Select Designation</option>
                                @foreach($designations as $designation)
                                    <option value="{{ $designation->designation_name }}" {{ old('designation') == $designation->designation_name ? 'selected' : '' }}>
                                        {{ $designation->designation_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Employee ID *</label>
                            <input type="text" class="form-control" name="employee_id" required value="{{ old('employee_id') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject *</label>
                            <div class="border rounded p-2" style="max-height: 220px; overflow:auto;">
                                @foreach($subjects as $subject)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" id="subject_{{ $subject->id }}"
                                            {{ (is_array(old('subject_ids')) && in_array($subject->id, old('subject_ids'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="subject_{{ $subject->id }}">
                                            {{ $subject->title }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('admin.master.instructors.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Instructor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
