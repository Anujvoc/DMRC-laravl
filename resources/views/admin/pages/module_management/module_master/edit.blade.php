@extends('admin.partials.app')

@section('title', 'Edit Module')

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
            <h1 class="app-page-title">Edit Module</h1>
            <span>Update training module information</span>
        </div>
    </div>

    <div class="card border-primary" style="width:163%; margin-left:33px;">
        <div class="card-body">
         <form method="post" action="{{ route('admin.module.master.update', $module->id) }}">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Summary Title *</label>
    <input type="text" class="form-control" name="summary_title"
           value="{{ $module->summary_title }}" required>
</div>

<div class="mb-3">
    <label>Subject *</label>
    <input type="text" class="form-control" name="subject"
           value="{{ $module->subject }}" required>
</div>

<div class="mb-3">
    <label>Category</label>
    <select name="category_id" class="form-control">
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ (old('category_id', $module->category_id) == $category->id) ? 'selected' : '' }}>
                {{ $category->title }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Module Doc No</label>
    <input type="text" class="form-control" name="module_doc_no"
           value="{{ old('module_doc_no', $module->module_doc_no) }}">
</div>

<div class="mb-3">
    <label>Revision No *</label>
    <input type="text" class="form-control" name="rev_no"
           value="{{ $module->rev_no }}" required>
</div>

<div class="mb-3">
    <label>Date *</label>
    <input type="date" class="form-control" name="form_date"
           value="{{ $module->form_date }}" required>
</div>

<div class="mb-3">
    <label>Date2</label>
    <input type="date" class="form-control" name="date2"
           value="{{ old('date2', $module->date2) }}">
</div>

<hr>

<h5>Mapped Competency</h5>
<div class="row">
    <div class="col-md-4">
        <input type="radio" name="mapped_competency" value="Domain"
               {{ $module->mapped_competency=='Domain'?'checked':'' }}> Domain
    </div>
    <div class="col-md-4">
        <input type="radio" name="mapped_competency" value="Functional"
               {{ $module->mapped_competency=='Functional'?'checked':'' }}> Functional
    </div>
    <div class="col-md-4">
        <input type="radio" name="mapped_competency" value="Behavioral"
               {{ $module->mapped_competency=='Behavioral'?'checked':'' }}> Behavioral
    </div>
    <div class="col-md-4">
        <input type="radio" name="mapped_competency" value="All Competencies"
               {{ $module->mapped_competency=='All Competencies'?'checked':'' }}> All Competencies
    </div>
</div>

<hr>

<div class="mb-3">
    <label>Total Sessions *</label>
    <input type="number" class="form-control" name="total_sessions"
           value="{{ $module->total_sessions }}" required>
</div>

<div class="mb-3">
    <label>No of Days *</label>
    <input type="number" class="form-control" name="no_of_days"
           value="{{ $module->no_of_days }}" required>
</div>

<button type="submit" class="btn btn-primary">
    Update Module
</button>

<a href="{{ route('admin.module.master') }}" class="btn btn-secondary">
    Back
</a>

</form>

        </div>
    </div>
</div>

<script>
// Store topics data globally for dynamic rows
const topicsData = @json($topics ?? []);

// Store module topics data for subtopic selection
const moduleTopicsData = @json($moduleTopics ?? []);

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
    const container = document.getElementById('topicsContainer');
    if (container.children.length > 1) {
        button.closest('.topic-row').remove();
    } else {
        alert('At least one topic row is required.');
    }
}

// Load subtopics based on selected topic
function loadSubtopics(selectElement) {
    const topicId = selectElement.value;
    const subtopicSelect = selectElement.closest('.topic-row').querySelector('.subtopic-select');
    
    console.log('Loading subtopics for topic:', topicId);
    
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
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Subtopics data received:', data);
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

// Load existing subtopics on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load subtopics for existing topic selections
    document.querySelectorAll('.topic-select').forEach((select, index) => {
        if (select.value) {
            loadSubtopics(select);
            // Set the subtopic value after loading
            setTimeout(() => {
                const subtopicSelect = select.closest('.topic-row').querySelector('.subtopic-select');
                const subtopicId = moduleTopicsData[index]?.subtopic_id || null;
                if (subtopicId) {
                    subtopicSelect.value = subtopicId;
                }
            }, 500);
        }
    });
});
</script>
@endsection
