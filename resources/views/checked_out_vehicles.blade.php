<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checked Out Vehicles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>

<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Checked Out Vehicles</h2>
                    <p class="card-text">List of all vehicles that have checked out.</p>
                </div>
                <div class="pull-right mb-2">
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-bordered table-striped" id="checkedOutVehiclesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ticket Number</th>
                        <th>Vehicle Make</th>
                        <th>Plate Number</th>
                        <th>Driver Name</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Parking Duration</th>
                        <th>Parking Fee</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->id }}</td>
                            <td>{{ $vehicle->TicketNumber }}</td>
                            <td>{{ $vehicle->VehicleMake }}</td>
                            <td>{{ $vehicle->PlateNumber }}</td>
                            <td>{{ $vehicle->DriverName }}</td>
                            <td>{{ $vehicle->EntryTime }}</td>
                            <td>{{ $vehicle->ExitTime }}</td>
                            <td>
                                @php
                                    $entryTime = \Carbon\Carbon::parse($vehicle->EntryTime);
                                    $exitTime = \Carbon\Carbon::parse($vehicle->ExitTime);
                                    $duration = $entryTime->diff($exitTime)->format('%Hh %Im');
                                @endphp
                                {{ $duration }}
                            </td>
                            <td>₱{{ number_format($vehicle->ParkingFee, 2) }}</td>
                            <td>{{ $vehicle->Remark }}</td>
                            <td>
                                <!-- Trigger the modal with a button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewModal{{ $vehicle->id }}">View</button>
                                <a href="{{ url('/receipt/' . $vehicle->id) }}" class="btn btn-secondary btn-sm"
                                    target="_blank">Print Receipt</a>
                            </td>
                        </tr>
        
                        <!-- Modal -->
                        <div class="modal fade" id="viewModal{{ $vehicle->id }}" tabindex="-1"
                            aria-labelledby="viewModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewModalLabel">Vehicle Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>ID:</strong> {{ $vehicle->id }}</p>
                                        <p><strong>Ticket Number:</strong> {{ $vehicle->TicketNumber }}</p>
                                        <p><strong>Vehicle Make:</strong> {{ $vehicle->VehicleMake }}</p>
                                        <p><strong>Plate Number:</strong> {{ $vehicle->PlateNumber }}</p>
                                        <p><strong>Driver Name:</strong> {{ $vehicle->DriverName }}</p>
                                        <p><strong>Entry Time:</strong> {{ $vehicle->EntryTime }}</p>
                                        <p><strong>Exit Time:</strong> {{ $vehicle->ExitTime }}</p>
                                        <p><strong>Parking Duration:</strong> {{ $duration }}</p>
                                        <p><strong>Parking Fee:</strong> ₱{{ number_format($vehicle->ParkingFee, 2) }}
                                        </p>
                                        <p><strong>Remark:</strong> {{ $vehicle->Remark }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end mt-3">
                <a href="http://localhost:8000/dashboard" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
        </div>
        

        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('#checkedOutVehiclesTable').DataTable();
            });
        </script>
</body>

</html>
