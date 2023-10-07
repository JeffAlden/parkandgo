<?php

namespace App\Http\Controllers;

use App\Models\VehicleInfo;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;

class VehicleInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }

    public function index()
    {
        $this->authorize('admin-actions');
        return view('parked-fleet-overview');
    }

    public function store(Request $request)
    {
        $this->authorize('admin-actions');
        
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
        $this->authorize('admin-actions');
        
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
        $this->authorize('admin-actions');
        
        $vehicle = VehicleInfo::find($id);
        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
        return response()->json($vehicle);
    }

    public function destroy($id)
    {
        $this->authorize('admin-actions');
        
        $vehicle = VehicleInfo::find($id);
        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
        $vehicle->delete();
        return response()->json(['success' => 'Vehicle deleted successfully']);
    }

    public function checkoutVehicle(Request $request, $vehicleId)
    {
        $this->authorize('admin-actions');
        
        $vehicle = VehicleInfo::find($vehicleId);
        if (!$vehicle) {
            return response()->json(['message' => 'Vehicle not found!'], 404);
        }

        // Retrieve the calculated parking fee from the request
        $calculatedParkingFee = $request->input('calculatedParkingFee');

        // Format the parking fee to two decimal places
        $formattedParkingFee = number_format($calculatedParkingFee, 2, '.', '');

        // Update the vehicle status, exit time, remark, and parking fee
        $vehicle->Status = 'Checked Out';
        $vehicle->ExitTime = now();
        $vehicle->Remark = $request->input('remark');
        $vehicle->ParkingFee = $formattedParkingFee; // Use the formatted parking fee

        // Save the updated vehicle information
        $vehicle->save();

        return response()->json(['message' => 'Checkout successful!']);
    }

    public function getCheckedOutVehicles()
    {
        $this->authorize('admin-actions');
        
        $checkedOutVehicles = VehicleInfo::where('Status', 'Checked Out')->get();
        return view('checked_out_vehicles', ['vehicles' => $checkedOutVehicles]);
    }

    public function showReceipt($id)
    {
        $this->authorize('admin-actions');
        
        $vehicle = VehicleInfo::find($id);
        if (!$vehicle) {
            return abort(404);
        }
        return view('receipt', compact('vehicle'));
    }
}
