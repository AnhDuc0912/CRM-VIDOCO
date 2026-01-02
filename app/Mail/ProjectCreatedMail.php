<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Project\Models\Project;

class ProjectCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function build()
    {
        $assigneesNames = $this->project->assignee_employees->pluck('full_name')->implode(', ');
        $managerName = $this->project->manager?->full_name ?? 'Chưa xác định';

        return $this->subject('Dự án ' . $this->project->project_name . ' đã được khởi tạo')
            ->view('emails.project_created')
            ->with([
                'assigneesNames' => $assigneesNames,
                'managerName' => $managerName,
            ]);
    }
}
