<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleInfo; // Use the VehicleInfo model
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function showReportGenerator()
    {
        $this->authorize('admin-actions');
        return view('report.generator');
    }

    public function getPeriodicReport(Request $request)
    {
        $this->authorize('admin-actions');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Fetch the data based on the date range from the database using VehicleInfo model
        $data = VehicleInfo::whereBetween('ExitTime', [$startDate, $endDate])->get();

        return response()->json(['data' => $data]);
    }

    public function incomeReport(Request $request)
    {
        $this->authorize('admin-actions');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch the data and calculate total income
        $data = VehicleInfo::whereBetween('ExitTime', [$startDate, $endDate])->get();
        $totalIncome = $data->sum('ParkingFee');

        // Check if the request is for CSV download
        if ($request->input('download') === 'csv') {
            $filename = "income_report_{$startDate}_to_{$endDate}.csv";

            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename={$filename}",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $callback = function () use ($data, $totalIncome) {
                $handle = fopen('php://output', 'w');
                // Include all the columns of your vehicle_info table here
                fputcsv($handle, array('ID', 'Ticket Number', 'Vehicle Make', 'Plate Number', 'Driver Name', 'Driver Number', 'Entry Time', 'Exit Time', 'Parking Fee', 'Remark', 'Status', 'Created At', 'Updated At'));

                foreach ($data as $row) {
                    // Include all the fields of your vehicle_info table here
                    fputcsv($handle, array($row->id, $row->TicketNumber, $row->VehicleMake, $row->PlateNumber, $row->DriverName, $row->DriverNumber, $row->EntryTime, $row->ExitTime, $row->ParkingFee, $row->Remark, $row->Status, $row->created_at, $row->updated_at));
                }

                // Add a row for Total Income
                fputcsv($handle, array('', '', '', '', '', '', '', '', 'Total Income:', number_format($totalIncome, 2), '', '', ''));

                fclose($handle);
            };

            return new StreamedResponse($callback, 200, $headers);
        }

        return view('report.income', compact('data', 'totalIncome', 'startDate', 'endDate'));
    }
}
