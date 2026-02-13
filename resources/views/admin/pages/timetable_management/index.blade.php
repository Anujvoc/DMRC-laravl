@extends('admin.partials.app')

@section('title','Weekly Training')

@section('content')
<div class="container" id="weeklyTrainingContainer">

    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control"
                       value="{{ request('start_date') }}">
            </div>

            <div class="col-md-4">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control"
                       value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4">
                <label for="time">Time</label>
                <select name="time" class="form-select" id="time">
                    <option value="8">8</option>
                    <option value="7">7</option>
                    <option value="6">6</option>
                </select>
            </div>
<div class="col-md-3">
                <label>Sessions Per Day</label>
                <select name="time" class="form-select">
                    <option value="8" {{ request('time',8)==8?'selected':'' }}>8</option>
                    <option value="7" {{ request('time')==7?'selected':'' }}>7</option>
                    <option value="6" {{ request('time')==6?'selected':'' }}>6</option>
                </select>
            </div>
            <div class="col-md-1 mt-1">
                <button class="btn btn-primary">Generate</button>
            </div>
        </div>
    </form>


    <div class="mb-3">
        <a href="?start_date={{request('start_date')}}&end_date={{request('end_date')}}&week={{$weekOffset-1}}"
           class="btn btn-sm btn-secondary">Prev Week</a>

        <a href="?start_date={{request('start_date')}}&end_date={{request('end_date')}}&week={{$weekOffset+1}}"
           class="btn btn-sm btn-success">Next Week</a>
    </div>

     <table class="table table-bordered text-center">

        <thead class="table-dark">
            <tr>
                <th>Time</th>
                @foreach($workingDays as $day)
                    <th>
                        {{ $day['day'] }} <br>
                        <small>{{ $day['date'] }}</small>
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody>

        @foreach($timeSlots as $slot)
            <tr>

                <td>
                    <strong>{{ $slot['time'] }}</strong><br>
                    <small>{{ $slot['label'] }}</small>
                </td>

                @foreach($workingDays as $day)

                    @if($slot['type'] == 'session')
                        <td>Session</td>

                    @elseif($slot['type'] == 'tea')
                        <td class="bg-warning">Tea Break</td>

                    @elseif($slot['type'] == 'lunch')
                        <td class="bg-info">Lunch Break</td>
                    @endif

                @endforeach

            </tr>
        @endforeach

        </tbody>

    </table>


</div>
@endsection