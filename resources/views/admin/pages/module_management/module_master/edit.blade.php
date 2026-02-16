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
            <form method="post" action="{{ route('admin.module.master.update', $module->module_id) }}" onsubmit="debugFormSubmit(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="topic_id" id="primary_topic_id" value="0">
                <input type="hidden" name="subtopic_id" id="primary_subtopic_id" value="0">

                <div class="mb-3">
                    <label class="form-label">Module Name *</label>
                    <input type="text" class="form-control" name="module_name" value="{{ $module->module_name }}" placeholder="Enter module name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Module Code</label>
                    <input type="text" class="form-control" name="module_code" value="{{ $module->module_code }}" placeholder="Enter module code (optional)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject *</label>
                    <input type="text" class="form-control" name="subject" value="{{ $module->subject }}" placeholder="Enter subject" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Version</label>
                    <input type="text" class="form-control" name="version" value="{{ $module->version }}" placeholder="Enter version (e.g., 1.0, 2.1)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Sessions</label>
                    <input type="number" class="form-control" name="total_sessions" value="{{ $module->total_sessions }}" placeholder="Enter total sessions">
                </div>

                <div class="mb-3">
                    <label class="form-label">Sequence</label>
                    <input type="number" class="form-control" name="sequence" value="{{ $module->sequence }}" placeholder="Enter sequence order">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Start Date *</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $module->start_date }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">End Date *</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $module->end_date }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Duration (Days) *</label>
                    <input type="number" class="form-control" name="duration_days" id="duration_days" value="{{ $module->duration_days }}" placeholder="Enter duration in days" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status *</label>
                    <select class="form-control" name="status" required>
                        <option value="">Select Status</option>
                        <option value="Planned" {{ $module->status == 'Planned' ? 'selected' : '' }}>Planned</option>
                        <option value="In Progress" {{ $module->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ $module->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Module Objectives</label>
                    <textarea class="form-control" name="module_objectives" rows="3" placeholder="Enter module objectives and dependencies">{{ $module->module_objectives }}</textarea>
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
                        <!-- Existing topics will be loaded here -->
                        @foreach($moduleTopics as $index => $moduleTopic)
                            <div class="topic-row mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Topic</label>
                                        <select class="form-control topic-select" name="topics[]" onchange="loadSubtopics(this)">
                                            <option value="">Select Topic</option>
                                            @foreach($topics as $topic)
                                                <option value="{{ $topic->topic_id }}" {{ $moduleTopic->topic_id == $topic->topic_id ? 'selected' : '' }}>{{ $topic->topic }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Subtopic</label>
                                        <select class="form-control subtopic-select" name="subtopics[]">
                                            <option value="">Select Topic First</option>
                                            @if($moduleTopic->subtopic_id)
                                                <!-- Subtopics will be loaded via JavaScript -->
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeTopicRow(this)">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        @endforeach
                        
                        @if(count($moduleTopics) == 0)
                            <!-- Add one empty row if no existing topics -->
                            <div class="topic-row mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Topic</label>
                                        <select class="form-control topic-select" name="topics[]" onchange="loadSubtopics(this)">
                                            <option value="">Select Topic</option>
                                            @foreach($topics as $topic)
                                                <option value="{{ $topic->topic_id }}">{{ $topic->topic }}</option>
                                            @endforeach
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
                        @endif
                    </div>
                </div>

                <!-- Mapped Competency Section -->
                <div class="mt-4">
                    <h5 class="mb-3">Mapped Competency</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mapped_domain" value="1" {{ $module->mapped_domain ? 'checked' : '' }}>
                                <label class="form-check-label">Domain</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mapped_functional" value="1" {{ $module->mapped_functional ? 'checked' : '' }}>
                                <label class="form-check-label">Functional</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mapped_behavioral" value="1" {{ $module->mapped_behavioral ? 'checked' : '' }}>
                                <label class="form-check-label">Behavioral</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mapped_other" value="1" {{ $module->mapped_other ? 'checked' : '' }}>
                                <label class="form-check-label">Other</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prerequisites Section -->
                <div class="mt-4">
                    <h5 class="mb-3">Prerequisites</h5>
                    <div class="row">
                        @php
                            $allModules = DB::table('module_master')->where('module_id', '!=', $module->module_id)->get();
                        @endphp
                        @foreach($allModules as $prereqModule)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="prerequisites[]" value="{{ $prereqModule->module_id }}" 
                                           @if(in_array($prereqModule->module_id, $prerequisites)) checked @endif>
                                    <label class="form-check-label">{{ $prereqModule->module_name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Module
                    </button>
                    <a href="{{ route('admin.module.master') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Modules
                    </a>
                </div>
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
