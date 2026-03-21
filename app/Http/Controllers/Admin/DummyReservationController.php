<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DummyReservationController extends Controller
{
    public function index()
    {
        $amenities = [
            [
                'id' => 1,
                'name' => 'Basketball Court',
                'color' => 'blue', // Tailwind class suffix
                'reservations' => [
                    [
                        'resident_name' => 'John Doe',
                        'time_slot' => '08:00 AM - 10:00 AM',
                        'status' => 'approved',
                    ],
                    [
                        'resident_name' => 'Jane Smith',
                        'time_slot' => '10:00 AM - 12:00 PM',
                        'status' => 'pending',
                    ],
                    [
                        'resident_name' => 'Mike Johnson',
                        'time_slot' => '02:00 PM - 04:00 PM',
                        'status' => 'rejected',
                    ],
                ]
            ],
            [
                'id' => 2,
                'name' => 'Swimming Pool',
                'color' => 'cyan',
                'reservations' => [
                    [
                        'resident_name' => 'Sarah Connor',
                        'time_slot' => '09:00 AM - 11:00 AM',
                        'status' => 'approved',
                    ],
                    [
                        'resident_name' => 'Kyle Reese',
                        'time_slot' => '01:00 PM - 03:00 PM',
                        'status' => 'approved',
                    ],
                ]
            ],
            [
                'id' => 3,
                'name' => 'Gym',
                'color' => 'orange',
                'reservations' => [
                    [
                        'resident_name' => 'Tony Stark',
                        'time_slot' => '06:00 AM - 08:00 AM',
                        'status' => 'pending',
                    ],
                    [
                        'resident_name' => 'Steve Rogers',
                        'time_slot' => '05:00 PM - 07:00 PM',
                        'status' => 'approved',
                    ],
                    [
                        'resident_name' => 'Bruce Banner',
                        'time_slot' => '08:00 PM - 10:00 PM',
                        'status' => 'pending',
                    ],
                ]
            ],
        ];

        return view('admin.dummy-reservation', compact('amenities'));
    }
}
