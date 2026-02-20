@extends('admin.partials.app')

@section('title', 'Edit Certification')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Edit Certification</h1>
            <span>Update ISO standard</span>
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
                    <form method="POST" action="{{ route('admin.master.certifications.update', $certification->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">ISO Standard *</label>
                            <input type="text" class="form-control" name="iso_standard" required value="{{ old('iso_standard', $certification->iso_standard) }}">
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('admin.master.certifications.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Certification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
