<?php

namespace App\Http\Controllers;

use App\Models\VehicleInfo;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;

class VehicleInfoController extends Controller
{
    public function index()
    {
        return view('parked-fleet-overview');
    }

    public function store(Request $request)
    {
        $request->validate([
            'PlateNumber' => 'required|string|max:120',
            'VehicleMake' => 'required|string|max:120',
            'DriverName' => 'required|string|max:120',
            'DriverNumber' => 'required|numeric',
            'Remark' => 'required|string',
        ]);

        $TicketNumber = 'PNG-' . time();
        $vehicle = VehicleInfo::create(array_merge($request->all(), ['TicketNumber' => $TicketNumber, 'EntryTime' => now()]));
        return response()->json(['success' => 'Vehicle added successfully', 'TicketNumber' => $TicketNumber]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = VehicleInfo::where('Status', 'Parked')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" onclick="viewDetails(' . $row->id . ')" class="edit btn btn-primary btn-sm">View</a>';
                    $btn .= ' <a href="javascript:void(0)" onclick="deleteVehicle(' . $row->id . ')" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function show($id)
    {
        $vehicle = VehicleInfo::find($id);

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        return response()->json($vehicle);
    }

    public function destroy($id)
    {
        $vehicle = VehicleInfo::find($id);

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        $vehicle->delete();

        return response()->json(['success' => 'Vehicle deleted successfully']);
    }

    public function checkoutVehicle(Request $request, $vehicleId)
    {
        $vehicle = VehicleInfo::find($vehicleId);
        if (!$vehicle) {
            return response()->json(['message' => 'Vehicle not found!'], 404);
        }

        // Update the vehicle status and exit time
        $vehicle->Status = 'Checked Out';
        $vehicle->ExitTime = now();

        // Calculate the parking duration in hours
        $entryTime = new Carbon($vehicle->EntryTime);
        $exitTime = new Carbon($vehicle->ExitTime);
        $durationInHours = $entryTime->diffInHours($exitTime);

        // Define your base fare and additional hourly rate
        $baseFare = 80; // Base fare for the first 3 hours
        $additionalHourlyRate = 30; // Rate for every succeeding hour

        // Calculate the parking fee
        if ($durationInHours <= 3) {
            $parkingFee = $baseFare;
        } else {
            $additionalHours = $durationInHours - 3;
            $parkingFee = $baseFare + ($additionalHours * $additionalHourlyRate);
        }

        // Update the Remark and ParkingFee fields
        $vehicle->Remark = $request->input('remark');
        $vehicle->ParkingFee = $parkingFee;

        // Save the updated vehicle information
        $vehicle->save();

        return response()->json(['message' => 'Checkout successful!']);
    }

    public function departureRecord()
    {
        $vehicles = VehicleInfo::where('Status', 'Checked Out')->get();

        return DataTables::of($vehicles)
            ->addColumn('action', function ($vehicle) {
                // return '<a href="javascript:void(0)" onclick="viewDetails(' . $vehicle->id . ')" class="btn btn-primary btn-sm">View Details</a>' .
                //     '<a href="javascript:void(0)" onclick="generateReceipt(' . $vehicle->id . ')" class="btn btn-success btn-sm ml-2">Generate Receipt</a>' .
                //     '<a href="javascript:void(0)" onclick="emailDetails(' . $vehicle->id . ')" class="btn btn-warning btn-sm ml-2">Email</a>' .
                //     '<a href="javascript:void(0)" onclick="printDetails(' . $vehicle->id . ')" class="btn btn-info btn-sm ml-2">Print</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
