@extends('admin.partials.app')

@section('title', 'Edit Company')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Edit Company</h1>
            <span>Update company profile</span>
        </div>
      
    </div>
<div class="container mt-4">
    <div class="card border-primary" style="width:60%; margin-left:147px;">
        <div class="card-header  text-white" style="background-color:#337ab7;">
            <strong>Edit Company</strong>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.master.companies.update', $company->company_id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" class="form-control" name="company_name" value="{{ $company->company_name ?? '' }}" placeholder="Enter company name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Company Code</label>
                    <input type="text" class="form-control" name="company_code" value="{{ $company->company_code ?? '' }}" placeholder="Enter company code">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="3" placeholder="Enter company address">{{ $company->address ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ $company->phone ?? '' }}" placeholder="Enter phone number">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $company->email ?? '' }}" placeholder="Enter email address">
                </div>

                <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input type="text" class="form-control" name="website" value="{{ $company->website ?? '' }}" placeholder="Enter website URL">
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Person</label>
                    <input type="text" class="form-control" name="contact_person" value="{{ $company->contact_person ?? '' }}" placeholder="Enter contact person name">
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $company->is_active == 1 ? 'checked' : '' }}>
                    <label class="form-check-label">
                        Active
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Company
                </button>
                <a href="{{ route('admin.master.companies') }}" class="btn btn-secondary ms-2">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
