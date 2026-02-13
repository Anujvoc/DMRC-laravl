@extends('admin.partials.app')

@section('title', 'Venues')

@section('content')
<style>
    th{
          font-size: 14px;
    font-weight: 800;
    color: gray;
    }

      
</style>

<div class="app-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Venues Management</h2>

    <button type="button" class="btn btn-primary" onclick="openVenueModal()">
        <i class="bi bi-plus"></i> Add New Venue
    </button>
</div>

    

    <!-- Statistics Cards -->


    <!-- Venues Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="venuesTable">
                        <thead>
                            <tr>
                               
                                <th>Venue Name</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venue as $item)
                            <tr>
                                <td>{{$item->venue_name}}</td>
                                <td>{{$item->address}}</td>
                                <td>@if($item->is_active === 1)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary editVenueBtn"
                                        data-bs-toggle="modal" data-bs-target="#editVenuesModal" data-id="{{$item->venue_id}}"
                                        data-name="{{$item->venue_name}}" data-address="{{$item->address}}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="{{ route('admin.master.venues.destroy', $item->venue_id) }}" 
                                        class="btn btn-sm btn-outline-danger delete-item">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Venue Modal -->
<div class="modal fade" id="venueModal" tabindex="-1" aria-labelledby="venueModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="venueModalTitle">Add Venue</h5>
                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{$error}}</div>
                    @endforeach
                </div>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="venueForm" method="post">
                    @csrf
                    <input type="hidden" name="id" id="venue_id">
                    
                    <div class="mb-3">
                        <label for="venue_name" class="form-label">Venue Name</label>
                        <input type="text" name="venue_name" id="venue_name" class="form-control" 
                               placeholder="Enter Venue Name" required>
                        @error('venue_name')
                            <div class="text-danger small">{{$message}}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control" 
                                  placeholder="Enter Venue Address" rows="3" required></textarea>
                        @error('address')
                            <div class="text-danger small">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-lg"></i> Add Venue
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button clicks
    document.querySelectorAll(".editVenueBtn").forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const address = this.dataset.address;
            
            // Populate form fields
            document.getElementById("venue_id").value = id;
            document.getElementById("venue_name").value = name;
            document.getElementById("address").value = address;
            
            // Change modal title and button text
            document.getElementById("venueModalTitle").textContent = "Edit Venue";
            document.getElementById("submitBtn").innerHTML = '<i class="bi bi-check-lg"></i> Update Venue';
            
            // Set form action to update route
            document.getElementById("venueForm").action = "{{ route('admin.master.venues.update') }}";
            
            // Show modal
            const venueModal = new bootstrap.Modal(document.getElementById('venueModal'));
            venueModal.show();
        });
    });
});

function openVenueModal() {
    // Reset form for adding new venue
    document.getElementById("venueForm").reset();
    document.getElementById("venue_id").value = "";
    document.getElementById("venueModalTitle").textContent = "Add Venue";
    document.getElementById("submitBtn").innerHTML = '<i class="bi bi-check-lg"></i> Add Venue';
    
    // Set form action to add route
    document.getElementById("venueForm").action = "{{ route('admin.master.venues.add') }}";
    
    // Show modal
    const venueModal = new bootstrap.Modal(document.getElementById('venueModal'));
    venueModal.show();
}

// Handle delete confirmation
document.querySelectorAll(".delete-item").forEach(btn => {
    btn.addEventListener("click", function(e) {
        if(confirm('Are you sure you want to delete this venue?')) {
            // Allow the delete to proceed
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection