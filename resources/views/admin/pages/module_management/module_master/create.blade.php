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
            <form method="post" action="{{ route('admin.module.master.store') }}" onsubmit="debugFormSubmit(event)">
                @csrf
                <input type="hidden" name="topic_id" id="primary_topic_id" value="0">
                <input type="hidden" name="subtopic_id" id="primary_subtopic_id" value="0">

                <div class="mb-3">
                    <label class="form-label">Module Name *</label>
                    <input type="text" class="form-control" name="module_name" placeholder="Enter module name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Module Code</label>
                    <input type="text" class="form-control" name="module_code" placeholder="Enter module code (optional)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject *</label>
                    <input type="text" class="form-control" name="subject" placeholder="Enter subject" required>
                </div>

             

                <div class="mb-3">
                    <label class="form-label">Version</label>
                    <input type="text" class="form-control" name="version" placeholder="Enter version (e.g., 1.0, 2.1)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Sessions</label>
                    <input type="number" class="form-control" name="total_sessions" placeholder="Enter total sessions" min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label">Sequence Order</label>
                    <input type="number" class="form-control" name="sequence" placeholder="Enter sequence order" min="1">
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
                    <label class="form-label">Duration (Days) *</label>
                    <input type="number" class="form-control" name="duration_days" placeholder="Enter duration in days" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status *</label>
                    <select class="form-control" name="status" required>
                        <option value="">Select Status</option>
                        <option value="Planned">Planned</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

             
               <label for="">Pre-requisites</label>
                <?php
                $existingModules = DB::table('training_modules')->get();
                ?>
                @if($existingModules->count() > 0)
                    @foreach($existingModules as $module)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="prerequisites[]" id="prereq_{{ $module->module_id }}" value="{{ $module->module_id }}">
                            <label class="form-check-label" for="prereq_{{ $module->module_id }}">
                                {{ $module->module_name }}
                            </label>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No existing modules available as prerequisites.</p>
                @endif

                <!-- Mapped Competency Section -->
                <div class="mb-4">
                    <label class="form-label">Mapped Competency</label>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mapped_domain" id="mapped_domain_comp" value="1">
                                <label class="form-check-label" for="mapped_domain_comp">
                                    Domain
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mapped_functional" id="mapped_functional_comp" value="1">
                                <label class="form-check-label" for="mapped_functional_comp">
                                    Functional
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mapped_behavioral" id="mapped_behavioral_comp" value="1">
                                <label class="form-check-label" for="mapped_behavioral_comp">
                                    Behavioral
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mapped_other" id="mapped_other" value="1">
                                <label class="form-check-label" for="mapped_other">
                                    Other
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Module Objectives</label>
                    <textarea class="form-control" name="module_objectives" rows="3" placeholder="Enter module objectives and dependencies"></textarea>
                </div>

                <!-- Topics and Subtopics Section -->
                <div class="mt-4" style="width:90%;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Topics and Subtopics</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addTopicRow()">
                            <i class="bi bi-plus"></i> Add Topic
                        </button>
                    </div>
                    
                    <div id="topicsContainer">
                        <div class="topic-row mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Topic</label>
                                    <select class="form-control topic-select" name="topics[]" onchange="loadSubtopics(this)">
                                        <option value="">Select Topic</option>
                                        <?php
                                        $topics = DB::table('topics')->get();
                                        foreach($topics as $topic) {
                                            echo '<option value="' . $topic->topic_id . '">' . $topic->topic . '</option>';
                                        }
                                        ?>
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
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Add Module
                </button>
                <a href="{{ route('admin.module.master') }}" class="btn btn-secondary ms-2">
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
