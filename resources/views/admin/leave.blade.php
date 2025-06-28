@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<style>
    /* Add to your CSS file */
.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

#leaveRequestModal {
    z-index: 1000;
}
</style>
<body class="bg-gray-50">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending Requests</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $pendingCount }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $pendingDiff >= 0 ? '+' : '' }}{{ $pendingDiff }} from yesterday
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-blue-600 text-sm font-medium hover:underline flex items-center">
                    View all <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
        
        <!-- Approved Leaves Card -->
        <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Approved Leaves</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $approvedCount }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $approvedDiff >= 0 ? '+' : '' }}{{ $approvedDiff }} this week
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-green-600 text-sm font-medium hover:underline flex items-center">
                    View all <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
        
        <!-- Rejected Leaves Card -->
        <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Rejected Leaves</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-1">{{ $rejectedCount }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $rejectedDiff >= 0 ? '+' : '' }}{{ $rejectedDiff }} from last week
                    </p>
                </div>
                <div class="bg-red-100 p-3 rounded-full text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-red-600 text-sm font-medium hover:underline flex items-center">
                    View all <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
        
        <!-- Leave Types Card -->
        <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Leave Types</p>
                    <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $leaveTypesCount }}</h3>
                    <p class="text-xs text-gray-500 mt-1">3 customizable</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full text-purple-600">
                    <i class="fas fa-tags text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-purple-600 text-sm font-medium hover:underline flex items-center">
                    Manage types <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Leaves -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Leave Statistics Chart -->
        <div class="bg-white rounded-xl shadow p-6 lg:col-span-2 transition duration-300 card-hover">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Leave Statistics (Last 6 Months)</h2>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg font-medium" id="monthlyBtn">Monthly</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium" id="quarterlyBtn">Quarterly</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium" id="yearlyBtn">Yearly</button>
                </div>
            </div>
            <div class="chart-container h-64">
                <canvas id="leaveChart"></canvas>
            </div>
        </div>

        <!-- Recent Leaves -->
        <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Recent Leave Requests</h2>
                <a href="#" class="text-blue-600 text-sm font-medium hover:underline flex items-center">
                    View all <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="space-y-4">
                @foreach($recentLeaves as $leave)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        @if($leave->user->profile_photo_path)
                        <img src="{{ asset('storage/' . $leave->user->profile_photo_path) }}" alt="User" class="w-8 h-8 rounded-full">
                        @else
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-xs text-gray-600">{{ substr($leave->user->first_name, 0, 1) }}{{ substr($leave->user->last_name, 0, 1) }}</span>
                        </div>
                        @endif
                        <div>
                            <p class="font-medium text-sm">{{ $leave->user->first_name }} {{ $leave->user->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $leave->type }} - {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }} days</p>
                        </div>
                    </div>
                    <span class="status-badge 
                        @if($leave->status == 'approved') bg-green-100 text-green-800
                        @elseif($leave->status == 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($leave->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pending Approvals and Leave Types -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pending Approvals -->
        <div class="bg-white rounded-xl shadow p-6 lg:col-span-2 transition duration-300 card-hover">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Pending Approvals</h2>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg font-medium" id="filterAll">All</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium" id="filterFaculty">Faculty</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium" id="filterStaff">Staff</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="approvalsTableBody">
                        @foreach($pendingApprovals as $leave)
                        <tr class="hover:bg-gray-50" data-department="{{ $leave->user->department }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($leave->user->profile_photo_path)
                                        <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $leave->user->profile_photo_path) }}" alt="">
                                        @else
                                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm text-gray-600">{{ substr($leave->user->first_name, 0, 1) }}{{ substr($leave->user->last_name, 0, 1) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $leave->user->first_name }} {{ $leave->user->last_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $leave->user->department }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $leave->type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('j M Y') }} - 
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('j M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }} days
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('leave.update', $leave->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-check-circle mr-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('leave.update', $leave->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-times-circle mr-1"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Leave Types and Balances -->
        <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Leave Requests</h2>
                <button onclick="openLeaveRequestModal()" class="px-3 py-1 text-xs bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 flex items-center">
                    <i class="fas fa-plus mr-1"></i> Add New Request
                </button>
            </div>
            <div class="space-y-4">
                @foreach($leaveTypes as $type => $details)
                <div class="p-4 border rounded-lg hover:border-{{ explode('-', $details['icon_color'])[1] }}-200 hover:bg-{{ explode('-', $details['icon_color'])[1] }}-50 transition">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-medium flex items-center">
                            <i class="{{ $details['icon'] }} mr-2 {{ $details['icon_color'] }}"></i> {{ $type }}
                        </h3>
                        <span class="text-sm text-gray-500">
                            @if($details['total_days'])
                                {{ $details['total_days'] }} days/{{ $details['period'] }}
                            @else
                                Unlimited
                            @endif
                        </span>
                    </div>
                    @if($details['total_days'])
                    <div class="w-full bg-gray-200 rounded-full progress-bar">
                        @php
                            $percentage = min(100, ($details['used_days'] / $details['total_days']) * 100);
                            $colorClass = [
                                'text-yellow-500' => 'bg-yellow-500',
                                'text-green-500' => 'bg-green-600',
                                'text-purple-500' => 'bg-purple-600',
                                'text-yellow-600' => 'bg-yellow-500'
                            ][$details['icon_color']];
                        @endphp
                        <div class="{{ $colorClass }} progress-bar rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500">Used: {{ $details['used_days'] }} days</p>
                        <p class="text-xs text-gray-500">
                            @if($details['total_days'])
                            Remaining: {{ $details['total_days'] - $details['used_days'] }} days
                            @else
                            Avg. per employee
                            @endif
                        </p>
                    </div>
                    @else
                    <div class="w-full bg-gray-200 rounded-full progress-bar">
                        <div class="bg-green-600 progress-bar rounded-full" style="width: {{ min(100, $details['used_days'] / 10) }}%"></div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500">Used: {{ $details['used_days'] }} days</p>
                        <p class="text-xs text-gray-500">Avg. per employee</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add Leave Request Modal -->
    <div id="leaveRequestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-semibold">Create New Leave Request</h3>
                <button onclick="closeLeaveRequestModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-4">
                <form id="leaveRequestForm" action="{{ route('leave.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Employee Selection (for admin) -->
                        @if(auth()->user()->role === 'admin')
                        <div class="md:col-span-2">
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee *</label>
                            <select name="user_id" id="employee_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        @endif
                        
                        <!-- Leave Type -->
                        <div class="md:col-span-2">
                            <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-1">Leave Type *</label>
                            <select name="type" id="leave_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required>
                                <option value="">Select Leave Type</option>
                                <option value="Annual Leave">Annual Leave</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Conference Leave">Conference Leave</option>
                            </select>
                        </div>
                        
                        <!-- Dates -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="date" name="start_date" id="start_date" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required>
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                            <input type="date" name="end_date" id="end_date" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required>
                        </div>
                        
                        <!-- Reason -->
                        <div class="md:col-span-2">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason *</label>
                            <textarea name="reason" id="reason" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeLeaveRequestModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart initialization
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('leaveChart').getContext('2d');
        let leaveChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartMonths),
                datasets: [
                    {
                        label: 'Approved',
                        data: @json($approvedData),
                        backgroundColor: '#4f46e5', // Indigo-600
                        borderColor: '#4f46e5',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Pending',
                        data: @json($pendingData),
                        backgroundColor: '#f59e0b', // Amber-500
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Rejected',
                        data: @json($rejectedData),
                        backgroundColor: '#ef4444', // Red-500
                        borderColor: '#ef4444',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#6b7280',
                            font: {
                                size: 13,
                                family: "'Inter', sans-serif",
                                weight: '500'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        titleFont: {
                            size: 14,
                            weight: '500'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: '#e5e7eb',
                            borderDash: [5]
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            },
                            stepSize: 5,
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                animation: {
                    duration: 1000
                }
            }
        });

        // Period toggle buttons
        document.getElementById('monthlyBtn').addEventListener('click', function() {
            updateChart('monthly');
            setActiveButton(this);
        });

        document.getElementById('quarterlyBtn').addEventListener('click', function() {
            updateChart('quarterly');
            setActiveButton(this);
        });

        document.getElementById('yearlyBtn').addEventListener('click', function() {
            updateChart('yearly');
            setActiveButton(this);
        });

        function setActiveButton(activeButton) {
            document.querySelectorAll('#monthlyBtn, #quarterlyBtn, #yearlyBtn').forEach(btn => {
                btn.classList.remove('bg-blue-100', 'text-blue-700');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });
            activeButton.classList.remove('bg-gray-100', 'text-gray-600');
            activeButton.classList.add('bg-blue-100', 'text-blue-700');
        }

        function updateChart(period) {
            fetch(`/leave/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    leaveChart.data.labels = data.labels;
                    leaveChart.data.datasets[0].data = data.approved;
                    leaveChart.data.datasets[1].data = data.pending;
                    leaveChart.data.datasets[2].data = data.rejected;
                    leaveChart.update();
                });
        }

        // Department filter functionality
        document.getElementById('filterAll').addEventListener('click', function() {
            filterApprovals('all');
            setActiveFilterButton(this);
        });

        document.getElementById('filterFaculty').addEventListener('click', function() {
            filterApprovals('Faculty');
            setActiveFilterButton(this);
        });

        document.getElementById('filterStaff').addEventListener('click', function() {
            filterApprovals('Staff');
            setActiveFilterButton(this);
        });

        function setActiveFilterButton(activeButton) {
            document.querySelectorAll('#filterAll, #filterFaculty, #filterStaff').forEach(btn => {
                btn.classList.remove('bg-blue-100', 'text-blue-700');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });
            activeButton.classList.remove('bg-gray-100', 'text-gray-600');
            activeButton.classList.add('bg-blue-100', 'text-blue-700');
        }

        function filterApprovals(department) {
            const rows = document.querySelectorAll('#approvalsTableBody tr');
            
            rows.forEach(row => {
                if (department === 'all' || row.dataset.department === department) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Modal functions
        function openLeaveRequestModal() {
            document.getElementById('leaveRequestModal').classList.remove('hidden');
            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').value = today;
            document.getElementById('end_date').value = today;
            
            // Focus on first input field
            setTimeout(() => {
                const firstInput = document.querySelector('#leaveRequestForm input, #leaveRequestForm select');
                if (firstInput) firstInput.focus();
            }, 100);
        }

        function closeLeaveRequestModal() {
            document.getElementById('leaveRequestModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('leaveRequestModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeLeaveRequestModal();
            }
        });

        // Close modal when clicking the close button
        document.querySelector('#leaveRequestModal .fa-times').closest('button').addEventListener('click', closeLeaveRequestModal);

        // Form validation
        document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            
            if (endDate < startDate) {
                e.preventDefault();
                alert('End date must be after start date');
                return false;
            }
            return true;
        });

        // Make modal functions available globally
        window.openLeaveRequestModal = openLeaveRequestModal;
        window.closeLeaveRequestModal = closeLeaveRequestModal;
    });
</script>
</body>
@endsection