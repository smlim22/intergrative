@extends('layouts.app')
@section('title', 'Availability Schedule')
@section('content')
<?php 
/**
 * Author : Adrean Goh
 */
?>
<div class="container">
    <h2 class="mb-4">Facility Schedule</h2>


    <div class="row mb-3">
        <div class="col-md-6">
            <label for="facilityID" class="form-label fw-bold">Select Facility</label>
            <select class="form-select" id="facilityID">
                <option value="" disabled selected>Select a facility</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="reservation_date" class="form-label fw-bold">Select Date</label>
            <input type="date" class="form-control" id="reservation_date" min="{{ date('Y-m-d') }}">
        </div>
    </div>

    <!-- Schedule List/Table part -->
    <div id="schedule-container" class="mt-4">
        <p class="text-muted">Please select a facility and date to view the schedule.</p>
    </div>
</div>

<script>
function loadSchedule() {
    let facilityID = document.getElementById('facilityID').value;
    let reservation_date = document.getElementById('reservation_date').value;

    if (!facilityID || !reservation_date) {
        return;
    }

    fetch(`/api/booking/schedule?facilityID=${facilityID}&reservation_date=${reservation_date}`, {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        let container = document.getElementById('schedule-container');

        if (data.error) {
            container.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            return;
        }

        let rows = '';
        for (let hour = 8; hour < 18; hour++) {
            let start = String(hour).padStart(2, '0') + ':00:00';
            let end = String(hour+1).padStart(2, '0') + ':00:00';

            let taken = data.reservations.some(r => r.start_time < end && r.end_time > start);

            let actionBtn = taken
                ? `<button class="btn btn-secondary btn-sm" disabled>Unavailable</button>`
                : `<button class="btn btn-success btn-sm" onclick="bookNow('${facilityID}','${reservation_date}','${start}','${end}')">Book Now</button>`;

            rows += `
                <tr>
                    <td>${start} - ${end}</td>
                    <td>${taken ? '<span class="badge bg-danger">Booked</span>' : '<span class="badge bg-success">Available</span>'}</td>
                    <td>${actionBtn}</td>
                </tr>
            `;
        }

        container.innerHTML = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Time Slot</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        `;
    })
    .catch(error => {
        document.getElementById('schedule-container').innerHTML =
            '<div class="alert alert-warning">Error loading schedule.</div>';
    });
}

document.getElementById('facilityID').addEventListener('change', loadSchedule);
document.getElementById('reservation_date').addEventListener('change', loadSchedule);

function bookNow(facilityID, reservation_date, start_time, end_time) {
    window.location.href = `/bookCheck?facilityID=${facilityID}&reservation_date=${reservation_date}&start_time=${start_time}&end_time=${end_time}`;
}
</script>
@endsection
