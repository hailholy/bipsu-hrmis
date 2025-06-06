@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold mb-6">Profile Settings</h2>
        
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Profile Photo Section -->
            <div class="w-full md:w-1/3">
                <div class="mb-4">
                    <h3 class="text-lg font-medium mb-4">Profile Photo</h3>
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative">
                            <img src="{{ auth()->user()->profile_photo_url }}" 
                                alt="Profile Photo" 
                                class="w-32 h-32 rounded-full object-cover border-2 border-gray-200">
                        </div>
                        <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
                            @csrf
                            <div class="flex flex-col items-center">
                                <label for="profile_photo" class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="fas fa-camera mr-2"></i>
                                    Change Photo
                                </label>
                                <input type="file" 
                                       name="profile_photo" 
                                       id="profile_photo" 
                                       class="hidden" 
                                       accept="image/*">
                                @error('profile_photo')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-xs mt-2">JPG, PNG (Max 2MB)</p>
                            </div>
                        </form>
                    </div>
                </div>
                @if(auth()->user()->profile_photo_path)
                    <div class="mt-4">
                        <form method="POST" action="{{ route('profile.photo.delete') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm hover:text-red-800">
                                <i class="fas fa-trash mr-1"></i> Remove Photo
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            
            <!-- Other profile settings can go here -->
            <div class="w-full md:w-2/3">
                <!-- Add other form fields here -->
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-submit the form when a file is selected
    document.getElementById('profile_photo').addEventListener('change', function() {
        // Show loading indicator
        const button = this.closest('form').querySelector('label');
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Uploading...';
        button.classList.add('opacity-75');
        
        this.form.submit();
    });
</script>
@endsection