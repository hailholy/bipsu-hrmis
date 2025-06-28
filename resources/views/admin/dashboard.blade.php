@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    </style>


<main class="p-6">
    <!-- Welcome Banner -->
    <div class="container mx-auto px-4"> 
        <div class="bg-blue-800 text-white rounded-xl p-6 mb-6 shadow-lg">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->first_name }}!</h2>
                    <p class="opacity-90">Here's what's happening today.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        {{ now()->format('l, F j, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

<div class="container mx-auto px-4 py-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">Total Employees</p>
                    <h3 class="text-2xl font-bold">{{ number_format($stats['totalEmployees']) }}</h3>
                    <p class="text-green-500 text-sm">+12% from last month</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500">Present Today</p>
                <h3 class="text-2xl font-bold" data-stat="present">{{ $attendanceStats->present ?? 0 }}</h3>
                <p class="text-sm {{ ($attendanceStats->present ?? 0) > 0 ? 'text-green-500' : 'text-gray-500' }}">
                    {{ $totalEmployees > 0 ? round(($attendanceStats->present ?? 0)/$totalEmployees*100) : 0 }}% of workforce
                </p>
            </div>
        </div>
    </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">Monthly Payroll</p>
                    <h3 class="text-2xl font-bold">₱ {{ number_format($payrollData->total_payroll) }}</h3>
                    <p class="{{ $payrollPercentageChange >= 0 ? 'text-green-500' : 'text-red-500' }} text-sm">
                        {{ $payrollPercentageChange >= 0 ? '+' : '' }}{{ $payrollPercentageChange }}% from last month
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">Faculty Members</p>
                    <h3 class="text-2xl font-bold">{{ number_format($stats['facultyCount']) }}</h3>
                    <p class="text-green-500 text-sm">+8% from last year</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Department Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Employee Distribution by Department</h2>
                <select id="departmentFilter" class="border rounded px-3 py-1 text-sm">
                    <option value="all">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>

       <!-- Gender Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Gender Distribution</h2>
            <div class="chart-container" style="position: relative; height: 250px;">
                <canvas id="genderChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                @php
                    $total = $genderStats['male'] + $genderStats['female'] + $genderStats['other'];
                    $malePercentage = $total > 0 ? round(($genderStats['male'] / $total) * 100) : 0;
                    $femalePercentage = $total > 0 ? round(($genderStats['female'] / $total) * 100) : 0;
                    $otherPercentage = $total > 0 ? round(($genderStats['other'] / $total) * 100) : 0;
                @endphp
                <div class="p-2">
                    <div class="text-xl font-bold text-blue-600">{{ $genderStats['male'] }}</div>
                    <div class="text-xs text-gray-500">Male ({{ $malePercentage }}%)</div>
                </div>
                <div class="p-2">
                    <div class="text-xl font-bold text-pink-600">{{ $genderStats['female'] }}</div>
                    <div class="text-xs text-gray-500">Female ({{ $femalePercentage }}%)</div>
                </div>
                <div class="p-2">
                    <div class="text-xl font-bold text-purple-600">{{ $genderStats['other'] }}</div>
                    <div class="text-xs text-gray-500">Other ({{ $otherPercentage }}%)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance and Payroll Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Attendance Summary -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Attendance Summary</h2>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Today's Attendance</span>
                        <span class="text-sm font-medium">{{ $attendanceStats->present ?? 0 }}/{{ $stats['totalEmployees'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php
                            $attendancePercentage = $stats['totalEmployees'] > 0 
                                ? ($attendanceStats->present / $stats['totalEmployees']) * 100 
                                : 0;
                        @endphp
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $attendancePercentage }}%"></div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="p-3 border rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $attendanceStats->present ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Present</div>
                    </div>
                    <div class="p-3 border rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $attendanceStats->on_leave ?? 0 }}</div>
                        <div class="text-xs text-gray-500">On Leave</div>
                    </div>
                    <div class="p-3 border rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $attendanceStats->absent ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Absent</div>
                    </div>
                </div>
                <div class="mt-6">
                    <h3 class="text-sm font-medium mb-3">Recent Check-ins</h3>
                    <div class="space-y-3">
                        @foreach($recentCheckins as $checkin)
                        <div class="flex items-center">
                            <img class="w-8 h-8 rounded-full mr-3" src="{{ $checkin->profile_photo_path }}" alt="{{ $checkin->first_name }} {{ $checkin->last_name }}">
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ $checkin->first_name }} {{ $checkin->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $checkin->department }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($checkin->check_in)->format('h:i A') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Payroll Summary -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Payroll Summary</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">{{ now()->format('F Y') }} Payroll</span>
                        <span class="text-sm font-medium">₱ {{ number_format($payrollData->total_payroll ?? 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php
                            $processedPercentage = $payrollData->total_payroll > 0 
                                ? ($payrollData->processed / $payrollData->total_payroll) * 100 
                                : 0;
                        @endphp
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $processedPercentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Processed: ₱ {{ number_format($payrollData->processed ?? 0) }}</span>
                        <span>Pending: ₱ {{ number_format($payrollData->pending ?? 0) }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 border rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <div class="text-xl font-bold">₱ {{ number_format($payrollData->faculty_payroll ?? 0) }}</div>
                                <div class="text-xs text-gray-500">Faculty</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div>
                                <div class="text-xl font-bold">₱ {{ number_format($payrollData->staff_payroll ?? 0) }}</div>
                                <div class="text-xs text-gray-500">Staff</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium mb-3">Upcoming Payments</h3>
                    <div class="space-y-3">
                        @foreach($upcomingPayments as $payment)
                        <div class="flex items-center justify-between p-2 border rounded-lg">
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full mr-3" src="{{ $payment->profile_photo_url }}" alt="{{ $payment->first_name }} {{ $payment->last_name }}">
                                <div>
                                    <p class="text-sm font-medium">{{ $payment->first_name }} {{ $payment->last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $payment->department }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">₱ {{ number_format($payment->net_salary) }}</p>
                                <p class="text-xs text-gray-500">Due: {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Recent Employees Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="text-lg font-semibold">Recent Employees</h2>
            <a href="{{ route('admin.employees') }}" 
            class="text-xs bg-white hover:bg-gray-50 text-blue-600 px-1 py-1 rounded flex items-center transition-colors duration-200">
                <i class="fas fa-users mr-1"></i> View All Employees
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentEmployees as $employee)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" 
                                        src="{{ $employee->profile_photo_url }}" 
                                        alt="{{ $employee->first_name }} {{ $employee->last_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $employee->department }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ucfirst($employee->role) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the data passed from Laravel
        const departmentLabels = @json($departmentLabels);
        const departmentData = @json($departmentData);
        const colorMap = @json($departmentColorMap);
        
        // Extract colors from the color map
        const backgroundColors = departmentLabels.map(label => colorMap[label].background);
        const borderColors = departmentLabels.map(label => colorMap[label].border);
        
        // Get the chart canvas
        const ctx = document.getElementById('departmentChart').getContext('2d');
        
        // Create the chart
        const departmentChart = new Chart(ctx, {
            type: 'bar', // Can be changed to 'pie' or 'doughnut'
            data: {
                labels: departmentLabels,
                datasets: [{
                    label: 'Employees by Department',
                    data: departmentData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = Math.round((value / total) * 100);
                                return `${label}${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // Filter functionality
        document.getElementById('departmentFilter').addEventListener('change', function() {
            const selectedDept = this.value;
            
            if (selectedDept === 'all') {
                // Reset to show all departments
                departmentChart.data.labels = departmentLabels;
                departmentChart.data.datasets[0].data = departmentData;
                departmentChart.data.datasets[0].backgroundColor = backgroundColors;
                departmentChart.data.datasets[0].borderColor = borderColors;
            } else {
                // Filter for selected department
                const deptIndex = departmentLabels.indexOf(selectedDept);
                if (deptIndex !== -1) {
                    departmentChart.data.labels = [selectedDept];
                    departmentChart.data.datasets[0].data = [departmentData[deptIndex]];
                    departmentChart.data.datasets[0].backgroundColor = [backgroundColors[deptIndex]];
                    departmentChart.data.datasets[0].borderColor = [borderColors[deptIndex]];
                }
            }
            
            departmentChart.update();
        });

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

        // Check if there are flash messages to show
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    });

      document.addEventListener('DOMContentLoaded', function() {
        // Gender Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    data: [@json($genderStats['male']), @json($genderStats['female']), @json($genderStats['other'])],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush
@endsection