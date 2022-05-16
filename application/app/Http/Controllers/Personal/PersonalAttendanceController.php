<?php
/*
* Workday - A time clock application for employees
* URL: https://codecanyon.net/item/workday-a-time-clock-application-for-employees/23076255
* Support: official.codefactor@gmail.com
* Version: 5.0
* Author: Brian Luna
* Copyright 2022 Codefactor
*/

namespace App\Http\Controllers\personal;

use App\Mail\AttendanceEditRequest;
use DB;
use App\Classes\Table;
use App\Classes\Permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class PersonalAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $idno = \Auth::user()->idno;
        if($request->emp_id && ($idno !== $request->emp_id)){
            abort(403);
        }

        $attendance = table::attendance()->where('idno', $idno)->limit(700)->get();
        $time_format = table::settings()->value("time_format");

        if ($request->start && $request->end && $request->emp_id) {
            $attendance = table::attendanceByPersonAndDate($request->emp_id, $request->start, $request->end)
                ->orderBy('date', 'desc')->get();
        }
        $hours_to_calculate_sum = $attendance->where('realhours', '>', 0.5)->sum('realhours');
        $hours_to_calculate_count = $attendance->where('realhours', '>', 0.5)->count();

        return view('personal.attendance', [
            'attendance' => $attendance,
            'time_format' => $time_format,
            'hours_sum' => $attendance->sum('realhours'),
            'hours_sum_net' => $attendance->sum('real_hours_netto'),
            'emp_id' => $idno,
            'show_nav' => true,
            'h_125_sum' => $attendance->sum('h_125'),
            'h_150_sum' => $attendance->sum('h_150'),
        ]);
    }

    public function filter(Request $request)
    {
        $idno = \Auth::user()->idno;

        $v = $request->validate([
            'start' => 'required|max:255',
            'end' => 'required|max:255'
        ]);

        $start = $request->start;

        $end = $request->end;

        $time_format = table::settings()->value("time_format");

        $data = table::attendance()->where('idno', $idno)->whereBetween('date', ["$start", "$end"])->get();

        return view('personal.attendance', ['attendance' => $data, 'time_format' => $time_format]);
    }

    public function editRequest(Request $request)
    {
        $attendance = table::attendance()->where('id', $request->attendance)->first();
        $user = \Auth::user();

        return view('personal.edits.attendance-edit', [
            'attendance' => $attendance->id,
            'date' => $attendance->date,
            'in' => $attendance->timein,
            'out' => $attendance->timeout,
            'user' => $user
        ]);
    }

    public function editRequestSend(Request $request)
    {
        $attendance = tap(table::attendance()->where('id', $request->attendance))->update([
            'is_edit_requested' => 1
        ])->first();

        $msg = '';

        Mail::to('maxf@leos.co.il')->send(new AttendanceEditRequest($attendance, $request->clockin, $request->clockout));

        // check for failures
        if (Mail::failures()) {
            $msg = 'שגיא';
        }else{
            $msg = 'נשלח בהצלחה';
        }

        //return Redirect::back()->withErrors(['msg' => 'נשלח בהצלחה']);
        return redirect()->route('personal-dashboard')->with('success', $msg);

    }
}

