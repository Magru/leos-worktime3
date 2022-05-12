<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceEditRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $attendance;
    private $new_in;
    private $new_out;

    public function __construct($attendance, $new_in, $new_out)
    {
        $this->attendance = $attendance;
        $this->new_in = $new_in;
        $this->new_out = $new_out;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.attendance')
            ->subject('בקשה לעדכון שעות מ-' . $this->attendance->employee)
            ->with([
               'date' => date('d/m/Y', strtotime($this->attendance->date)),
                'in' => date('H:i', strtotime($this->attendance->timein)),
                'out' => date('H:i', strtotime($this->attendance->timeout)),
                'in_new' => $this->new_in,
                'out_new' => $this->new_out,
                'name' => $this->attendance->employee
            ]);
    }
}
