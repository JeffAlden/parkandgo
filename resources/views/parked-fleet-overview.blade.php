<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Parked Fleet Overview - Park and Go</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Parked Fleet Overview</h2>
                    <p class="card-text">List of all vehicles currently parked.</p><br>
                </div>
                <div class="pull-right mb-2">
                    <a class="btn btn-success" onClick="addVehicle()" href="javascript:void(0)">Add Vehicle</a>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-bordered" id="parked-fleet-datatable">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Ticket Number</th>
                        <th>Plate Number</th>
                        <th>Vehicle Make</th>
                        <th>Driver Name</th>
                        <th>Driver Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <div class="text-end mt-3">
                <a href="http://localhost:8000/dashboard" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap Modal for Viewing Vehicle Details -->
    <!-- Add Vehicle Modal -->
    <div class="modal fade" id="add-vehicle-modal" tabindex="-1" aria-labelledby="addVehicleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVehicleModalLabel">Entry Logbook</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-vehicle-form">
                        <div class="mb-3">
                            <label for="TicketNumber" class="form-label">Ticket Number</label>
                            <input type="text" class="form-control" id="TicketNumber" name="TicketNumber" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="PlateNumber" class="form-label">Plate Number</label>
                            <input type="text" class="form-control" id="PlateNumber" name="PlateNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="VehicleMake" class="form-label">Vehicle Make</label>
                            <input type="text" class="form-control" id="VehicleMake" name="VehicleMake" required>
                        </div>
                        <div class="mb-3">
                            <label for="DriverName" class="form-label">Driver Name</label>
                            <input type="text" class="form-control" id="DriverName" name="DriverName" required>
                        </div>
                        <div class="mb-3">
                            <label for="DriverNumber" class="form-label">Driver Number</label>
                            <input type="text" class="form-control" id="DriverNumber" name="DriverNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="Remark" class="form-label">Remark</label>
                            <input type="text" class="form-control" id="Remark" name="Remark" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-vehicle-btn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Vehicle Modal -->
    <div class="modal fade" id="view-vehicle-modal" tabindex="-1" aria-labelledby="viewVehicleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewVehicleModalLabel">Vehicle Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Ticket Number:</strong> <span id="TicketNumber"></span></p>
                    <p><strong>Plate Number:</strong> <span id="PlateNumber"></span></p>
                    <p><strong>Vehicle Make:</strong> <span id="VehicleMake"></span></p>
                    <p><strong>Driver Name:</strong> <span id="DriverName"></span></p>
                    <p><strong>Driver Number:</strong> <span id="DriverNumber"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Vehicle Modal -->
    <!-- Checkout Vehicle Modal -->
    <div class="modal fade" id="checkout-vehicle-modal" tabindex="-1" aria-labelledby="checkoutVehicleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutVehicleModalLabel">Managed Parked Vehicles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Plate Number:</strong> <span id="checkout-PlateNumber"></span></p>
                    <p><strong>Vehicle Make:</strong> <span id="checkout-VehicleMake"></span></p>
                    <p><strong>Ticket Number:</strong> <span id="checkout-TicketNumber"></span></p>
                    <p><strong>Driver Name:</strong> <span id="checkout-DriverName"></span></p>
                    <p><strong>Driver Number:</strong> <span id="checkout-DriverNumber"></span></p>
                    <p><strong>Vehicle Entry Time:</strong> <span id="checkout-VehicleEntryTime"></span></p>
                    <p><strong>Parking Duration:</strong> <span id="checkout-ParkingDuration"></span></p>
                    <p><strong>Parking Fee:</strong> <span id="calculated-ParkingFee"></span></p>
                    <!-- Add elements for Vehicle Duration and Parking Fee here -->
                    <div class="mb-3">
                        <label for="amountPaid" class="form-label">Amount to Pay</label>
                        <input type="number" class="form-control" id="amountPaid" name="ParkingFee" required>
                        <!-- Display the fee calculation details here -->
                    </div>
                    <div class="mb-3">
                        <label for="checkout-Status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="checkout-Status" name="Status"
                            value="Outgoing Vehicle" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="checkout-Remark" class="form-label">Remark</label>
                        <input type="text" class="form-control" id="checkout-Remark" name="Remark" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                    <button type="button" class="btn btn-primary" id="proceed-checkout-button">Proceed
                        Checkout</button>
                </div>
            </div>
        </div>
    </div>


    <!-- end bootstrap modal -->

    <script type="text/javascript">
        // Define addVehicle function in the global scope
        function addVehicle() {
            $('#add-vehicle-modal').modal('show');
        }

        function calculateParkingFee(durationInHours) {
            const baseFee = 80;
            const additionalFee = 30;
            const baseHours = 3;

            if (durationInHours <= baseHours) {
                return baseFee;
            } else {
                return baseFee + ((durationInHours - baseHours) * additionalFee);
            }
        }

        let currentVehicleId; // Declare a variable to store the current vehicle ID

        function proceedCheckout(vehicleId) {
            currentVehicleId = vehicleId; // Store the vehicle ID when the checkout is initiated

            // Fetch vehicle details
            $.ajax({
                type: 'GET',
                url: "{{ url('vehicle-details') }}/" + vehicleId,
                success: function(data) {
                    // Calculate parking duration and fee
                    const entryTime = new Date(data.EntryTime);
                    const currentTime = new Date();
                    const durationInMilliseconds = currentTime - entryTime;
                    const durationInHours = durationInMilliseconds / (1000 * 60 * 60);
                    const parkingFee = calculateParkingFee(durationInHours);

                    // Populate the modal with the fetched vehicle details
                    $('#checkout-PlateNumber').text(data.PlateNumber);
                    $('#checkout-VehicleMake').text(data.VehicleMake);
                    $('#checkout-TicketNumber').text(data.TicketNumber);
                    $('#checkout-DriverName').text(data.DriverName);
                    $('#checkout-DriverNumber').text(data.DriverNumber);
                    $('#checkout-VehicleEntryTime').text(entryTime.toLocaleString());
                    $('#checkout-ParkingDuration').text(
                        `${Math.floor(durationInHours)}h ${Math.round((durationInHours % 1) * 60)}m`);
                    $('#calculated-ParkingFee').text(`₱${parkingFee.toFixed(2)}`);

                    // Show the modal
                    $('#checkout-vehicle-modal').modal('show');
                },
                error: function(error) {
                    console.log(error);
                    alert('Failed to fetch vehicle details!');
                }
            });
        }

        // Define viewDetails function to fetch and display vehicle details
        function viewDetails(vehicleId) {
            $.ajax({
                type: 'GET',
                url: "{{ url('vehicle-details') }}/" + vehicleId,
                success: function(data) {
                    console.log(data);
                    // Populate the modal with the fetched vehicle details
                    $('#view-vehicle-modal').find('#TicketNumber').text(data.TicketNumber);
                    $('#view-vehicle-modal').find('#PlateNumber').text(data.PlateNumber);
                    $('#view-vehicle-modal').find('#VehicleMake').text(data.VehicleMake);
                    $('#view-vehicle-modal').find('#DriverName').text(data.DriverName);
                    $('#view-vehicle-modal').find('#DriverNumber').text(data.DriverNumber);
                    // Show the modal
                    $('#view-vehicle-modal').modal('show');
                },
                error: function(error) {
                    console.log(error);
                    alert('Failed to fetch vehicle details!');
                }
            });
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var table = $('#parked-fleet-datatable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: false, // Disable state saving
                ajax: "{{ url('list-vehicles') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'TicketNumber',
                        name: 'TicketNumber'
                    },
                    {
                        data: 'PlateNumber',
                        name: 'PlateNumber'
                    },
                    {
                        data: 'VehicleMake',
                        name: 'VehicleMake'
                    },
                    {
                        data: 'DriverName',
                        name: 'DriverName'
                    },
                    {
                        data: 'DriverNumber',
                        name: 'DriverNumber'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        render: function(data, type, row) {
                            return '<a href="javascript:void(0)" onclick="viewDetails(' + data +
                                ')" class="edit btn btn-primary btn-sm">View</a>' +
                                ' <a href="javascript:void(0)" onclick="proceedCheckout(' + data +
                                ')" class="btn btn-success btn-sm">Proceed Payment</a>';
                        }
                    }
                ],
                order: [
                    [0, 'desc']
                ] // Set initial order to the first column (id) in descending order
            });

            // Attach an event listener to reset the form when the modal is shown
            $('#add-vehicle-modal').on('show.bs.modal', function(e) {
                $('#add-vehicle-form').trigger("reset"); // Reset the form
            });

            // Event listener for form submission
            $('#add-vehicle-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: "{{ url('add-vehicle') }}",
                    data: formData,
                    success: (data) => {
                        $("#add-vehicle-modal").modal('hide');
                        table.draw(false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error:', jqXHR.responseText);
                    }
                });
            });

            // Event listener for the "Add Vehicle" button inside the modal
            $('#save-vehicle-btn').click(function(e) {
                e.preventDefault();
                var formData = $('#add-vehicle-form').serialize();
                $(this).prop('disabled', true); // Disable the submit button

                $.ajax({
                    type: 'POST',
                    url: "{{ url('add-vehicle') }}",
                    data: formData,
                    success: function(data) {
                        console.log(data); // Log the returned data to the console
                        $('#add-vehicle-modal').modal('hide');
                        table.draw(false);
                        alert('Vehicle added successfully!');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        alert('Failed to add vehicle!');
                    },
                    complete: function() {
                        $('#save-vehicle-btn').prop('disabled',
                            false); // Enable the submit button
                    }
                });
            });

            // Handle Proceed Checkout button click
            $('#proceed-checkout-button').click(function() {
                // Check if currentVehicleId is set
                if (typeof currentVehicleId === 'undefined') {
                    console.error('Vehicle ID is undefined!');
                    alert('Vehicle ID is undefined!');
                    return;
                }

                const amountPaid = parseFloat($('#amountPaid').val());
                const remark = $('#checkout-Remark').val();

                // Calculate the parking fee again
                const entryTimeElement = $('#checkout-VehicleEntryTime');
                const entryTime = new Date(entryTimeElement.text());

                // Convert entryTime to Asia/Manila timezone
                const options = {
                    timeZone: 'Asia/Manila'
                };
                const entryTimePhilippine = new Date(entryTime.toLocaleString('en-US', options));

                // Get current time and convert it to Asia/Manila timezone
                const currentTime = new Date();
                const currentTimePhilippine = new Date(currentTime.toLocaleString('en-US', options));

                const durationInMilliseconds = currentTimePhilippine - entryTimePhilippine;
                const durationInHours = durationInMilliseconds / (1000 * 60 * 60);
                const parkingFee = calculateParkingFee(durationInHours);

                // Log the data being sent to the console for debugging
                console.log('Sending checkout request for vehicleId:', currentVehicleId);
                console.log('Amount Paid:', amountPaid);
                console.log('Calculated Parking Fee:', parkingFee.toFixed(
                2)); // Format the parking fee to two decimal places
                console.log('Remark:', remark);

                $.ajax({
                    type: 'POST',
                    url: "{{ url('checkout-vehicle') }}/" + currentVehicleId,
                    data: {
                        amountPaid: amountPaid,
                        calculatedParkingFee: parkingFee, // Send the calculated parking fee
                        status: 'Outgoing Vehicle',
                        remark: remark,
                    },
                    success: function(data) {
                        console.log('Checkout successful:', data);
                        $('#checkout-vehicle-modal').modal('hide');
                        table.draw(false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Failed to checkout vehicle!', jqXHR, textStatus,
                            errorThrown);
                        alert('Failed to checkout vehicle!');
                    }
                });
            });




        });
    </script>

</body>

</html>
