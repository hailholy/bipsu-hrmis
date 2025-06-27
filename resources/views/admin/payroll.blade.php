<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University HRMIS - Payroll Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        .sidebar.collapsed .logo-text {
            display: none;
        }
        .sidebar.collapsed .nav-item {
            justify-content: center;
        }
        .content-area {
            transition: all 0.3s ease;
        }
        .content-area.expanded {
            margin-left: 80px;
        }
        .payroll-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .chart-container {
            height: 300px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .print-content {
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white w-64 flex flex-col no-print" id="sidebar">
            <!-- Logo -->
            <div class="p-4 flex items-center">
                <div class="bg-white p-2 rounded-lg mr-3">
                    <i class="fas fa-university text-blue-800 text-xl"></i>
                </div>
                <span class="logo-text font-bold text-xl">University HRMIS</span>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 mt-6">
                <div class="px-4 mb-8">
                    <p class="sidebar-text text-xs uppercase text-blue-200 mb-2">Main Menu</p>
                    <a href="#" class="nav-item flex items-center py-2 px-3 bg-blue-700 rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </div>
                
                <div class="px-4 mb-8">
                    <p class="sidebar-text text-xs uppercase text-blue-200 mb-2">HR Management</p>
                    <a href="#" class="nav-item flex items-center py-2 px-3 hover:bg-blue-700 rounded-lg mb-2">
                        <i class="fas fa-users mr-3"></i>
                        <span class="sidebar-text">Employees</span>
                    </a>
                    <a href="#" class="nav-item flex items-center py-2 px-3 hover:bg-blue-700 rounded-lg mb-2">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        <span class="sidebar-text">Attendance</span>
                    </a>
                    <a href="#" class="nav-item flex items-center py-2 px-3 bg-blue-900 rounded-lg mb-2">
                        <i class="fas fa-money-bill-wave mr-3"></i>
                        <span class="sidebar-text">Payroll</span>
                    </a>
                    <a href="#" class="nav-item flex items-center py-2 px-3 hover:bg-blue-700 rounded-lg">
                        <i class="fas fa-chart-line mr-3"></i>
                        <span class="sidebar-text">Reports</span>
                    </a>
                </div>
                
                <div class="px-4 mb-8">
                    <p class="sidebar-text text-xs uppercase text-blue-200 mb-2">System</p>
                    <a href="#" class="nav-item flex items-center py-2 px-3 hover:bg-blue-700 rounded-lg mb-2">
                        <i class="fas fa-cog mr-3"></i>
                        <span class="sidebar-text">Settings</span>
                    </a>
                    <a href="#" class="nav-item flex items-center py-2 px-3 hover:bg-blue-700 rounded-lg">
                        <i class="fas fa-question-circle mr-3"></i>
                        <span class="sidebar-text">Help</span>
                    </a>
                </div>
            </nav>
            
            <!-- User Profile -->
            <div class="p-4 border-t border-blue-700">
                <div class="flex items-center">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Profile" class="w-10 h-10 rounded-full mr-3">
                    <div class="sidebar-text">
                        <p class="font-semibold">Admin User</p>
                        <p class="text-xs text-blue-200">Super Admin</p>
                    </div>
                </div>
            </div>
            
            <!-- Collapse Button -->
            <div class="p-2 text-center">
                <button onclick="toggleSidebar()" class="text-blue-200 hover:text-white">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="content-area flex-1 overflow-auto" id="contentArea">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center no-print">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Payroll Management</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                        </button>
                    </div>
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-envelope"></i>
                            <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">5</span>
                        </button>
                    </div>
                    <button class="flex items-center text-gray-600 hover:text-gray-900">
                        <span class="mr-2">Logout</span>
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="p-6">
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow payroll-card transition duration-300 p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">Total Payroll</p>
                                <h3 class="text-2xl font-bold text-gray-800">$245,380</h3>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-wallet text-blue-600"></i>
                            </div>
                        </div>
                        <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i> 12% from last month</p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow payroll-card transition duration-300 p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">Employees</p>
                                <h3 class="text-2xl font-bold text-gray-800">428</h3>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                        </div>
                        <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i> 5 new hires</p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow payroll-card transition duration-300 p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">Pending Approvals</p>
                                <h3 class="text-2xl font-bold text-gray-800">18</h3>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                        </div>
                        <p class="text-red-500 text-sm mt-2"><i class="fas fa-arrow-down mr-1"></i> 2 overdue</p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow payroll-card transition duration-300 p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">Tax Deductions</p>
                                <h3 class="text-2xl font-bold text-gray-800">$38,260</h3>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-percentage text-purple-600"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm mt-2">Updated today</p>
                    </div>
                </div>
                
                <!-- Payroll Actions -->
                <div class="bg-white rounded-lg shadow mb-6 p-6 no-print">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2 md:mb-0">Payroll Actions</h2>
                        <div class="flex space-x-2">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                                <i class="fas fa-plus mr-2"></i> New Payroll Run
                            </button>
                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                                <i class="fas fa-file-export mr-2"></i> Export
                            </button>
                            <button onclick="printPayroll()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button class="bg-blue-50 hover:bg-blue-100 text-blue-800 p-4 rounded-lg flex flex-col items-center">
                            <i class="fas fa-calendar-check text-2xl mb-2"></i>
                            <span>Process Payroll</span>
                        </button>
                        <button class="bg-green-50 hover:bg-green-100 text-green-800 p-4 rounded-lg flex flex-col items-center">
                            <i class="fas fa-file-invoice-dollar text-2xl mb-2"></i>
                            <span>Generate Payslips</span>
                        </button>
                        <button class="bg-purple-50 hover:bg-purple-100 text-purple-800 p-4 rounded-lg flex flex-col items-center">
                            <i class="fas fa-hand-holding-usd text-2xl mb-2"></i>
                            <span>Bonus & Deductions</span>
                        </button>
                        <button class="bg-yellow-50 hover:bg-yellow-100 text-yellow-800 p-4 rounded-lg flex flex-col items-center">
                            <i class="fas fa-chart-pie text-2xl mb-2"></i>
                            <span>Reports</span>
                        </button>
                    </div>
                </div>
                
                <!-- Payroll Summary -->
                <div class="bg-white rounded-lg shadow mb-6 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Current Payroll Period</h2>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 mr-2">Pay Period:</span>
                            <select class="border rounded-lg px-3 py-1 text-sm">
                                <option>November 2023</option>
                                <option>October 2023</option>
                                <option>September 2023</option>
                                <option>August 2023</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Basic Salary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allowances</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deductions</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Pay</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/men/32.jpg" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Dr. John Smith</div>
                                                <div class="text-sm text-gray-500">EMP-1001</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Computer Science</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Professor</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$8,500</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">$1,200</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">$950</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">$8,750</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/women/44.jpg" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Dr. Sarah Johnson</div>
                                                <div class="text-sm text-gray-500">EMP-1002</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Mathematics</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Associate Professor</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$7,200</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">$800</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">$720</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">$7,280</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/men/67.jpg" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Prof. Michael Brown</div>
                                                <div class="text-sm text-gray-500">EMP-1003</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Physics</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Professor</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$8,500</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">$1,500</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">$1,020</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">$8,980</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/women/28.jpg" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Dr. Emily Wilson</div>
                                                <div class="text-sm text-gray-500">EMP-1004</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Biology</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Assistant Professor</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$6,500</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">$600</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">$650</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">$6,450</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="https://randomuser.me/api/portraits/men/75.jpg" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Prof. David Lee</div>
                                                <div class="text-sm text-gray-500">EMP-1005</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Chemistry</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Professor</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$8,500</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">$1,200</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">$950</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">$8,750</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">428</span> employees
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded-lg text-sm bg-gray-100 text-gray-600">Previous</button>
                            <button class="px-3 py-1 border rounded-lg text-sm bg-blue-600 text-white">1</button>
                            <button class="px-3 py-1 border rounded-lg text-sm hover:bg-gray-100">2</button>
                            <button class="px-3 py-1 border rounded-lg text-sm hover:bg-gray-100">3</button>
                            <button class="px-3 py-1 border rounded-lg text-sm hover:bg-gray-100">...</button>
                            <button class="px-3 py-1 border rounded-lg text-sm hover:bg-gray-100">42</button>
                            <button class="px-3 py-1 border rounded-lg text-sm hover:bg-gray-100">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payroll Distribution by Department</h3>
                        <div class="chart-container">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Payroll Trend</h3>
                        <div class="chart-container">
                            <canvas id="monthlyTrendChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Payroll Activities</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-check-circle text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">November payroll processed</p>
                                <p class="text-sm text-gray-500">428 employees paid totaling $245,380</p>
                                <p class="text-xs text-gray-400">Today, 10:45 AM</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-file-export text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Payroll report exported</p>
                                <p class="text-sm text-gray-500">Finance department received November payroll report</p>
                                <p class="text-xs text-gray-400">Yesterday, 3:30 PM</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-yellow-100 p-2 rounded-full mr-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Payroll approval required</p>
                                <p class="text-sm text-gray-500">5 pending approvals for November payroll</p>
                                <p class="text-xs text-gray-400">2 days ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Print Content (hidden by default) -->
    <div id="printContent" class="hidden print-content p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">University HRMIS - Payroll Report</h1>
            <p class="text-gray-600">November 2023 Payroll Summary</p>
            <p class="text-sm text-gray-500">Generated on: <span id="printDate"></span></p>
        </div>
        
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Payroll Overview</h2>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="border p-4 rounded-lg">
                    <p class="text-gray-500 text-sm">Total Employees</p>
                    <h3 class="text-2xl font-bold">428</h3>
                </div>
                <div class="border p-4 rounded-lg">
                    <p class="text-gray-500 text-sm">Total Payroll</p>
                    <h3 class="text-2xl font-bold">$245,380</h3>
                </div>
                <div class="border p-4 rounded-lg">
                    <p class="text-gray-500 text-sm">Average Salary</p>
                    <h3 class="text-2xl font-bold">$5,733</h3>
                </div>
            </div>
        </div>
        
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Top 5 Employees by Salary</h2>
            <table class="min-w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">Employee</th>
                        <th class="border px-4 py-2 text-left">Department</th>
                        <th class="border px-4 py-2 text-left">Position</th>
                        <th class="border px-4 py-2 text-left">Net Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">Dr. John Smith</td>
                        <td class="border px-4 py-2">Computer Science</td>
                        <td class="border px-4 py-2">Professor</td>
                        <td class="border px-4 py-2">$8,750</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="border px-4 py-2">Prof. Michael Brown</td>
                        <td class="border px-4 py-2">Physics</td>
                        <td class="border px-4 py-2">Professor</td>
                        <td class="border px-4 py-2">$8,750</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Prof. David Lee</td>
                        <td class="border px-4 py-2">Chemistry</td>
                        <td class="border px-4 py-2">Professor</td>
                        <td class="border px-4 py-2">$8,750</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="border px-4 py-2">Dr. Sarah Johnson</td>
                        <td class="border px-4 py-2">Mathematics</td>
                        <td class="border px-4 py-2">Associate Professor</td>
                        <td class="border px-4 py-2">$7,280</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Dr. Emily Wilson</td>
                        <td class="border px-4 py-2">Biology</td>
                        <td class="border px-4 py-2">Assistant Professor</td>
                        <td class="border px-4 py-2">$6,450</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="text-xs text-gray-500 mt-8">
            <p>Confidential - University HRMIS Payroll Report</p>
            <p>This document is automatically generated and should not be shared without authorization.</p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const contentArea = document.getElementById('contentArea');
            sidebar.classList.toggle('collapsed');
            contentArea.classList.toggle('expanded');
            
            // Change icon
            const toggleBtn = sidebar.querySelector('button');
            if (sidebar.classList.contains('collapsed')) {
                toggleBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            }
        }
        
        // Print payroll function
        function printPayroll() {
            const today = new Date();
            document.getElementById('printDate').textContent = today.toLocaleDateString() + ' ' + today.toLocaleTimeString();
            
            const printContent = document.getElementById('printContent');
            printContent.classList.remove('hidden');
            
            window.print();
            
            printContent.classList.add('hidden');
        }
        
        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Department Chart
            const deptCtx = document.getElementById('departmentChart').getContext('2d');
            const deptChart = new Chart(deptCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Computer Science', 'Mathematics', 'Physics', 'Biology', 'Chemistry', 'Other'],
                    datasets: [{
                        data: [25, 15, 20, 12, 18, 10],
                        backgroundColor: [
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B',
                            '#8B5CF6',
                            '#EC4899',
                            '#64748B'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
            
            // Monthly Trend Chart
            const trendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov'],
                    datasets: [{
                        label: 'Total Payroll ($)',
                        data: [220000, 225000, 230000, 228000, 232000, 235000, 238000, 240000, 242000, 243000, 245380],
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: '#3B82F6',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
            
            // Simulate loading data
            setTimeout(() => {
                document.querySelectorAll('.payroll-card').forEach(card => {
                    card.style.opacity = '1';
                });
            }, 300);
        });
    </script>
</body>
</html>