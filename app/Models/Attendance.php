<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i'
    ];

    protected $appends = ['hours_worked', 'is_late'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHoursWorkedAttribute()
    {
        if (!$this->check_in || !$this->check_out) {
            return 0;
        }

        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        return round($checkOut->diffInMinutes($checkIn) / 60, 2);
    }

    public function getIsLateAttribute()
    {
        if (!$this->check_in) {
            return false;
        }

        return Carbon::parse($this->check_in)->gt(Carbon::parse('08:30'));
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeForMonth($query, $month)
    {
        return $query->whereMonth('date', Carbon::parse($month)->month)
            ->whereYear('date', Carbon::parse($month)->year);
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}