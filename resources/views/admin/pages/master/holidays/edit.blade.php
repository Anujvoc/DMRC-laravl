@extends('admin.partials.app')

@section('title', 'Edit Holiday')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Edit Holiday</h1>
            <span>Update holiday information</span>
        </div>
      
    </div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.master.holidays.update', $holiday->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Holiday Information -->
                            <h5 class="card-title mb-3 bg-primary bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 0 0 1rem 0;">
                                <i class="fi fi-sr-calendar me-2"></i>Holiday Information
                            </h5>
                            
                            <div class="col-12">
                                <label for="holiday_name" class="form-label">Holiday Name *</label>
                                <input type="text" class="form-control" id="holiday_name" name="holiday_name" value="{{ $holiday->holiday_name ?? '' }}" required placeholder="Enter holiday name">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="date" class="form-label">Holiday Date *</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $holiday->date ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Day of Week</label>
                                <input type="text" class="form-control" id="dayOfWeek" readonly placeholder="Will be calculated automatically">
                            </div>
                            
                            <!-- Additional Information -->
                            <h5 class="card-title mb-3 bg-info bg-opacity-10 p-3 rounded" style="background-color: #f8f9fa !important; width:100%; margin: 1rem 0;">
                                <i class="fi fi-sr-info me-2"></i>Additional Information
                            </h5>
                            
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fi fi-sr-info me-2"></i>
                                    <strong>Note:</strong> Holiday dates cannot be set in the past. The system will automatically calculate the day of the week for the selected date.
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ route('admin.master.holidays') }}" class="btn btn-outline-secondary">
                                        <i class="fi fi-rr-cross me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fi fi-rr-save me-1"></i> Update Holiday
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

<script>
// Calculate day of week when date changes
document.getElementById('date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayOfWeek = days[selectedDate.getDay()];
    document.getElementById('dayOfWeek').value = dayOfWeek;
});

// Set minimum date to today
document.getElementById('date').min = new Date().toISOString().split('T')[0];

// Initialize day of week on page load
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    if (dateInput.value) {
        const selectedDate = new Date(dateInput.value);
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const dayOfWeek = days[selectedDate.getDay()];
        document.getElementById('dayOfWeek').value = dayOfWeek;
    }
});
</script>
@endsection
