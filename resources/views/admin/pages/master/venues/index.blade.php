@extends('admin.partials.app')

@section('title','Venues')

@section('content')

<div class="container mt-5 ml-5 " id="venueContainer">

    <div id="venuesHeader">
        @if(session('success'))
        <small class="text-success">{{session('success')}}</small>
        @endif
        @if(session('error'))
        <small class="text-danger">{{session('error')}}</small>
        @endif
        <h1>Venues Management</h1>
    </div>
    <button type="button" class="btn btn-primary text-light fs-2 p-1" data-bs-toggle="modal"
        data-bs-target="#addVenuesModal"><i class="bi bi-plus-lg fs-2 fw-bold p-1"></i> Add Veneus </button>
    <div class="table row" id="venueTable">

        <table class="table table table-striped  " id="venueTables">
            <thead>

                <h3 class="text-light bg-primary px-2">Venue List</h3>

                <tr>

                    <th>Venues Name</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach($venue as $item)
                <tr>
                    <td>{{$item->venue_name}}</td>
                    <td>{{$item->address}}</td>
                    <td class="pr-5 mr-5">@if($item->is_active === 1)

                        <a href="{{url('admin/master/venues/toggle/'.$item->venue_id)}}"
                            class="btn btn-success  btn-sm px-2 py-1 mr-5">Active</a>
                        @else
                        <a href="{{url('admin/master/venues/toggle/'.$item->venue_id)}}"
                            class="btn  btn-danger btn-sm px-2 py-1 le">Inactive</a>

                        @endif
                    </td>
                    <td class=" d-flex ml-5"><button type="button" class="btn btn btn ml-2 m-0 p-0  editVenuesModal"
                            data-bs-toggle="modal" data-bs-target="#editVenuesModal" data-id="{{$item->venue_id}}"
                            data-name="{{$item->venue_name}}" data-address="{{$item->address}}"> <i
                                class="bi bi-pencil-square bg-dark text-light"></i></button>
                        <form action="{{ url('admin/master/venues/'. $item->venue_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn m-0 p-0 border-0 bg-transparent">
                                <i class="bi bi-trash bg-danger text-light p-1"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add venues modal is hare-->
<div class="modal fade" id="addVenuesModal" tabindex="-1" aria-hidde="true" aria-labelledby="addVenueModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Venues</h5>
                @if(session('success'))
                <small class="text-success">{{session('success')}}</small>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">@foreach($errors->all() as $error)
                    <div>{{$error}}</div>
                    @endforeach
                </div>
                @endif
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body ">

                <form class="mt-5" action="{{url('admin/master/venues/add')}}" method="post">
                    @csrf
                    <div class="mt-5">
                        <label for="venue-name">Venue Name</label>
                        <input type="text" name="venue_name" placeholder="Enter Venue Name" class="form-control">
                        @error('venue_name')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mt-5">
                        <label for="address">Address</label>
                        <input type="text" name="address" placeholder="Enter Venue Name" class="form-control">
                        @error('address')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary" type="button">submit</button>

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close</button>
            </div>
        </div>
    </div>
</div>

<!--Updata modal-->
<div class="modal fade" id="editVenuesModal" tabindex="-1" aria-hidde="true" aria-labelledby="editVenueModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Venues</h5>

                @if($errors->any())
                <div class="alert alert-danger">@foreach($errors->all() as $error)
                    <div>{{$error}}</div>
                    @endforeach
                </div>
                @endif
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body ">

                <form class="mt-5" action="{{url('admin/master/venues/update')}}" method="post">
                    @csrf

                    <input type="hidden" name="id" id="edit_id">
                    <div class="mt-5">
                        <label for="venue-name">Venue Name</label>
                        <input type="text" name="venue_name" id="edit_name" placeholder="Enter Venue Name"
                            class="form-control">
                        @error('venue_name')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mt-5">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="edit_address" placeholder="Enter Venue Name"
                            class="form-control">
                        @error('address')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary" type="button">submit</button>

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close</button>
            </div>
        </div>
    </div>
</div>

@if($errors->any() || session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    let myAddVenueModal = new bootstrap.Modal(document.getElementById('addVenuesModal'));
    myAddVenueModal.show();

    let myEditVenueModal = new bootstrap.Modal(document.getElementById('editVenuesModal'));
    myEditVenueModal.show();
});
</script>

@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll(".editVenuesModal").forEach(btn => {
        btn.addEventListener("click", function() {
            document.getElementById("edit_id").value = this.dataset.id;
            document.getElementById("edit_name").value = this.dataset.name;
            document.getElementById("edit_address").value = this.dataset.address;
        });
    });

});
</script>
@endsection