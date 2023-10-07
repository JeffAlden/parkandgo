<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Income Report</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Income Report</h2>
        <h5>Generate income reports for vehicles based on a specific time frame.</h5>
        <button type="submit" class="btn btn-primary">Generate Report</button>
        <form action="{{ route('income-report') }}" method="GET" class="mb-4">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                    value="{{ $startDate }}">
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="text-end mt-3">
                <a href="http://localhost:8000/dashboard" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </form>
        <p>Total Income: ₱{{ number_format($totalIncome, 2) }}</p>
        <a href="{{ route('income-report', ['start_date' => $startDate, 'end_date' => $endDate, 'download' => 'csv']) }}"
            class="btn btn-secondary" target="_blank">Download as CSV</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
