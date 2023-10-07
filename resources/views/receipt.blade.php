<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $vehicle->TicketNumber }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3>Park and Go Official Receipt</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>ID:</strong> {{ $vehicle->id }}</p>
                        <p><strong>Ticket Number:</strong> {{ $vehicle->TicketNumber }}</p>
                        <p><strong>Vehicle Make:</strong> {{ $vehicle->VehicleMake }}</p>
                        <p><strong>Plate Number:</strong> {{ $vehicle->PlateNumber }}</p>
                        <p><strong>Driver Name:</strong> {{ $vehicle->DriverName }}</p>
                        <p><strong>Entry Time:</strong> {{ $vehicle->EntryTime }}</p>
                        <p><strong>Exit Time:</strong> {{ $vehicle->ExitTime }}</p>
                        @php
                            $entryTime = \Carbon\Carbon::parse($vehicle->EntryTime);
                            $exitTime = \Carbon\Carbon::parse($vehicle->ExitTime);
                            $duration = $entryTime->diff($exitTime)->format('%Hh %Im');
                        @endphp
                        <p><strong>Parking Duration:</strong> {{ $duration }}</p>
                        <p><strong>Parking Fee:</strong> ₱{{ number_format($vehicle->ParkingFee, 2) }}</p>
                        <p><strong>Remark:</strong> {{ $vehicle->Remark }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                        <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>
