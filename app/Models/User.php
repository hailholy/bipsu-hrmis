<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'gender',
        'dob',
        'employee_id',
        'department',
        'role',
        'profile_photo_path',
        'user_status',
        'hire_date',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
        'hire_date' => 'date',
    ];

        public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }

        // Fallback to UI Avatars with department-based colors
        $colors = [
            'STCS' => 'rgba(255, 159, 64, 0.6)',  // Orange
            'SOE' => 'rgba(153, 102, 255, 0.6)',   // Violet
            'SCJE' => 'rgba(255, 99, 132, 0.6)', // Pink
            'SNHS' => 'rgba(75, 192, 192, 0.6)',  // Green
            'SME' =>  'rgba(255, 206, 86, 0.6)',   // Yellow
            'SAS' => 'rgba(54, 162, 235, 0.6)', //Blue
            'STED' => 'rgba(199, 199, 199, 0.6)', //Gray
            'default' => '7C3AED' // Purple
        ];

        
        $bgColor = $colors[$this->department] ?? $colors['default'];
        
        return "https://ui-avatars.com/api/?" . http_build_query([
            'name' => $this->first_name . '+' . $this->last_name,
            'background' => $bgColor,
            'color' => 'FFFFFF',
            'size' => '256',
            'rounded' => 'true',
            'bold' => 'true',
            'format' => 'png'
        ]);


    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}