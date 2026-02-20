@extends('admin.partials.app')

@section('title', 'Add Training Batch')

@section('content')

<style>
    body {
        background-color: #f3f4f6;
    }

    .page-header {
            background: #c9d8c1;
    padding: 17px 40px;
    border-bottom: 1px solid #dcdcdc;
    margin-top: 106px;
    /* width: 260px; */
    margin-left: 177px;
    width: 73%;
    /* margin-top: 95px; */
    margin-bottom: -40px;
    }

    .page-header h1 {
        margin: 0;
        font-weight: 500;
        color: #3f4a3f;
    }

    .form-wrapper {
        max-width: 950px;
        margin: 40px auto;
        background: #fff;
        padding: 40px 60px;
        border-radius: 4px;
        box-shadow: 0 0 0 1px #e5e7eb;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-label {
        font-weight: 600;
        color: #5f6f85;
    }

    .form-control,
    .form-select {
        height: 48px;
        border-radius: 4px;
        border: 1px solid #d1d5db;
        font-size: 15px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #9ca3af;
        box-shadow: none;
    }

    .required {
        color: red;
    }

    .batch-code-field {
        background: #f3f4f6;
    }

    .helper-text {
        font-size: 14px;
        color: #6b7280;
    }

    .helper-text span {
        color: #d63384;
        font-weight: 500;
    }

    .btn-submit {
        background-color: #1abc9c;
        border: none;
        padding: 10px 30px;
        font-size: 15px;
    }

    .btn-submit:hover {
        background-color: #16a085;
    }

    .btn-cancel {
        padding: 10px 25px;
    }
    .app-main{
        margin-left: 215px;
    }
</style>


<div class="page-header">
    <h1>Add New Training Batch</h1>
</div>

