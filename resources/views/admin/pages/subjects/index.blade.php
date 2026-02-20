@extends('admin.partials.app')

@section('title', 'Subjects')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Subjects</h1>
            <span>Manage subjects</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.master.subjects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add Subject
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="subjectsTable">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">+</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
@forelse($subjects as $subject)

<tr>
    <!-- Toggle Column -->
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary"
                onclick="toggleDetails({{ $subject->id }})"
                id="btn-{{ $subject->id }}">
            +
        </button>
    </td>

    <!-- Subject Data -->
    <td>{{ $subject->id }}</td>
    <td>{{ $subject->title }}</td>

    <!-- Actions Column -->
    <td class="text-end">
        <div class="d-flex gap-1 justify-content-end">
            <a href="{{ route('admin.master.subjects.edit', $subject->id) }}"
               class="btn btn-sm btn-outline-secondary btn-icon">
                <i class="fas fa-edit"></i>
            </a>

            <button class="btn btn-sm btn-danger btn-icon"
                    onclick="deleteSubject({{ $subject->id }})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>

<!-- Hidden Expandable Row -->
<tr id="details-{{ $subject->id }}" style="display:none;">
    <td colspan="4">
        <div class="p-3 bg-light border rounded">

            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Topic</th>
                        <th>Subtopic</th>
                        <th>Sessions</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($subject->topics as $topic)
                    @forelse($topic->subtopics as $subtopic)
                        <tr>
                            <td>{{ $topic->topic }}</td>
                            <td>{{ $subtopic->subtopic }}</td>
                            <td>{{ $subtopic->sessions }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>{{ $topic->topic }}</td>
                            <td colspan="2" class="text-muted">No subtopics</td>
                        </tr>
                    @endforelse
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            No topics found
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
    </td>
</tr>

@empty
<tr>
    <td colspan="4" class="text-center">No subjects found</td>
</tr>
@endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSubject(id) {
    if(confirm('Are you sure you want to delete this subject?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/master/subjects/' + id;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<script>
function toggleDetails(id) {

    let row = document.getElementById('details-' + id);
    let btn = document.getElementById('btn-' + id);

    if (row.style.display === "none") {
        row.style.display = "table-row";
        btn.innerHTML = "−";
        btn.classList.remove("btn-outline-primary");
        btn.classList.add("btn-outline-danger");
    } else {
        row.style.display = "none";
        btn.innerHTML = "+";
        btn.classList.remove("btn-outline-danger");
        btn.classList.add("btn-outline-primary");
    }
}
</script>

@endsection
