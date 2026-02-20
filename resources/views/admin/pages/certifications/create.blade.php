@extends('admin.partials.app')

@section('title', 'Add Certification')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Add Certification</h1>
            <span>Create a new ISO standard</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.master.certifications.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.master.certifications.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">ISO Standard *</label>
                            <input type="text" class="form-control" name="iso_standard" required value="{{ old('iso_standard') }}" placeholder="e.g. ISO 9001:2015">
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('admin.master.certifications.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Certification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
