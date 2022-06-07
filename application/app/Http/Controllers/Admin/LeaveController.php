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
use App\Classes\Table;
use App\Classes\Permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function index() 
    {
        if (permission::permitted('leaves')=='fail'){ return redirect()->route('denied'); }

        $leaves = table::leaves()->get();

        $employee = table::people()->get();

        return view('admin.leave', [
            'leaves' => $leaves,
            'employee' => $employee,
        ]);
    }

    public function add()
    {
        if (permission::permitted('leave-edit')=='fail'){ return redirect()->route('denied'); }

        $employee = table::people()->get();
        $leave_type = table::leavetypes()->get();

        return view('admin.leave-add', [
            'employee' => $employee,
            'leave_type' => $leave_type
        ]);
    }

    public function store(Request $request){
        $v = $request->validate([
            'employee' => 'required',
            'leavefrom' => 'required|date|max:15',
            'leaveto' => 'required|date|max:15',
            'returndate' => 'required|date|max:15',
        ]);

        $typeid = $request->typeid;

        $type = mb_strtoupper($request->type);

        $reason = mb_strtoupper($request->reason);

        $leavefrom = date("Y-m-d", strtotime($request->leavefrom));

        $leaveto = date("Y-m-d", strtotime($request->leaveto));

        $returndate = date("Y-m-d", strtotime($request->returndate));

        $employee_obj = table::people()->where('idno', $request->employee)->first();

        $id = $employee_obj->id;

        $idno = $request->employee;


        table::leaves()->insert([
            'reference' => $id,
            'idno' => $idno,
            'employee' => $employee_obj->lastname.', '.$employee_obj->firstname,
            'type' => $type,
            'typeid' => $typeid,
            'leavefrom' => $leavefrom,
            'leaveto' => $leaveto,
            'returndate' => $returndate,
            'reason' => $reason,
            'status' => 'Approved',
        ]);

        return redirect('admin/leave')->with('success', 'נשמר בהצלחה');
    }

    public function edit($id, Request $request) 
    {
        if (permission::permitted('leave-edit')=='fail'){ return redirect()->route('denied'); }

        $leave = table::leaves()->where('id', $id)->first();

        $leave->leavefrom = date('M d, Y', strtotime($leave->leavefrom));

        $leave->leaveto = date('M d, Y', strtotime($leave->leaveto));

        $leave->returndate = date('M d, Y', strtotime($leave->returndate));

        $leave_types = table::leavetypes()->get();

        return view('admin.leave-edit', [
            'leave' => $leave,
            'leave_types' => $leave_types
        ]);
    }

    public function update(Request $request)
    {
        if (permission::permitted('leave-edit')=='fail'){ return redirect()->route('denied'); }

        $v = $request->validate([
            'id' => 'required|max:200',
            'status' => 'required|max:100',
            'comment' => 'max:255',
        ]);

        $id = $request->id;

        $status = $request->status;

        $comment = mb_strtoupper($request->comment);

        table::leaves()->where('id', $id)->update([
            'status' => $status,
            'comment' => $comment
        ]);

        return redirect('admin/leave')->with('success', trans("Employee leave has been updated"));
    }

    public function delete($id, Request $request)
    {
        if (permission::permitted('leave-delete')=='fail'){ return redirect()->route('denied'); }

        table::leaves()->where('id', $id)->delete();

        return redirect('admin/leave')->with('success', trans("A leave request is successfully deleted"));
    }

    public function filter(Request $request)
    {
        if (permission::permitted('leaves')=='fail'){ return redirect()->route('denied'); }
        
        $v = $request->validate([
            'emp_id' => 'required|max:255',
        ]);
        
        $emp_id = $request->emp_id;

        $leaves = table::leaves()->where('reference', $emp_id)->get();
    
        $employee = table::people()->get();

        return view('admin.leave', [
            'employee' => $employee,
            'leaves' => $leaves,
        ]);
    }

}
