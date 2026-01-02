<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Employee\Models\Employee;
use Modules\Work\Models\Work;

class WorkCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $work;
    public $assigneesNames;

    public function __construct(Work $work)
    {
        $this->work = $work;

        $toUsers = json_decode($work->to_user ?? '[]', true);
        $this->assigneesNames = Employee::whereIn('id', $toUsers)
                                ->pluck('full_name')
                                ->implode(', ');
    }

    public function build()
    {
        return $this->subject('Công việc ' . $this->work->work_name . ' đã được khởi tạo')
            ->view('emails.work_created')
            ->with([
                'assigneesNames' => $this->assigneesNames,
            ]);
    }
}

