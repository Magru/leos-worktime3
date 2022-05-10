<?php
/*
* Workday - A time clock application for employees
* URL: https://codecanyon.net/item/workday-a-time-clock-application-for-employees/23076255
* Support: official.codefactor@gmail.com
* Version: 5.0
* Author: Brian Luna
* Copyright 2022 Codefactor
*/

namespace App\Http\Controllers\admin;

use DB;
use Carbon\Carbon;
use App\Classes\Table;
use App\Classes\Permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        if (permission::permitted('attendance') == 'fail') {
            return redirect()->route('denied');
        }

        $show_nav = false;
        if ($request->emp_id) {
            $attendance = table::attendanceByPerson($request->emp_id)->orderBy('date', 'desc')->get();
            $emp_id = $request->emp_id;
            $show_nav = true;
        } else {
            $attendance = table::attendance()->orderBy('date', 'desc')->take(700)->get();
            $emp_id = null;
        }

        if ($request->start && $request->end && $request->emp_id) {
            $attendance = table::attendanceByPersonAndDate($request->emp_id, $request->start, $request->end)
                ->orderBy('date', 'desc')->get();
        }
        $time_format = table::settings()->value("time_format");
        $employee = table::people()->get();

        $hours_to_calculate_sum = $attendance->where('realhours', '>', 0.5)->sum('realhours');
        $hours_to_calculate_count = $attendance->where('realhours', '>', 0.5)->count();


        $hours_sum_net = $hours_to_calculate_sum - ($hours_to_calculate_count * 0.5);


        return view('admin.attendance', [
            'attendance' => $attendance,
            'time_format' => $time_format,
            'hours_sum' => $attendance->sum('realhours'),
            'hours_sum_net' => $hours_sum_net,
            'employee' => $employee,
            'emp_id' => $emp_id,
            'show_nav' => $show_nav
        ]);
    }

    public function add()
    {
        if (permission::permitted('attendance') == 'fail') {
            return redirect()->route('denied');
        }

        $employee = table::people()->get();

        $time_format = table::settings()->value("time_format");

        return view('admin.attendance-add', ['employee' => $employee, 'time_format' => $time_format]);
    }

    public function entry(Request $request)
    {
        if (permission::permitted('attendance') == 'fail') {
            return redirect()->route('denied');
        }

        if ($request->reference == NULL) {
            return redirect('admin/attendance/manual-entry')->with('error', trans("Invalid request"));
        }

        $v = $request->validate([
            'date' => 'required|max:15',
            'clockin' => 'required|max:15',
            'clockout' => 'nullable|max:15',
            'reference' => 'required|max:250',
        ]);

        $reference = $request->reference;

        $date = date('Y-m-d', strtotime($request->date));

        $clockin = date('h:i:s A', strtotime($request->clockin));

        $clockout = ($request->clockout != null) ? date('h:i:s A', strtotime($request->clockout)) : null;

        $ip = $request->ip();

        $time_format = table::settings()->value('time_format');

        # ip resriction
        $iprestriction = table::settings()->value('iprestriction');

        if ($iprestriction != NULL) {
            $ips = explode(",", $iprestriction);

            if (in_array($ip, $ips) == false) {
                return redirect('admin/attendance/manual-entry')->with('error', trans("Your device it not registered"));
            }
        }

        $emp_id = table::companydata()->where('id', $reference)->value('reference');

        $emp_idno = table::companydata()->where('id', $reference)->value('idno');

        if ($emp_id == null || $emp_idno == null) {
            return redirect('admin/attendance/manual-entry')->with('error', trans("There is no employee with this ID"));
        }

        # employee
        $person = table::people()->where('id', $emp_id)->first();
        $lastname = $person->lastname;
        $firstname = $person->firstname;

        $employee = mb_strtoupper($lastname . ', ' . $firstname);

        if ($clockout == null) {
            $has = table::attendance()->where([['idno', $emp_idno], ['date', $date]])->exists();

            if ($has == 1) {
                $hti = table::attendance()->where([['idno', $emp_idno], ['date', $date]])->value('timein');
                $hti = date('h:i A', strtotime($hti));
                $hti_24 = ($time_format == 12) ? $hti : date("H:i", strtotime($hti));

                return redirect('admin/attendance/manual-entry')->with('error', trans("The employee already clock in today at") . " " . $hti_24);

            } else {

                $sched_in_time = table::schedules()->where([['idno', $emp_idno], ['archive', 0]])->value('intime');

                if ($sched_in_time == NULL) {
                    $status_in = null;

                } else {

                    $sched_clock_in_time_24h = date("H.i", strtotime($sched_in_time));

                    $time_in_24h = date("H.i", strtotime($clockin));

                    if ($time_in_24h <= $sched_clock_in_time_24h) {
                        $status_in = trans("In Time");
                    } else {
                        $status_in = trans("Late In");
                    }
                }

                table::attendance()->insert([
                    [
                        'idno' => $emp_idno,
                        'reference' => $emp_id,
                        'date' => $date,
                        'employee' => $employee,
                        'timein' => $date . " " . $clockin,
                        'status_timein' => $status_in,
                    ],
                ]);

                return redirect('admin/attendance/manual-entry')->with('success', trans("Registration was successful"));
            }
        }

        if ($clockin != null && $clockout != null) {
            $has = table::attendance()->where([['idno', $emp_idno], ['date', $date]])->exists();

            if ($has == 1) {
                $hti = table::attendance()->where([['idno', $emp_idno], ['date', $date]])->value('timein');
                $hti = date('h:i A', strtotime($hti));
                $hti_24 = ($time_format == 12) ? $hti : date("H:i", strtotime($hti));

                return redirect('admin/attendance/manual-entry')->with('error', trans("The employee already clock in today at") . " " . $hti_24);

            } else {

                $sched_in_time = table::schedules()->where([['idno', $emp_idno], ['archive', 0]])->value('intime');

                $sched_out_time = table::schedules()->where([['idno', $emp_idno], ['archive', 0]])->value('outime');

                if ($sched_in_time == NULL) {
                    $status_in = null;

                } else {

                    $sched_clock_in_time_24h = date("H.i", strtotime($sched_in_time));

                    $time_in_24h = date("H.i", strtotime($clockin));

                    if ($time_in_24h <= $sched_clock_in_time_24h) {
                        $status_in = trans("In Time");
                    } else {
                        $status_in = trans("Late In");
                    }
                }

                if ($sched_out_time == NULL) {
                    $status_out = null;

                } else {

                    $sched_clock_out_time_24h = date("H.i", strtotime($sched_out_time));

                    $time_out_24h = date("H.i", strtotime($clockout));

                    if ($time_out_24h >= $sched_clock_out_time_24h) {
                        $status_out = trans("On Time");
                    } else {
                        $status_out = trans("Early Out");
                    }
                }

                $time1 = Carbon::createFromFormat("Y-m-d h:i:s A", $date . " " . $clockin);
                $time2 = Carbon::createFromFormat("Y-m-d h:i:s A", $date . " " . $clockout);
                $th = $time1->diffInHours($time2);
                $tm = floor(($time1->diffInMinutes($time2) - (60 * $th)));
                $totalhour = $th . "." . $tm;

                table::attendance()->insert([
                    [
                        'idno' => $emp_idno,
                        'reference' => $emp_id,
                        'date' => $date,
                        'employee' => $employee,
                        'timein' => $date . " " . $clockin,
                        'status_timein' => $status_in,
                        'timeout' => $date . " " . $clockout,
                        'totalhours' => $totalhour,
                        'status_timeout' => $status_out,
                    ],
                ]);

                return redirect('admin/attendance/manual-entry')->with('success', trans("Registration was successful"));
            }
        }
    }

    public function delete($id, Request $request)
    {
        if (permission::permitted('attendance-delete') == 'fail') {
            return redirect()->route('denied');
        }

        $id = $request->id;

        table::attendance()->where('id', $id)->delete();

        return redirect('admin/attendance')->with('success', trans("Attendance is successfully deleted"));
    }

    public function filter(Request $request)
    {
        if (permission::permitted('attendance') == 'fail') {
            return redirect()->route('denied');
        }

        $v = $request->validate([
            'emp_id' => 'nullable|max:255',
            'start' => 'required|max:255',
            'end' => 'required|max:255'
        ]);

        $emp_id = $request->emp_id;

        $start = $request->start;

        $end = $request->end;

        $time_format = table::settings()->value("time_format");

        $employee = table::people()->get();

        if ($emp_id != null) {
            $attendance = table::attendance()->where('reference', $emp_id)->whereBetween('date', ["$start", "$end"])->get();
        } else {
            $attendance = table::attendance()->whereBetween('date', ["$start", "$end"])->get();
        }

        return view('admin.attendance', [
            'attendance' => $attendance,
            'employee' => $employee,
            'time_format' => $time_format
        ]);
    }
}

