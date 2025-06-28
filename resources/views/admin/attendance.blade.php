@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')
    <style>
        /* Custom styles for biometric scanner animation */
        .biometric-scanner {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }
        .biometric-scanner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(transparent, rgba(59, 130, 246, 0.5), transparent);
            animation: scan 2s linear infinite;
        }
        @keyframes scan {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 3px;
        }

        .loading-spinner {
            display: inline-block;
            width: 1em;
            height: 1em;
            border: 2px solid rgba(0,0,0,.1);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto p-4 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- User Information Card -->
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Your Information</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ auth()->user()->full_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ auth()->user()->department }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-user text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">Employee ID: <span class="font-medium">{{ auth()->user()->employee_id }}</span></p>
                </div>
            </div>

            <!-- Today's Date Card -->
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Today's Date</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="currentDate">{{ now()->format('F j, Y') }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">Day: <span class="font-medium">{{ now()->format('l') }}</span></p>
                </div>
            </div>

            <!-- Current Time Card -->
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Current Time</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="currentTime">Loading...</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">Shift: <span class="font-medium">9:00 AM - 5:00 PM</span></p>
                </div>
            </div>
            
            <!-- Attendance Status Card -->
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Your Status</p>
                        @if($todayRecord)
                            <h3 class="text-xl font-bold {{ $todayRecord->status === 'Present' ? 'text-green-600' : ($todayRecord->status === 'Late' ? 'text-yellow-600' : ($todayRecord->status === 'Absent' ? 'text-red-600' : 'text-purple-600')) }}">
                                {{ str_replace('_', ' ', $todayRecord->status) }}
                            </h3>
                            @if($todayRecord->check_in)
                                <p class="text-sm text-gray-600 mt-1">Clocked in at {{ \Carbon\Carbon::parse($todayRecord->check_in)->format('h:i A') }}</p>
                            @endif
                        @else
                            <h3 class="text-xl font-bold text-gray-600">Not Checked In</h3>
                            <p class="text-sm text-gray-600 mt-1">--:--</p>
                        @endif
                    </div>
                    <div class="{{ $todayRecord && $todayRecord->status === 'Present' ? 'bg-green-100' : ($todayRecord && $todayRecord->status === 'Late' ? 'bg-yellow-100' : ($todayRecord && $todayRecord->status === 'Absent' ? 'bg-red-100' : ($todayRecord && $todayRecord->status === 'On_Leave' ? 'bg-purple-100' : 'bg-gray-100'))) }} p-3 rounded-full">
                        <i class="fas {{ $todayRecord && $todayRecord->status === 'Present' ? 'fa-check-circle text-green-600' : ($todayRecord && $todayRecord->status === 'Late' ? 'fa-clock text-yellow-600' : ($todayRecord && $todayRecord->status === 'Absent' ? 'fa-times-circle text-red-600' : ($todayRecord && $todayRecord->status === 'On_Leave' ? 'fa-umbrella-beach text-purple-600' : 'fa-question-circle text-gray-600'))) }} text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">Hours worked: 
                        <span class="font-medium">
                            @if($todayRecord && $todayRecord->check_in && $todayRecord->check_out)
                                {{ $todayRecord->hours_worked }}
                            @elseif($todayRecord && $todayRecord->check_in)
                                Calculating...
                            @else
                                0h 0m
                            @endif
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Biometric Scanner -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Biometric Attendance</h2>
                        <div class="text-blue-500">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </div>
                    
                    <div class="biometric-scanner h-48 flex flex-col items-center justify-center mb-4 cursor-pointer" id="biometricScanner">
                        <div class="text-center p-4">
                            <i class="fas fa-fingerprint text-5xl text-blue-500 mb-2"></i>
                            <p class="text-gray-600 font-medium">Place your finger on the scanner</p>
                            <p class="text-xs text-gray-400 mt-1">Ensure your finger is clean and dry</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between space-x-3">
                        <button class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg font-medium flex items-center justify-center transition duration-200">
                            <i class="fas fa-sync-alt mr-2"></i> Retry
                        </button>
                        <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium flex items-center justify-center transition duration-200">
                            <i class="fas fa-check-circle mr-2"></i> Verify
                        </button>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500 flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-blue-500"></i> Your biometric data is securely encrypted
                        </p>
                    </div>
                </div>
                
                <!-- Manual Attendance section -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Manual Attendance</h2>
                        <div class="text-blue-500 text-xl">
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-center justify-center mb-6 p-6 bg-blue-50 rounded-lg border border-blue-100">
                        <i class="fas fa-clock text-5xl text-blue-500 mb-3"></i>
                        <p class="text-gray-700 font-medium text-center">Manual Time Recording</p>
                        <p class="text-sm text-gray-500 mt-1 text-center">Click below to record your attendance</p>
                    </div>
                    
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="space-y-3">
                        <form action="{{ route('attendance.clockIn') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-medium flex items-center justify-center transition duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-sign-in-alt mr-2"></i> Clock In
                            </button>
                        </form>

                        <form action="{{ route('attendance.clockOut') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg font-medium flex items-center justify-center transition duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-sign-out-alt mr-2"></i> Clock Out
                            </button>
                        </form>
                    </div>
                    
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-400"></i> Your attendance will be recorded immediately
                        </p>
                    </div>
            </div>

        
        <!-- Quick Stats -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Today's Summary</h2>
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Present</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $todaySummary->present_count ?? 0 }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Late</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $todaySummary->late_count ?? 0 }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Absent</p>
                    <p class="text-2xl font-bold text-red-600">{{ $todaySummary->absent_count ?? 0 }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">On Leave</p>
                    <p class="text-2xl font-bold text-green-600">{{ $todaySummary->on_leave_count ?? 0 }}</p>
                </div>
            </div>
        </div>
       
        <!-- Attendance Chart Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Attendance Overview</h2>
                    <p class="text-sm text-gray-500">Monthly attendance comparison</p>
                </div>
                <div class="mt-2 md:mt-0">
                    <select id="chart-period-selector" class="appearance-none bg-gray-100 border-0 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="2" selected>Last 2 Months</option>
                        <option value="4">Last 4 Months</option>
                        <option value="6">Last 6 Months</option>
                        <option value="8">Last 8 Months</option>
                        <option value="12">Last 12 Months</option>
                    </select>
                </div>
            </div>
            
            <div class="chart-container" style="height: 400px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

            <!-- Right Column - Attendance Records -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Filters and Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <form id="attendance-filter-form" method="GET" action="{{ route('attendance') }}">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <input type="date" name="date" value="{{ request('date') }}" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="relative">
                                    <select name="department" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Departments</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>{{ $department }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="relative">
                                    <select name="status" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Statuses</option>
                                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                                        <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="On_Leave" {{ request('status') == 'On_Leave' ? 'selected' : '' }}>On Leave</option>
                                    </select>
                                </div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                                    <i class="fas fa-filter mr-2"></i> Filter
                                </button>
                                <a href="{{ route('attendance') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                                    <i class="fas fa-sync-alt mr-2"></i> Reset
                                </a>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('attendance.export') }}?{{ http_build_query(request()->query()) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                                    <i class="fas fa-download mr-2"></i> Export
                                </a>
                                <button type="button" onclick="openManualEntryModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Add Manual
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Attendance Records Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('employee')">
                                        Employee <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('department')">
                                        Department <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('check_in')">
                                        Check In <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('check_out')">
                                        Check Out <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('status')">
                                        Status <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                                @forelse($attendanceRecords as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $record->user->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $record->user->full_name }}" onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $record->user->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $record->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $record->user->department }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('h:i A') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('h:i A') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($record->status == 'Present')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                                        @elseif($record->status == 'Late')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                                        @elseif($record->status == 'Absent')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                                        @elseif($record->status == 'On_Leave')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">On Leave</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button onclick="editAttendance({{ $record->id }})" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button onclick="confirmDelete({{ $record->id }})" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No attendance records found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($attendanceRecords->previousPageUrl())
                                <a href="{{ $attendanceRecords->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                            @endif
                            @if($attendanceRecords->nextPageUrl())
                                <a href="{{ $attendanceRecords->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $attendanceRecords->firstItem() }}</span> to <span class="font-medium">{{ $attendanceRecords->lastItem() }}</span> of <span class="font-medium">{{ $attendanceRecords->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                {{ $attendanceRecords->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Function to handle sorting
        function sortTable(column) {
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            
            // Toggle between asc and desc if same column is clicked
            if (params.get('sort') === column) {
                params.set('direction', params.get('direction') === 'asc' ? 'desc' : 'asc');
            } else {
                params.set('sort', column);
                params.set('direction', 'asc');
            }
            
            window.location.href = url.pathname + '?' + params.toString();
        }

        // Function to confirm deletion
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this attendance record?')) {
                fetch(`/attendance/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('An error occurred', 'error');
                });
            }
        }

        // Function to open manual entry modal
        function openManualEntryModal() {
            alert('Manual entry modal would open here');
        }

        // Function to edit attendance
        function editAttendance(id) {
            alert(`Edit attendance record with ID: ${id}`);
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md shadow-md text-white ${
                type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            } z-50`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize current time display
            function updateCurrentTime() {
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const formattedHours = hours % 12 || 12;
                const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
                document.getElementById('currentTime').textContent = 
                    `${formattedHours}:${formattedMinutes} ${ampm}`;
            }

            updateCurrentTime();
            setInterval(updateCurrentTime, 60000);

            // Biometric scanner interaction
            const scanner = document.querySelector('.biometric-scanner');
            if (scanner) {
                scanner.addEventListener('click', function() {
                    // Simulate scanning process
                    this.querySelector('i').classList.add('animate-pulse');
                    this.querySelector('p').textContent = 'Scanning...';
                    
                    setTimeout(() => {
                        this.querySelector('i').classList.remove('animate-pulse');
                        this.querySelector('p').textContent = 'Scan completed!';
                        
                        // Show success message
                        setTimeout(() => {
                            alert('Employee identified: {{ auth()->user()->full_name }}\nAttendance recorded successfully!');
                            this.querySelector('p').textContent = 'Place your finger on the scanner';
                        }, 1000);
                    }, 2000);
                });
            }

            // Initialize attendance chart
            let attendanceChart;
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            // Initialize chart with default 2 months
            initChart(2);
            
            // Handle period selector change
            document.getElementById('chart-period-selector').addEventListener('change', function() {
                initChart(parseInt(this.value));
            });

            function initChart(months = 2) {
                document.getElementById('attendanceChart').innerHTML = '<div class="flex items-center justify-center h-full"><div class="loading-spinner" style="width: 3rem; height: 3rem;"></div></div>';
                
                fetch(`/attendance/monthly-comparison-data?months=${months}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderChart(data.data);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching chart data:', error);
                        document.getElementById('attendanceChart').innerHTML = '<p class="text-red-500 text-center py-10">Failed to load chart data</p>';
                    });
            }
            
            function renderChart(chartData) {
                if (attendanceChart) {
                    attendanceChart.destroy();
                }
                
                const datasets = [
                    {
                        label: 'Present',
                        data: chartData.current.present,
                        backgroundColor: 'rgba(191, 219, 254, 1)',  // bg-blue-50:
                        borderColor: 'rgba(59, 130, 246, 1)',        
                        borderWidth: 1,
                    },
                    {
                      label: 'Late',
                        data: chartData.current.late,
                        backgroundColor: 'rgba(253, 230, 138, 1)',  // bg-yellow-50: 
                        borderColor: 'rgba(234, 179, 8, 1)',        
                        borderWidth: 1,
                    },
                    {
                        label: 'Absent',
                        data: chartData.current.absent,
                        backgroundColor:'rgba(252, 165, 165, 1)',  // bg-red-50: 
                        borderColor: 'rgba(239, 68, 68, 1)',          
                        borderWidth: 1,
                    },
                    {
                        label: 'On Leave',
                        data: chartData.current.on_leave,
                        backgroundColor: 'rgba(134, 239, 172, 1)',  // bg-green-50:
                        borderColor: 'rgba(16, 185, 129, 1)',        
                        borderWidth: 1,
                    }
                ];
                
                attendanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: false,
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                stacked: false,
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 1000
                        }
                    }
                });
            }
        });
    </script>
@endsection