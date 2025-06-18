@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 attendance-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Today's Present</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['present'] }}</h3>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4"><span class="text-green-500">+5%</span> from yesterday</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 attendance-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Today's Absent</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['absent'] }}</h3>
                </div>
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-user-slash text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4"><span class="text-red-500">-2%</span> from yesterday</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 attendance-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Late Arrivals</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['late'] }}</h3>
                </div>
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4"><span class="text-yellow-500">+3%</span> from yesterday</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 attendance-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">On Leave</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['on_leave'] }}</h3>
                </div>
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-umbrella-beach text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4"><span class="text-blue-500">+1%</span> from yesterday</p>
        </div>
    </div>
    
    <!-- Attendance Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Time In/Out Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4">Clock In/Out</h3>
            <div class="text-center mb-4">
                <div class="text-2xl font-bold" id="current-time">00:00:00</div>
                <div class="text-gray-500" id="current-date">Monday, January 1, 2023</div>
            </div>
            
            <div class="flex flex-col space-y-3">
                <form method="POST" action="{{ route('attendance.check-in') }}">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-fingerprint mr-2"></i> Time In
                    </button>
                </form>
                
                <form method="POST" action="{{ route('attendance.check-out') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-fingerprint mr-2"></i> Time Out
                    </button>
                </form>
                
                <button id="qrCodeBtn" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg font-medium flex items-center justify-center">
                    <i class="fas fa-qrcode mr-2"></i> Scan QR Code
                </button>
            </div>
        </div>
        
        <!-- Biometric Implementation -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4">Biometric Attendance</h3>
            <div class="biometric-placeholder rounded-lg p-4 mb-4 text-center">
                <i class="fas fa-fingerprint text-5xl text-blue-500 mb-3"></i>
                <p class="text-gray-600">Future biometric implementation</p>
                <p class="text-sm text-gray-500 mt-2">Fingerprint or facial recognition will be integrated here</p>
            </div>
            <div class="text-sm text-gray-600">
                <p class="mb-2"><i class="fas fa-check-circle text-green-500 mr-2"></i> Secure authentication</p>
                <p class="mb-2"><i class="fas fa-check-circle text-green-500 mr-2"></i> Real-time tracking</p>
                <p><i class="fas fa-check-circle text-green-500 mr-2"></i> Fraud prevention</p>
            </div>
        </div>
        
        <!-- QR Code Scanner -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4">QR Code Scanner</h3>
            <div class="qr-scanner" id="qrScanner">
                <div class="qr-overlay" id="qrOverlay">
                    <div class="text-center">
                        <i class="fas fa-qrcode text-4xl mb-2"></i>
                        <p>Click to activate scanner</p>
                    </div>
                </div>
                <video id="qrVideo" class="hidden"></video>
            </div>
            <div class="mt-4 text-center text-sm text-gray-500">
                Scan your employee QR code to record attendance
            </div>
        </div>
    </div>
    
    <!-- Recent Attendance Records -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Recent Attendance Records</h3>
                <div class="flex space-x-2">
                    <button class="bg-blue-50 text-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-100">
                        <i class="fas fa-download mr-1"></i> Export
                    </button>
                    <button class="bg-gray-50 text-gray-600 px-3 py-1 rounded text-sm hover:bg-gray-100">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentAttendances as $attendance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $attendance->user->profile_photo_url }}" alt="{{ $attendance->user->full_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $attendance->user->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $attendance->user->department }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->check_in ? Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->check_out ? Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-{{ strtolower(str_replace(' ', '-', $attendance->status)) }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">{{ $recentAttendances->count() }}</span> of <span class="font-medium">{{ $stats['present'] + $stats['absent'] + $stats['late'] + $stats['on_leave'] }}</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</a>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">2</a>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">3</a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attendance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4">Attendance Trend</h3>
            <div class="h-64">
                <canvas id="attendanceTrendChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4">Department Attendance</h3>
            <div class="h-64">
                <canvas id="departmentAttendanceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div id="qrScannerModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Modal content same as in your example -->
</div>

<!-- Time In/Out Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Modal content same as in your example -->
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qr-scanner@1.4.1/qr-scanner.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update current time
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString(undefined, dateOptions);
            
            document.getElementById('current-time').textContent = timeStr;
            document.getElementById('current-date').textContent = dateStr;
        }
        
        setInterval(updateClock, 1000);
        updateClock();

        // QR Code Scanner
        const qrScanner = new QrScanner(
            document.getElementById('qrVideo'),
            result => {
                console.log('QR code scanned:', result);
                qrScanner.stop();
                document.getElementById('qrScannerModal').classList.add('hidden');
                
                // Send the QR code data to the server
                fetch('{{ route("attendance.qr-check-in") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ qr_code: result })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Attendance recorded successfully');
                        // Refresh the recent attendance records
                        fetchRecentAttendances();
                    } else {
                        showToast(data.message || 'Error recording attendance', 'error');
                    }
                })
                .catch(error => {
                    showToast('Error processing QR code', 'error');
                    console.error('Error:', error);
                });
            },
            {
                highlightScanRegion: true,
                highlightCodeOutline: true,
            }
        );

        document.getElementById('qrCodeBtn').addEventListener('click', function() {
            const modal = document.getElementById('qrScannerModal');
            modal.classList.remove('hidden');
            qrScanner.start();
        });

        document.getElementById('closeQrScanner').addEventListener('click', function() {
            qrScanner.stop();
            document.getElementById('qrScannerModal').classList.add('hidden');
        });

        // Function to fetch recent attendance records
        function fetchRecentAttendances() {
            fetch('{{ route("attendance") }}?recent=1')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.querySelector('tbody');
                    if (newTableBody) {
                        document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
                    }
                });
        }

        // Initialize charts with correct IDs (fix the IDs in your HTML)
        const attendanceTrendCtx = document.getElementById('attendanceTrendChart').getContext('2d');
        const attendanceTrendChart = new Chart(attendanceTrendCtx, {
            type: 'line',
            data: {
                labels: @json($attendanceTrend->pluck('date')->map(fn($date) => Carbon\Carbon::parse($date)->format('M d'))),
                datasets: [
                    {
                        label: 'Present',
                        data: @json($attendanceTrend->pluck('present')),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Absent',
                        data: @json($attendanceTrend->pluck('absent')),
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Late',
                        data: @json($attendanceTrend->pluck('late')),
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const departmentAttendanceCtx = document.getElementById('departmentAttendanceChart').getContext('2d');
        const departmentAttendanceChart = new Chart(departmentAttendanceCtx, {
            type: 'bar',
            data: {
                labels: @json($departmentAttendance->pluck('department')),
                datasets: [
                    {
                        label: 'Present',
                        data: @json($departmentAttendance->pluck('present')),
                        backgroundColor: '#10B981',
                    },
                    {
                        label: 'Absent',
                        data: @json($departmentAttendance->pluck('absent')),
                        backgroundColor: '#EF4444',
                    },
                    {
                        label: 'Late',
                        data: @json($departmentAttendance->pluck('late')),
                        backgroundColor: '#F59E0B',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });

        // Real-time updates using Pusher or polling
        function setupRealTimeUpdates() {
            // Using polling as fallback (in a real app, use Pusher or similar)
            setInterval(() => {
                fetch('{{ route("attendance.stats") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update stats cards
                        document.querySelector('[data-stat="present"]').textContent = data.present;
                        document.querySelector('[data-stat="absent"]').textContent = data.absent;
                        document.querySelector('[data-stat="late"]').textContent = data.late;
                        document.querySelector('[data-stat="on_leave"]').textContent = data.on_leave;
                    });
            }, 30000); // Poll every 30 seconds
        }

        setupRealTimeUpdates();

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md shadow-md text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } z-50`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    });
</script>
@endpush
@endsection