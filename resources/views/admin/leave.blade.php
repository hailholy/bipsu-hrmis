@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
</head>
<body class="bg-gray-50">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Pending Requests</p>
                                <h3 class="text-2xl font-bold text-blue-600 mt-1">24</h3>
                                <p class="text-xs text-gray-500 mt-1">+2 from yesterday</p>
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
                    <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Approved Leaves</p>
                                <h3 class="text-2xl font-bold text-green-600 mt-1">56</h3>
                                <p class="text-xs text-gray-500 mt-1">+5 this week</p>
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
                    <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Rejected Leaves</p>
                                <h3 class="text-2xl font-bold text-red-600 mt-1">12</h3>
                                <p class="text-xs text-gray-500 mt-1">-3 from last week</p>
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
                    <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Leave Types</p>
                                <h3 class="text-2xl font-bold text-purple-600 mt-1">8</h3>
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
                                <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg font-medium">Monthly</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium">Quarterly</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium">Yearly</button>
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
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-sm">Dr. Michael Brown</p>
                                        <p class="text-xs text-gray-500">Sick Leave - 3 days</p>
                                    </div>
                                </div>
                                <span class="status-badge bg-yellow-100 text-yellow-800">Pending</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-sm">Prof. Emily Davis</p>
                                        <p class="text-xs text-gray-500">Conference - 5 days</p>
                                    </div>
                                </div>
                                <span class="status-badge bg-green-100 text-green-800">Approved</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="User" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-sm">Dr. James Wilson</p>
                                        <p class="text-xs text-gray-500">Annual Leave - 10 days</p>
                                    </div>
                                </div>
                                <span class="status-badge bg-red-100 text-red-800">Rejected</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-sm">Dr. Lisa Taylor</p>
                                        <p class="text-xs text-gray-500">Maternity - 60 days</p>
                                    </div>
                                </div>
                                <span class="status-badge bg-green-100 text-green-800">Approved</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="https://randomuser.me/api/portraits/men/12.jpg" alt="User" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-sm">Prof. Robert Clark</p>
                                        <p class="text-xs text-gray-500">Research - 7 days</p>
                                    </div>
                                </div>
                                <span class="status-badge bg-yellow-100 text-yellow-800">Pending</span>
                            </div>
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
                                <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg font-medium">All</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium">Faculty</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium">Staff</button>
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
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/men/32.jpg" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Dr. Andrew Miller</div>
                                                    <div class="text-sm text-gray-500">Computer Science</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Sick Leave</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">15-18 May 2023</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">4 days</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-check-circle mr-1"></i> Approve
                                            </button>
                                            <button class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times-circle mr-1"></i> Reject
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/women/28.jpg" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Prof. Jessica Lee</div>
                                                    <div class="text-sm text-gray-500">Mathematics</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Conference</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">22-26 June 2023</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">5 days</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-check-circle mr-1"></i> Approve
                                            </button>
                                            <button class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times-circle mr-1"></i> Reject
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/men/75.jpg" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Dr. Richard Harris</div>
                                                    <div class="text-sm text-gray-500">Administration</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Annual Leave</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">1-15 July 2023</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">15 days</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-check-circle mr-1"></i> Approve
                                            </button>
                                            <button class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times-circle mr-1"></i> Reject
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Leave Types and Balances -->
                    <div class="bg-white rounded-xl shadow p-6 transition duration-300 card-hover">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Leave Types & Balances</h2>
                            <button class="px-3 py-1 text-xs bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 flex items-center">
                                <i class="fas fa-plus mr-1"></i> Add New
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div class="p-4 border rounded-lg hover:border-blue-200 hover:bg-blue-50 transition">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-medium flex items-center">
                                        <i class="fas fa-sun mr-2 text-yellow-500"></i> Annual Leave
                                    </h3>
                                    <span class="text-sm text-gray-500">30 days/year</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full progress-bar">
                                    <div class="bg-blue-600 progress-bar rounded-full" style="width: 70%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-gray-500">Used: 21 days</p>
                                    <p class="text-xs text-gray-500">Remaining: 9 days</p>
                                </div>
                            </div>
                            <div class="p-4 border rounded-lg hover:border-green-200 hover:bg-green-50 transition">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-medium flex items-center">
                                        <i class="fas fa-procedures mr-2 text-green-500"></i> Sick Leave
                                    </h3>
                                    <span class="text-sm text-gray-500">Unlimited</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full progress-bar">
                                    <div class="bg-green-600 progress-bar rounded-full" style="width: 45%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-gray-500">Used: 8 days</p>
                                    <p class="text-xs text-gray-500">Avg. per employee</p>
                                </div>
                            </div>
                            <div class="p-4 border rounded-lg hover:border-purple-200 hover:bg-purple-50 transition">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-medium flex items-center">
                                        <i class="fas fa-baby mr-2 text-purple-500"></i> Maternity Leave
                                    </h3>
                                    <span class="text-sm text-gray-500">90 days</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full progress-bar">
                                    <div class="bg-purple-600 progress-bar rounded-full" style="width: 30%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-gray-500">Used: 27 days</p>
                                    <p class="text-xs text-gray-500">Remaining: 63 days</p>
                                </div>
                            </div>
                            <div class="p-4 border rounded-lg hover:border-yellow-200 hover:bg-yellow-50 transition">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-medium flex items-center">
                                        <i class="fas fa-chalkboard-teacher mr-2 text-yellow-600"></i> Conference Leave
                                    </h3>
                                    <span class="text-sm text-gray-500">10 days/year</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full progress-bar">
                                    <div class="bg-yellow-500 progress-bar rounded-full" style="width: 60%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-gray-500">Used: 6 days</p>
                                    <p class="text-xs text-gray-500">Remaining: 4 days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');

        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Chart initialization
        const ctx = document.getElementById('leaveChart').getContext('2d');
        const leaveChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [
                    {
                        label: 'Approved',
                        data: [12, 19, 15, 8, 14, 18],
                        backgroundColor: '#10B981',
                        borderRadius: 4
                    },
                    {
                        label: 'Pending',
                        data: [5, 8, 6, 10, 7, 4],
                        backgroundColor: '#F59E0B',
                        borderRadius: 4
                    },
                    {
                        label: 'Rejected',
                        data: [2, 3, 1, 4, 3, 2],
                        backgroundColor: '#EF4444',
                        borderRadius: 4
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
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1F2937',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: '#E5E7EB'
                        },
                        ticks: {
                            stepSize: 5,
                            color: '#6B7280'
                        }
                    }
                }
            }
        });

        // Mobile responsiveness
        function handleResize() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebar.classList.remove('active');
                mobileMenuBtn.classList.remove('hidden');
            } else {
                mobileMenuBtn.classList.add('hidden');
                sidebar.classList.remove('active');
                sidebar.classList.remove('collapsed');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();
    </script>
</body>
</html>
@endsection