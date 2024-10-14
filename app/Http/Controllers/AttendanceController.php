<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'userId' => 'required|integer',
            'clockStatus' => 'required|string',
        ]);

        $attendance = new Attendance();
        
        $attendance->user_id = $validatedData['userId']; 
        $attendance->clock_status = $validatedData['clockStatus']; 
        $attendance->time = Carbon::now('Asia/Manila'); // Assuming 'time' is the field in your model

        $attendance->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(attendance $attendance)
    {
        //
    }
    
    public function attendanceRecord(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
    
        // Fetch all attendance records for the authenticated user
        $attendanceRecords = DB::table('attendances')
        ->join('users', 'attendances.user_id', '=', 'users.id')  
        ->select('users.name', 'attendances.clock_status', 'attendances.time') 
        ->whereDate('attendances.time', Carbon::today()) 
        ->orderBy('attendances.id', 'desc')  
        ->get();

        return response()->json($attendanceRecords);
    }
}
