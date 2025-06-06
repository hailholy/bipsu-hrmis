<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'message', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIconAttribute()
    {
        return [
            'new_employee' => 'fa-user-plus',
            'contract_renewal' => 'fa-file-signature',
            'payroll_processed' => 'fa-money-check-alt',
            'leave_request' => 'fa-calendar-times',
        ][$this->type] ?? 'fa-bell';
    }

    public function getColorAttribute()
    {
        return [
            'new_employee' => 'blue',
            'contract_renewal' => 'green',
            'payroll_processed' => 'purple',
            'leave_request' => 'yellow',
        ][$this->type] ?? 'gray';
    }

    public function getTitleAttribute()
    {
        return [
            'new_employee' => 'New employee added',
            'contract_renewal' => 'Contract renewed',
            'payroll_processed' => 'Payroll processed',
            'leave_request' => 'Leave request',
        ][$this->type] ?? 'New activity';
    }
}