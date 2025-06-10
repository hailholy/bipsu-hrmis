<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $employee;

    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Employee Added',
            'message' => $this->employee->first_name . ' ' . $this->employee->last_name . ' has joined the team.',
            'url' => route('employees')
        ];
    }
}