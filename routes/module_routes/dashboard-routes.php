<?php

use App\Http\Controllers\AttendanceController;

Route::prefix('attendance')->as('attendance.')->group(function () {
    Route::post('/store' ,[App\Http\Controllers\AttendanceController::class, 'store'])->name('store');
    Route::get('/get-attendance-list' ,[App\Http\Controllers\AttendanceController::class, 'attendanceRecord'])->name('record');
});
