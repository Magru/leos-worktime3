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
use Illuminate\Support\Facades\Auth;
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

        $filter = false;
        $three_month_average = null;
        $three_month_125_average = null;
        $three_month_150_average = null;

        if($request->type === 'avg'){
            $start_point = \Carbon\Carbon::now()->addMonth(-3);
            $end_point = new Carbon('last day of last month');

            $query_start = $start_point->firstOfMonth()->toDateString();
            $query_end = $end_point->toDateString();
            $filter = true;
        }
        elseif($request->start && $request->end && $request->emp_id){
            $query_start = $request->start;
            $query_end = $request->end;
            $filter = true;
        }

        if($filter){
            $attendance = table::attendanceByPersonAndDate($request->emp_id, $query_start, $query_end)
                ->orderBy('date', 'desc')->get();
        }

        if($request->type === 'avg'){
            $three_month_average = $attendance->sum('realhours') / count($attendance);
            $three_month_125_average = $attendance->sum('h_125') / count($attendance);
            $three_month_150_average = $attendance->sum('h_150') / count($attendance);
        }

        $time_format = table::settings()->value("time_format");
        $employee = table::people()->get();

        $current_m_1 = \Carbon\Carbon::now()->addMonth(-1)->format('m');
        $current_m_2 = \Carbon\Carbon::now()->addMonth(-2)->format('m');
        $current_m_3 = \Carbon\Carbon::now()->addMonth(-3)->format('m');
        $three_month_string = config('app.hebrew_month')[$current_m_1] . ',' .
                              config('app.hebrew_month')[$current_m_2] . ',' .
                              config('app.hebrew_month')[$current_m_3] ;


        return view('admin.attendance', [
            'attendance' => $attendance,
            'time_format' => $time_format,
            'hours_sum' => $attendance->sum('realhours'),
            'hours_sum_net' => $attendance->sum('real_hours_netto'),
            'employee' => $employee,
            'emp_id' => $emp_id,
            'show_nav' => $show_nav,
            'h_125_sum' => $attendance->sum('h_125'),
            'h_150_sum' => $attendance->sum('h_150'),
            'three_month_string' => $three_month_string,
            'three_month_average' => $three_month_average,
            'three_month_125_average' => $three_month_125_average,
            'three_month_150_average' => $three_month_150_average
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
                $realhours = $time1->floatDiffInRealHours($time2);

                $h_125 = 0;
                $h_150 = 0;


                if($person->rest_calc){
                    $realhours_netto = $realhours > 0.5 ? $realhours - 0.5 : 0;
                }else{
                    $realhours_netto = $realhours;
                }

                if($realhours_netto >= 10.4){
                    $h_125 = 2;
                    $h_150 = $realhours_netto - 10.4;
                }elseif($realhours_netto < 10.4 && $realhours_netto > 8.4){
                    $h_125 = $realhours_netto - 8.4;
                }



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
                        'realhours' => $realhours,
                        'real_hours_netto' => $realhours_netto,
                        'h_125' => $h_125,
                        'h_150' => $h_150,
                        'edited_by' => Auth::user()->name
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
        ]);

        if($request->type){
            $start_point = \Carbon\Carbon::now()->addMonth(-3);
            $end_point = new Carbon('last day of last month');

            $start = $start_point->toDateString();
            $end = $end_point->toDateString();

        }else{
            $start = $request->start;
            $end = $request->end;
        }
        $emp_id = $request->emp_id;

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
            'time_format' => $time_format,
            'show_nav' => true,
            'emp_id' => $emp_id,
        ]);
    }

    public function edit($id)
    {
        if (permission::permitted('attendance') == 'fail') {
            return redirect()->route('denied');
        }

        $entry = table::attendance()->where('id', $id)->first();

        $time_format = table::settings()->value("time_format");

        return view('admin.attendance-edit', ['entry' => $entry, 'time_format' => $time_format]);
    }

    public function update(Request $request)
    {
        if (permission::permitted('attendance') == 'fail') {
            return redirect()->route('denied');
        }

        $date = date('Y-m-d', strtotime($request->date));
        $entry = table::attendance()->where('id', $request->entry_to_update)->first();
        $sched_in_time = table::schedules()->where([['idno', $entry->idno], ['archive', 0]])->value('intime');
        $sched_out_time = table::schedules()->where([['idno', $entry->idno], ['archive', 0]])->value('outime');


        $clockin = date('h:i:s A', strtotime($request->clockin));
        $clockout = ($request->clockout != null) ? date('h:i:s A', strtotime($request->clockout)) : null;

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
        $realhours = $time1->floatDiffInRealHours($time2);

        if($entry->is_rest_calculated){
            $realhours_netto = $realhours > 0.5 ? $realhours - 0.5 : 0;
        }else{
            $realhours_netto = $realhours;
        }

        $h_125 = 0;
        $h_150 = 0;

        if($realhours_netto >= 10.4){
            $h_125 = 2;
            $h_150 = $realhours_netto - 10.4;
        }elseif($realhours_netto < 10.4 && $realhours_netto > 8.4){
            $h_125 = $realhours_netto - 8.4;
        }
        $totalhour = $th . "." . $tm;


        table::attendance()->where('id', $request->entry_to_update)->update(
            [
                'idno' => $entry->idno,
                'reference' => $entry->reference,
                'date' => $date,
                'employee' => $entry->employee,
                'timein' => $date . " " . $clockin,
                'status_timein' => $status_in,
                'timeout' => $date . " " . $clockout,
                'totalhours' => $totalhour,
                'realhours' => $realhours,
                'status_timeout' => $status_out,
                'real_hours_netto' => $realhours_netto,
                'h_125' => $h_125,
                'h_150' => $h_150,
                'is_request_done' => 1,
                'edited_by' => Auth::user()->name
            ]
        );

        return redirect()->route('admin-attendance');


    }
}

