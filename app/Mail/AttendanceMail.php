<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $status;

    public function __construct($student, $status)
    {
        $this->student = $student;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject('Attendance Notification')
            ->view('emails.attendance');
    }
}