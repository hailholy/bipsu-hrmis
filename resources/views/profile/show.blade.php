@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row items-start gap-8">
            <!-- Profile Photo -->
            <div class="w-full md:w-1/4 flex flex-col items-center">
                <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="w-32 h-32 rounded-full mb-4">
                <h2 class="text-xl font-semibold">{{ $user->first_name }} {{ $user->last_name }}</h2>
                <p class="text-gray-600">{{ ucfirst($user->role) }}</p>
            </div>
            
            <!-- Profile Details -->
            <div class="w-full md:w-3/4">
                <h3 class="text-lg font-medium mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500">First Name</p>
                        <p class="font-medium">{{ $user->first_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Last Name</p>
                        <p class="font-medium">{{ $user->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Employee ID</p>
                        <p class="font-medium">{{ $user->employee_id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Department</p>
                        <p class="font-medium">{{ $user->department }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection