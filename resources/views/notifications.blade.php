@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h1 class="text-xl font-semibold">Notifications</h1>
            <button id="markAllAsRead" class="text-sm text-blue-600 hover:text-blue-800">
                Mark all as read
            </button>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div 
                    class="px-6 py-4 hover:bg-gray-50 notification-item"
                    data-id="{{ $notification->id }}"
                >
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-1">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center 
                                bg-{{ $notification->data['color'] ?? 'blue' }}-100 text-{{ $notification->data['color'] ?? 'blue' }}-600">
                                <i class="fas fa-{{ $notification->data['icon'] ?? 'bell' }}"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>
                                <span class="text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            @if(!$notification->read_at)
                                <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    New
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-4 text-center text-gray-500">
                    No notifications found
                </div>
            @endforelse
        </div>
        
        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark notification as read when clicked
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.classList.add('bg-gray-50');
                        const newBadge = this.querySelector('.bg-blue-100');
                        if (newBadge) newBadge.remove();
                        
                        // Update notification count in navbar
                        updateNotificationCount(data.unread_count);
                    }
                });
            });
        });
        
        // Mark all as read
        document.getElementById('markAllAsRead').addEventListener('click', function() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update all notifications to appear as read
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.add('bg-gray-50');
                        const newBadge = item.querySelector('.bg-blue-100');
                        if (newBadge) newBadge.remove();
                    });
                    
                    // Update notification count in navbar
                    updateNotificationCount(data.unread_count);
                }
            });
        });
        
        function updateNotificationCount(count) {
            const counter = document.querySelector('.notification-counter');
            if (counter) {
                if (count > 0) {
                    counter.textContent = count;
                    counter.classList.remove('hidden');
                } else {
                    counter.classList.add('hidden');
                }
            }
        }
    });
</script>
@endpush
@endsection