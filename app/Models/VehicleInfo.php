<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleInfo extends Model
{
    use HasFactory;

    protected $table = 'vehicle_info';

    protected $fillable = [
        'PlateNumber',
        'VehicleMake',
        'DriverName',
        'DriverNumber',
        'Remark',
        'TicketNumber',
        'EntryTime',
        'ExitTime',
        'ParkingFee',
        'Status',
    ];
}