<div class="form-wrapper">

    <form method="POST" action="{{ route('admin.training_batch.store') }}">
        @csrf

        <div class="row">

            <!-- Company -->
            <div class="col-md-4 form-group">
                <label class="form-label">Company <span class="required">*</span></label>
                <select class="form-select" name="batch_code_company" required>
                    <option value="">- Company -</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->company_id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Designation -->
            <div class="col-md-4 form-group">
                <label class="form-label">Designation <span class="required">*</span></label>
                <select class="form-select" name="batch_code_designation" required>
                    <option value="">- Designation -</option>
                    @foreach($designations as $designation)
                        <option value="{{ $designation->designation_id }}">{{ $designation->designation_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Training Type -->
            <div class="col-md-4 form-group">
                <label class="form-label">Training Type <span class="required">*</span></label>
                <select class="form-select" name="training_id" required>
                    <option value="">- Training Type -</option>
                    @foreach($trainingTypes as $trainingType)
                        <option value="{{ $trainingType->training_type_id }}">{{ $trainingType->training_type_name }}</option>
                    @endforeach
                </select>
            </div>

              <div class="col-12 form-group">
                <label class="form-label">Batch Code <span class="required">*</span></label>
                <input type="text" class="form-control" name="batch_code" id="batch_code" required
                       placeholder="Auto-generated from selections" readonly>
                <div class="help-text mt-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Auto-generated from Company, Designation, and Training Type selections.
                    Format: [Company Code]-[Designation Code]-[Training Type Code]/2026-###
                </div>
            </div>

            <!-- Batch Coordinator -->
            <div class="col-12 form-group">
                <label class="form-label">Batch Coordinator</label>
                <select class="form-select" name="batch_coordinator_id">
                    <option value="">-- Select Coordinator --</option>
                    <option value="1">John Smith (john.smith@company.com)</option>
                    <option value="2">Sarah Johnson (sarah.johnson@company.com)</option>
                    <option value="3">Michael Davis (michael.davis@company.com)</option>
                    <option value="4">Emily Wilson (emily.wilson@company.com)</option>
                    <option value="5">Robert Brown (robert.brown@company.com)</option>
                </select>
            </div>

            <!-- Batch Manager -->
            <div class="col-12 form-group">
                <label class="form-label">Batch Manager</label>
                <select class="form-select" name="batch_manager_id">
                    <option value="">-- Select Manager --</option>
                    <option value="1">David Miller (david.miller@company.com)</option>
                    <option value="2">Jennifer Taylor (jennifer.taylor@company.com)</option>
                    <option value="3">William Anderson (william.anderson@company.com)</option>
                    <option value="4">Lisa Thomas (lisa.thomas@company.com)</option>
                    <option value="5">James Jackson (james.jackson@company.com)</option>
                </select>
            </div>

            <!-- Batch Name -->
            <div class="col-md-6">
                <label for="batch_name" class="form-label">Batch Name <span class="required">*</span></label>
                <input type="text" class="form-control" id="batch_name" name="batch_name" placeholder="Enter batch name" required>
            </div>

            <!-- Venue -->
            <div class="col-12 form-group">
                <label class="form-label">Venue <span class="required">*</span></label>
                <select class="form-select" name="venue" required>
                    <option value="">-- Select Venue --</option>
                    @if(isset($venues))
                        @foreach($venues as $venue)
                            <option value="{{ $venue->venue_id }}">{{ $venue->venue_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Module Master -->
            <div class="col-12 form-group">
                <label class="form-label">Module Master <span class="required">*</span></label>
              <select name="module_master_id" class="form-select" required>
    <option value="">Select Module</option>

    @foreach($modules as $module)
        <option value="{{ $module->id }}">
            {{ $module->summary_title }}
        </option>
    @endforeach
</select>


            </div>

            <!-- Training Program -->


 

            <!-- Start Date -->
            <div class="col-12 form-group">
                <label class="form-label">Start Date <span class="required">*</span></label>
                <input type="date" class="form-control" name="start_date" required>
            </div>

            <!-- End Date -->
            <div class="col-12 form-group">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date">
            </div>

            <!-- Daily Hours -->
            <div class="col-12 form-group">
                <label class="form-label">Daily Hours <span class="required">*</span></label>
                <select class="form-select" name="daily_hours" required>
                    <option value="">-- Select --</option>
                    <option value="6">6 Hours</option>
                    <option value="8">8 Hours</option>
                </select>
            </div>

            <!-- Batch Type -->
            <div class="col-12 form-group">
                <label class="form-label">Batch Type <span class="required">*</span></label>
                <select class="form-select" name="batch_type" required>
                    <option value="">-- Select Batch Type --</option>
                    <option value="1">Regular</option>
                    <option value="2">Weekend</option>
                    <option value="3">Online</option>
                </select>
            </div>

            <!-- Max Capacity -->
            <div class="col-12 form-group">
                <label class="form-label">Max Capacity</label>
                <input type="number" class="form-control" name="max_capacity" value="30">
            </div>

            <!-- Status -->
            <div class="col-12 form-group">
                <label class="form-label">Status <span class="required">*</span></label>
                <select class="form-select" name="status" required>
                    <option value="">-- Select Status --</option>
                    <option value="Planning">Planning</option>
                    <option value="Active">Active</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Description -->
            <div class="col-12 form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4" placeholder="Enter batch description..."></textarea>
            </div>

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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Validation Errors!</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- JavaScript Error Display -->
<div id="batch-code-errors" class="alert alert-warning" style="display: none;">
    <strong>Batch Code Generation Error!</strong>
    <span id="error-message"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

            <!-- Buttons -->
            <div class="col-12 text-end mt-4">
                <a href="{{ route('admin.training_batch.index') }}" class="btn btn-outline-secondary btn-cancel">
                    Cancel
                </a>
                <button type="submit" class="btn btn-submit">
                    Save Training Batch
                </button>
            </div>

        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the select elements
    const companySelect = document.querySelector('select[name="batch_code_company"]');
    const designationSelect = document.querySelector('select[name="batch_code_designation"]');
    const trainingTypeSelect = document.querySelector('select[name="training_id"]');
    const batchCodeInput = document.getElementById('batch_code');
    
    // Company codes mapping
    const companyCodes = {
        @foreach($companies as $company)
            '{{ $company->company_id }}': '{{ $company->company_code ?? strtoupper(substr($company->company_name, 0, 3)) }}',
        @endforeach
    };
    
    // Designation codes mapping
    const designationCodes = {
        @foreach($designations as $designation)
            '{{ $designation->designation_id }}': '{{ $designation->designation_code ?? strtoupper(substr($designation->designation_name, 0, 3)) }}',
        @endforeach
    };
    
    // Training type codes mapping
    const trainingTypeCodes = {
        @foreach($trainingTypes as $trainingType)
            '{{ $trainingType->training_type_id }}': '{{ $trainingType->training_type_code ?? strtoupper(substr($trainingType->training_type_name, 0, 3)) }}',
        @endforeach
    };
    
    // Function to generate batch code
    async function generateBatchCode() {
        const companyId = companySelect.value;
        const designationId = designationSelect.value;
        const trainingTypeId = trainingTypeSelect.value;
        
        // Validate selections first
        if (!validateSelections()) {
            return;
        }
        
        if (companyId && designationId && trainingTypeId) {
            const companyCode = companyCodes[companyId] || 'XXX';
            const designationCode = designationCodes[designationId] || 'XXX';
            const trainingTypeCode = trainingTypeCodes[trainingTypeId] || 'XXX';
            
            try {
                // Get actual batch count from database
                const response = await fetch('/admin/training-batch/api/batch-count', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new URLSearchParams({
                        company_code: companyCode,
                        designation_code: designationCode,
                        training_type_code: trainingTypeCode
                    })
                });
                
                const data = await response.json();
                const batchNumber = data.count;
                
                // Format with leading zeros (3 digits)
                const formattedNumber = batchNumber.toString().padStart(3, '0');
                
                const batchCode = `${companyCode}-${designationCode}-${trainingTypeCode}/2026-${formattedNumber}`;
                batchCodeInput.value = batchCode;
                
            } catch (error) {
                console.error('Error getting batch count:', error);
                // Show error to user
                showError('Failed to get batch count: ' + error.message);
                // Fallback to 001 if AJAX fails
                const batchCode = `${companyCode}-${designationCode}-${trainingTypeCode}/2026-001`;
                batchCodeInput.value = batchCode;
            }
        } else {
            batchCodeInput.value = '';
        }
    }
    
    // Add event listeners
    companySelect.addEventListener('change', generateBatchCode);
    designationSelect.addEventListener('change', generateBatchCode);
    trainingTypeSelect.addEventListener('change', generateBatchCode);
    
    // Generate initial batch code if all values are pre-selected
    generateBatchCode();
    
    // Debug function to check available data
    function debugBatchCodeGeneration() {
        console.log('=== DEBUG: Batch Code Generation ===');
        console.log('Company Options:', Object.keys(companyCodes));
        console.log('Designation Options:', Object.keys(designationCodes));
        console.log('Training Type Options:', Object.keys(trainingTypeCodes));
        console.log('Selected Company ID:', companySelect.value);
        console.log('Selected Designation ID:', designationSelect.value);
        console.log('Selected Training Type ID:', trainingTypeSelect.value);
        
        if (companySelect.value) {
            console.log('Company Code:', companyCodes[companySelect.value]);
        }
        if (designationSelect.value) {
            console.log('Designation Code:', designationCodes[designationSelect.value]);
        }
        if (trainingTypeSelect.value) {
            console.log('Training Type Code:', trainingTypeCodes[trainingTypeSelect.value]);
        }
        console.log('========================');
    }
    
    // Error display function
    function showError(message) {
        const errorDiv = document.getElementById('batch-code-errors');
        const errorMessage = document.getElementById('error-message');
        if (errorDiv && errorMessage) {
            errorMessage.textContent = message;
            errorDiv.style.display = 'block';
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }
    }
    
    // Validation function
    function validateSelections() {
        const companyId = companySelect.value;
        const designationId = designationSelect.value;
        const trainingTypeId = trainingTypeSelect.value;
        
        if (!companyId) {
            showError('Please select a company');
            return false;
        }
        
        if (!designationId) {
            showError('Please select a designation');
            return false;
        }
        
        if (!trainingTypeId) {
            showError('Please select a training type');
            return false;
        }
        
        if (!companyCodes[companyId]) {
            showError('Invalid company selection');
            return false;
        }
        
        if (!designationCodes[designationId]) {
            showError('Invalid designation selection');
            return false;
        }
        
        if (!trainingTypeCodes[trainingTypeId]) {
            showError('Invalid training type selection');
            return false;
        }
        
        // Hide error if all validations pass
        const errorDiv = document.getElementById('batch-code-errors');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
        
        return true;
    }
    
    // Add debug button to console
    window.debugBatchCode = debugBatchCodeGeneration;
});
</script>

@endsection

