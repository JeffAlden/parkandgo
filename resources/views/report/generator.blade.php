<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Periodic Report Generator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>

<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Periodic Report Generator</h2>
                    <p class="card-text">Generate reports for vehicles that have checked out within a specific time
                        frame.</p><br>
                </div>
            </div>
        </div>

        <!-- Date Range Filters -->
        <div class="row mb-3">
            <div class="col-md-5">
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" class="form-control">
            </div>
            <div class="col-md-5">
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" class="form-control">
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button class="btn btn-primary form-control" id="generateReportBtn">Generate Report</button>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="container mt-2">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="periodicReportTable">
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
                        <!-- DataTables will dynamically render table rows here based on AJAX data -->
                    </tbody>
                </table>
                 <div class="text-end mt-3">
        <a href="http://localhost:8000/dashboard" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>
            </div>
        </div>

        <!-- Single Modal Structure -->
        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">Vehicle Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ID:</strong> <span id="modalId"></span></p>
                        <p><strong>Ticket Number:</strong> <span id="modalTicketNumber"></span></p>
                        <p><strong>Vehicle Make:</strong> <span id="modalVehicleMake"></span></p>
                        <p><strong>Plate Number:</strong> <span id="modalPlateNumber"></span></p>
                        <p><strong>Driver Name:</strong> <span id="modalDriverName"></span></p>
                        <p><strong>Entry Time:</strong> <span id="modalEntryTime"></span></p>
                        <p><strong>Exit Time:</strong> <span id="modalExitTime"></span></p>
                        <p><strong>Parking Duration:</strong> <span id="modalParkingDuration"></span></p>
                        <p><strong>Parking Fee:</strong> <span id="modalParkingFee"></span></p>
                        <p><strong>Remark:</strong> <span id="modalRemark"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                var table = $('#periodicReportTable').DataTable({
                    ajax: '{{ route('getPeriodicReport') }}',
                    columns: [{
                            data: 'id',
                            title: 'ID'
                        },
                        {
                            data: 'TicketNumber',
                            title: 'Ticket Number'
                        },
                        {
                            data: 'VehicleMake',
                            title: 'Vehicle Make'
                        },
                        {
                            data: 'PlateNumber',
                            title: 'Plate Number'
                        },
                        {
                            data: 'DriverName',
                            title: 'Driver Name'
                        },
                        {
                            data: 'EntryTime',
                            title: 'Entry Time'
                        },
                        {
                            data: 'ExitTime',
                            title: 'Exit Time'
                        },
                        {
                            data: null,
                            title: 'Parking Duration',
                            render: function(data, type, row) {
                                var entryTime = new Date(row.EntryTime);
                                var exitTime = new Date(row.ExitTime);
                                var duration = exitTime - entryTime;
                                var hours = Math.floor(duration / (1000 * 60 * 60));
                                var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
                                return hours + 'h ' + minutes + 'm';
                            }
                        },
                        {
                            data: 'ParkingFee',
                            title: 'Parking Fee',
                            render: function(data, type, row) {
                                return '₱' + parseFloat(data).toFixed(2);
                            }
                        },
                        {
                            data: 'Remark',
                            title: 'Remark'
                        },
                        {
                            data: null,
                            title: 'Action',
                            render: function(data, type, row) {
                                return '<div class="d-flex">' +
                                    '<button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#viewModal">View</button>' +
                                    '<a href="/receipt/' + data.id +
                                    '" class="btn btn-secondary btn-sm" target="_blank">Print Receipt</a>' +
                                    '</div>';
                            }
                        }
                    ]
                });

                // Event listener for opening the modal and updating its content
                $('#periodicReportTable tbody').on('click', 'button', function() {
                    var data = table.row($(this).parents('tr')).data();

                    // Update modal content using the data object
                    $('#modalId').text(data.id);
                    $('#modalTicketNumber').text(data.TicketNumber);
                    $('#modalVehicleMake').text(data.VehicleMake);
                    $('#modalPlateNumber').text(data.PlateNumber);
                    $('#modalDriverName').text(data.DriverName);
                    $('#modalEntryTime').text(data.EntryTime);
                    $('#modalExitTime').text(data.ExitTime);

                    // Calculate Parking Duration
                    var entryTime = new Date(data.EntryTime);
                    var exitTime = new Date(data.ExitTime);
                    var duration = exitTime - entryTime;
                    var hours = Math.floor(duration / (1000 * 60 * 60));
                    var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
                    $('#modalParkingDuration').text(hours + 'h ' + minutes + 'm');

                    $('#modalParkingFee').text('₱' + parseFloat(data.ParkingFee).toFixed(2));
                    $('#modalRemark').text(data.Remark);

                    $('#modalReceiptLink').attr('href', '/receipt/' + data.id);
                    $('#viewModal').modal('show');
                });

                // Generate Report Button Click Event
                $('#generateReportBtn').click(function() {
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    table.ajax.url('{{ route('getPeriodicReport') }}?startDate=' + startDate + '&endDate=' +
                        endDate).load();
                });

            });
        </script>


</body>

</html>
