<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Table;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;


class MessageController extends Controller
{

    public function index(){

        $messages = table::messages()->get();


        return view('admin.message-index', [
            'messages' => $messages,
        ]);
    }

    public function create()
    {

        $departments = table::department()->get();
        $employee = table::people()->get();


        return view('admin.message-add', [
            'departments' => $departments,
            'employee' => $employee
        ]);
    }

    public function store(Request $request)
    {

        $message = $request->message;
        $departments = json_encode($request->departments);
        $expiry = $request->valid_date;
        $employees = json_encode($request->employee);


        table::messages()->insert([
            'is_active' => 1,
            'msg' => $message,
            'expiry' => $expiry,
            'departments' => $departments ?:     null,
            'created_at' => Carbon::now(),
            'employees' => $employees
        ]);

        return redirect('admin/messages')->with('success', 'הודעה נשמרה בהצלחה');

    }

}
