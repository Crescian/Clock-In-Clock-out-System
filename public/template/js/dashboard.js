"use strict";

function dashboard(userRole) {
    return {
        clockInBtn: false,
        clockOutBtn: false,
        attendanceListLoading: true,
        fetchAttendance: [],
        init: function () {
            this.attendanceRecordList();
            console.log(`Initializing dashboard for role: ${userRole}`);
        },
        attendanceRecord: function (userId, clockStatus) {
            $.ajax({
                url: route("attendance.store"),
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    userId: userId,
                    clockStatus: clockStatus,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: (result) => {
                    this.attendanceRecordList();
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                },
            });
        },
        attendanceRecordList: function (date = null) {
            this.attendanceListLoading = true;
            let requestDate = date != null ? date : this.dateToday;
            $.ajax({
                url: route("attendance.record"),
                type: "GET",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                error: function (xhr) {
                    console.error(xhr.responseText); // Log any errors for debugging
                },
            }).then((response) => {
                this.attendanceListLoading = false;
                this.fetchAttendance = response;
                console.log(this.fetchAttendance)
            });
        },
    };
}

