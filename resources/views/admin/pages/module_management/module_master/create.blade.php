@extends('admin.partials.app')

@section('title', 'Add Module')

@section('content')

<style>
    label{
        font-weight: 600;
        color: #2e6da4;
    }
</style>
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Add Module</h1>
            <span>Create a new training module with detailed information</span>
        </div>
    </div>


    <div class="card border-primary" style="width:163%; margin-left:33px;">
        

        <div class="card-body">
         <form method="post" action="{{ route('admin.module.master.store') }}">
@csrf

<div class="row">

    <div class="col-md-6 mb-3">
        <label>Summary Title *</label>
        <input type="text" name="summary_title" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Subject *</label>
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

    <div class="col-md-4 mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control">
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Certification</label>
        <select name="certification_id" class="form-control">
            <option value="">Select Certification</option>
            @foreach($certifications as $certification)
                <option value="{{ $certification->id }}" {{ old('certification_id') == $certification->id ? 'selected' : '' }}>
                    {{ $certification->iso_standard }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Module Doc No</label>
        <input type="text" name="doc_no" class="form-control" value="{{ old('doc_no') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Revision No *</label>
        <input type="text" name="rev_no" class="form-control" required>
    </div>

    <div class="col-md-4 mb-3">
        <label>Form Date *</label>
        <input type="date" name="form_date" class="form-control" required>
    </div>

    <div class="col-md-4 mb-3">
        <label>Date2</label>
        <input type="date" name="date2" class="form-control" value="{{ old('date2') }}">
    </div>

    <div class="form-group">
    <label><b>Mapped Competency</b></label>

    <div style="display:flex; gap:30px; margin-top:8px">

        <label>
            <input type="radio" name="mapped_competency" value="Domain" required>
            Domain
        </label>

        <label>
            <input type="radio" name="mapped_competency" value="Functional">
            Functional
        </label>

        <label>
            <input type="radio" name="mapped_competency" value="Behavioral">
            Behavioral
        </label>

        <label>
            <input type="radio" name="mapped_competency" value="All Competencies">
            All Competencies
        </label>

    </div>
</div>


</div>

<hr>





<h5>Module Duration</h5>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Total Sessions *</label>
        <input type="number" name="total_sessions" class="form-control" required min="1">
    </div>

    <div class="col-md-6 mb-3">
        <label>No of Days *</label>
        <input type="number" name="no_of_days" class="form-control" required min="1">
    </div>
</div>

<hr>

<button class="btn btn-primary">
    Save Module
</button>

<a href="{{ route('admin.module.master') }}" class="btn btn-secondary">
    Cancel
</a>

</form>

        </div>
    </div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum end date to start date
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
    });
    
    // Copy duration_days to no_of_days when changed
    const durationDays = document.getElementById('duration_days');
    const noOfDays = document.getElementById('no_of_days');
    
    durationDays.addEventListener('input', function() {
        if (!noOfDays.value) {
            noOfDays.value = this.value;
        }
    });
});

// Store topics data globally for dynamic rows
const topicsData = @json($topics ?? []);

// Add new topic row
function addTopicRow() {
    const container = document.getElementById('topicsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'topic-row mb-3 p-3 border rounded';
    
    // Generate topic options from stored data
    let topicOptions = '<option value="">Select Topic</option>';
    topicsData.forEach(topic => {
        topicOptions += `<option value="${topic.topic_id}">${topic.topic}</option>`;
    });
    
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Topic</label>
                <select class="form-control topic-select" name="topics[]" onchange="loadSubtopics(this)">
                    ${topicOptions}
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Subtopic</label>
                <select class="form-control subtopic-select" name="subtopics[]">
                    <option value="">Select Topic First</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeTopicRow(this)">
            <i class="bi bi-trash"></i> Remove
        </button>
    `;
    container.appendChild(newRow);
}

// Remove topic row
function removeTopicRow(button) {
    const row = button.closest('.topic-row');
    const container = document.getElementById('topicsContainer');
    
    // Keep at least one row
    if (container.children.length > 1) {
        row.remove();
    } else {
        alert('At least one topic row is required.');
    }
}

// Load subtopics based on selected topic
function loadSubtopics(selectElement) {
    const topicId = selectElement.value;
    const subtopicSelect = selectElement.closest('.topic-row').querySelector('.subtopic-select');
    
    console.log('Loading subtopics for topic:', topicId); // Debug log
    
    // Update primary topic_id if this is the first row
    const isFirstRow = selectElement.closest('.topic-row').parentElement.firstElementChild === selectElement.closest('.topic-row');
    if (isFirstRow) {
        document.getElementById('primary_topic_id').value = topicId;
    }
    
    // Clear current subtopics
    subtopicSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (topicId) {
        // Fetch subtopics via AJAX
        fetch(`/admin/module/api/subtopics/${topicId}`)
            .then(response => {
                console.log('Response status:', response.status); // Debug log
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Subtopics data received:', data); // Debug log
                subtopicSelect.innerHTML = '<option value="">Select Subtopic</option>';
                if (data && data.length > 0) {
                    data.forEach(subtopic => {
                        subtopicSelect.innerHTML += `<option value="${subtopic.subtopic_id}">${subtopic.subtopic}</option>`;
                    });
                } else {
                    subtopicSelect.innerHTML += '<option value="">No subtopics available for selected topic</option>';
                }
            })
            .catch(error => {
                console.error('Error loading subtopics:', error);
                subtopicSelect.innerHTML = '<option value="">Error loading subtopics</option>';
            });
    } else {
        subtopicSelect.innerHTML = '<option value="">Select Topic First</option>';
    }
}

// Update primary subtopic_id when subtopic is selected
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('subtopic-select')) {
        const isFirstRow = e.target.closest('.topic-row').parentElement.firstElementChild === e.target.closest('.topic-row');
        if (isFirstRow) {
            document.getElementById('primary_subtopic_id').value = e.target.value;
        }
    }
});

// Debug function to check form data before submission
function debugFormSubmit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const topics = formData.getAll('topics[]');
    const subtopics = formData.getAll('subtopics[]');
    
    console.log('=== FORM DEBUG INFO ===');
    console.log('All form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
    }
    
    console.log('Topics array:', topics);
    console.log('Subtopics array:', subtopics);
    console.log('Topics length:', topics.length);
    console.log('Subtopics length:', subtopics.length);
    
    // Check if topics are empty
    const emptyTopics = topics.filter(topic => topic === '');
    console.log('Empty topics:', emptyTopics.length);
    
    // Check if subtopics are empty
    const emptySubtopics = subtopics.filter(subtopic => subtopic === '');
    console.log('Empty subtopics:', emptySubtopics.length);
    
    // Show alert with debug info
    alert(`Debug Info:
Topics: ${topics.length} items (${emptyTopics.length} empty)
Subtopics: ${subtopics.length} items (${emptySubtopics.length} empty)
Check browser console for details`);
    
    // Submit the form after debug
    event.target.submit();
}
</script>
@endsection
