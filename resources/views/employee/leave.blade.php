<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles */
        .leave-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .leave-status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .calendar-day {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
        }
        
        .calendar-day.selected {
            background-color: #3b82f6;
            color: white;
        }
        
        .calendar-day.disabled {
            color: #d1d5db;
            cursor: not-allowed;
        }
        
        .calendar-day.leave-day {
            background-color: #93c5fd;
            color: white;
        }
        
        .smooth-transition {
            transition: all 0.3s ease;
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
        
        /* Loading spinner */
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
        
        /* Modal animation */
        .modal-enter {
            opacity: 0;
            transform: translateY(-20px);
        }
        
        .modal-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        
        .modal-exit {
            opacity: 1;
            transform: translateY(0);
        }
        
        .modal-exit-active {
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Main Content Area -->
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Leave Management</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="notification-btn" class="p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="User profile">
                        <span class="text-sm font-medium text-gray-700">Dr. Sarah Johnson</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6">
            <div class="max-w-7xl mx-auto">
                <!-- User Info and Leave Balance Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <!-- User Information Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Employee Information</p>
                                <h3 class="text-xl font-bold text-gray-800">Dr. Sarah Johnson</h3>
                                <p class="text-sm text-gray-600 mt-1">Computer Science Department</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-user-graduate text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-500">Employee ID: <span class="font-medium">FAC-2020-001</span></p>
                            <p class="text-sm text-gray-500 mt-1">Position: <span class="font-medium">Associate Professor</span></p>
                        </div>
                    </div>

                    <!-- Annual Leave Balance -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Annual Leave</p>
                                <h3 class="text-2xl font-bold text-gray-800">18 <span class="text-lg">days</span></h3>
                                <p class="text-sm text-gray-600 mt-1">Remaining this year</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-sun text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Used 6 of 24 days</p>
                        </div>
                    </div>

                    <!-- Sick Leave Balance -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Sick Leave</p>
                                <h3 class="text-2xl font-bold text-gray-800">Unlimited</h3>
                                <p class="text-sm text-gray-600 mt-1">With medical certificate</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-heartbeat text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-500">Used this year: <span class="font-medium">4 days</span></p>
                        </div>
                    </div>

                    <!-- Other Leave Balance -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Other Leave</p>
                                <h3 class="text-2xl font-bold text-gray-800">5 <span class="text-lg">days</span></h3>
                                <p class="text-sm text-gray-600 mt-1">Remaining this year</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-calendar-alt text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-500">Includes: <span class="font-medium">Compassionate, Study, etc.</span></p>
                        </div>
                    </div>
                </div>

                <!-- Leave Application Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Leave Types and Application Form -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Leave Types -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Available Leave Types</h2>
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Annual Leave -->
                                <div class="leave-type-card bg-blue-50 border border-blue-100 rounded-lg p-4 cursor-pointer smooth-transition" onclick="selectLeaveType('annual')">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-medium text-blue-800">Annual Leave</h3>
                                            <p class="text-sm text-blue-600">Paid time off for personal reasons</p>
                                        </div>
                                        <div class="bg-blue-100 p-2 rounded-full">
                                            <i class="fas fa-sun text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-xs text-blue-500">
                                        <span class="font-medium">18 days remaining</span> • Max 10 consecutive days
                                    </div>
                                </div>

                                <!-- Sick Leave -->
                                <div class="leave-type-card bg-green-50 border border-green-100 rounded-lg p-4 cursor-pointer smooth-transition" onclick="selectLeaveType('sick')">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-medium text-green-800">Sick Leave</h3>
                                            <p class="text-sm text-green-600">For illness or medical appointments</p>
                                        </div>
                                        <div class="bg-green-100 p-2 rounded-full">
                                            <i class="fas fa-heartbeat text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-xs text-green-500">
                                        <span class="font-medium">Unlimited</span> • Requires medical certificate after 3 days
                                    </div>
                                </div>

                                <!-- Compassionate Leave -->
                                <div class="leave-type-card bg-purple-50 border border-purple-100 rounded-lg p-4 cursor-pointer smooth-transition" onclick="selectLeaveType('compassionate')">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-medium text-purple-800">Compassionate Leave</h3>
                                            <p class="text-sm text-purple-600">For bereavement or family emergencies</p>
                                        </div>
                                        <div class="bg-purple-100 p-2 rounded-full">
                                            <i class="fas fa-hands-helping text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-xs text-purple-500">
                                        <span class="font-medium">5 days/year</span> • Requires documentation
                                    </div>
                                </div>

                                <!-- Study Leave -->
                                <div class="leave-type-card bg-indigo-50 border border-indigo-100 rounded-lg p-4 cursor-pointer smooth-transition" onclick="selectLeaveType('study')">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-medium text-indigo-800">Study Leave</h3>
                                            <p class="text-sm text-indigo-600">For academic research or conferences</p>
                                        </div>
                                        <div class="bg-indigo-100 p-2 rounded-full">
                                            <i class="fas fa-graduation-cap text-indigo-600"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-xs text-indigo-500">
                                        <span class="font-medium">10 days/year</span> • Requires approval
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Leave Request -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Leave Request</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                                    <select id="quick-leave-type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select leave type</option>
                                        <option value="annual">Annual Leave</option>
                                        <option value="sick">Sick Leave</option>
                                        <option value="compassionate">Compassionate Leave</option>
                                        <option value="study">Study Leave</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" id="quick-leave-date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                                    <select id="quick-leave-duration" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="full">Full Day</option>
                                        <option value="morning">Morning Only</option>
                                        <option value="afternoon">Afternoon Only</option>
                                    </select>
                                </div>
                                <button onclick="submitQuickLeave()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center justify-center transition duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i> Submit Request
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Leave Calendar and Applications -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Leave Calendar -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                <h2 class="text-lg font-semibold text-gray-800">Leave Calendar</h2>
                                <div class="flex items-center space-x-2 mt-2 md:mt-0">
                                    <button onclick="prevMonth()" class="p-2 rounded-full hover:bg-gray-100">
                                        <i class="fas fa-chevron-left text-gray-600"></i>
                                    </button>
                                    <h3 id="current-month-year" class="font-medium text-gray-700">June 2023</h3>
                                    <button onclick="nextMonth()" class="p-2 rounded-full hover:bg-gray-100">
                                        <i class="fas fa-chevron-right text-gray-600"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-xs font-medium text-gray-500 uppercase py-2">Sun</th>
                                            <th class="text-center text-xs font-medium text-gray-500 uppercase py-2">Mon</th>
                                            <th class="text-center text-xs font-medium text-gray-500 uppercase py-2">Tue</th>
                                            <th class="text-center text-xs font-medium text-gray-500 uppercase py-2">Wed</th>
                                            <th class="text-center text-xs font-medium text-gray-500 uppercase py-2">Thu</th>
                                            <th class="text-center text-xs font-medium text-gray-500 uppercase py-2">Fri</th>
                                            <th class="text-center text-xs font-medium text-gray-500 uppercase py-2">Sat</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calendar-body">
                                        <!-- Calendar will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 flex flex-wrap gap-2">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-1"></div>
                                    <span class="text-xs text-gray-600">Planned Leave</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-1"></div>
                                    <span class="text-xs text-gray-600">Approved Leave</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-1"></div>
                                    <span class="text-xs text-gray-600">Pending Approval</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-1"></div>
                                    <span class="text-xs text-gray-600">Rejected Leave</span>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Applications -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-800">My Leave Applications</h2>
                                <div class="mt-2 md:mt-0">
                                    <button onclick="openNewLeaveModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition duration-200">
                                        <i class="fas fa-plus mr-2"></i> New Application
                                    </button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                                        <!-- Sample leave applications -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-sun text-blue-600 text-sm"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">Annual Leave</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Jun 12 - Jun 16, 2023</div>
                                                <div class="text-sm text-gray-500">5 days</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Full Days</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                                <button class="text-gray-600 hover:text-gray-900"><i class="fas fa-print"></i></button>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-heartbeat text-green-600 text-sm"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">Sick Leave</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">May 8, 2023</div>
                                                <div class="text-sm text-gray-500">1 day</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Morning Only</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                                <button class="text-gray-600 hover:text-gray-900"><i class="fas fa-print"></i></button>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-graduation-cap text-purple-600 text-sm"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">Study Leave</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Jul 3 - Jul 7, 2023</div>
                                                <div class="text-sm text-gray-500">5 days</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Full Days</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                                <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-hands-helping text-indigo-600 text-sm"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">Compassionate Leave</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Apr 15 - Apr 17, 2023</div>
                                                <div class="text-sm text-gray-500">3 days</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Full Days</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                                <button class="text-gray-600 hover:text-gray-900"><i class="fas fa-print"></i></button>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-heartbeat text-green-600 text-sm"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">Sick Leave</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Mar 22, 2023</div>
                                                <div class="text-sm text-gray-500">1 day</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Full Day</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                                <button class="text-gray-600 hover:text-gray-900"><i class="fas fa-print"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                                <div class="flex-1 flex justify-between sm:hidden">
                                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Previous
                                    </a>
                                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Next
                                    </a>
                                </div>
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">12</span> results
                                        </p>
                                    </div>
                                    <div>
                                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Previous</span>
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                            <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                1
                                            </a>
                                            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                2
                                            </a>
                                            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                3
                                            </a>
                                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                                ...
                                            </span>
                                            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                8
                                            </a>
                                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Next</span>
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- New Leave Application Modal -->
    <div id="new-leave-modal" class="fixed inset-0 overflow-y-auto z-50 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    New Leave Application
                                </h3>
                                <button onclick="closeNewLeaveModal()" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="mt-2">
                                <form id="leave-application-form">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="leave-type" class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                                            <select id="leave-type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                <option value="">Select leave type</option>
                                                <option value="annual">Annual Leave</option>
                                                <option value="sick">Sick Leave</option>
                                                <option value="compassionate">Compassionate Leave</option>
                                                <option value="study">Study Leave</option>
                                                <option value="maternity">Maternity Leave</option>
                                                <option value="paternity">Paternity Leave</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="leave-sub-type" class="block text-sm font-medium text-gray-700 mb-1">Sub Type (if applicable)</label>
                                            <select id="leave-sub-type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Not applicable</option>
                                                <option value="medical">Medical Appointment</option>
                                                <option value="family">Family Emergency</option>
                                                <option value="conference">Academic Conference</option>
                                                <option value="research">Research Work</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="start-date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                            <input type="date" id="start-date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        </div>
                                        
                                        <div>
                                            <label for="end-date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                            <input type="date" id="end-date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        </div>
                                        
                                        <div>
                                            <label for="duration-type" class="block text-sm font-medium text-gray-700 mb-1">Duration Type</label>
                                            <select id="duration-type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                <option value="full">Full Day</option>
                                                <option value="morning">Morning Only</option>
                                                <option value="afternoon">Afternoon Only</option>
                                                <option value="hours">Specific Hours</option>
                                            </select>
                                        </div>
                                        
                                        <div id="hours-container" class="hidden">
                                            <label for="hours" class="block text-sm font-medium text-gray-700 mb-1">Hours</label>
                                            <input type="number" id="hours" min="1" max="8" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        
                                        <div class="md:col-span-2">
                                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                                            <textarea id="reason" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Briefly explain the reason for your leave" required></textarea>
                                        </div>
                                        
                                        <div class="md:col-span-2">
                                            <label for="contact-details" class="block text-sm font-medium text-gray-700 mb-1">Contact During Leave</label>
                                            <input type="text" id="contact-details" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Phone number or email where you can be reached">
                                        </div>
                                        
                                        <div class="md:col-span-2">
                                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Attachment (if required)</label>
                                            <div class="mt-1 flex items-center">
                                                <input type="file" id="attachment" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                                <label for="attachment" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <i class="fas fa-paperclip mr-2"></i> Choose File
                                                </label>
                                                <span id="file-name" class="ml-2 text-sm text-gray-500">No file chosen</span>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">PDF, JPG, or PNG (Max 5MB)</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" onclick="closeNewLeaveModal()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Cancel
                                        </button>
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-paper-plane mr-2"></i> Submit Application
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Leave Application Modal -->
    <div id="view-leave-modal" class="fixed inset-0 overflow-y-auto z-50 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Leave Application Details
                                </h3>
                                <button onclick="closeViewLeaveModal()" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="mt-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <p class="text-sm text-gray-500">Leave Type</p>
                                        <p class="font-medium" id="view-leave-type">Annual Leave</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Status</p>
                                        <p class="font-medium">
                                            <span id="view-leave-status" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Start Date</p>
                                        <p class="font-medium" id="view-start-date">Jun 12, 2023</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">End Date</p>
                                        <p class="font-medium" id="view-end-date">Jun 16, 2023</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Duration</p>
                                        <p class="font-medium" id="view-duration">5 days</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Applied On</p>
                                        <p class="font-medium" id="view-applied-date">Jun 5, 2023</p>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-500">Reason</p>
                                        <p class="font-medium" id="view-reason">Family vacation</p>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-500">Contact During Leave</p>
                                        <p class="font-medium" id="view-contact">sarah.johnson@email.com</p>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-500">Attachment</p>
                                        <div id="view-attachment" class="mt-1">
                                            <a href="#" class="text-blue-600 hover:text-blue-800 flex items-center">
                                                <i class="fas fa-file-pdf mr-2 text-red-500"></i> vacation_approval.pdf
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="approver-section" class="border-t border-gray-200 pt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Approval Details</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Approved By</p>
                                            <p class="font-medium" id="view-approved-by">Dr. Michael Brown</p>
                                        </div>
                                        
                                        <div>
                                            <p class="text-sm text-gray-500">Approved On</p>
                                            <p class="font-medium" id="view-approved-date">Jun 7, 2023</p>
                                        </div>
                                        
                                        <div class="md:col-span-2">
                                            <p class="text-sm text-gray-500">Approver Comments</p>
                                            <p class="font-medium" id="view-approver-comments">Approved. Please ensure all your classes are covered.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button onclick="closeViewLeaveModal()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Close
                                    </button>
                                    <button class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-print mr-2"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 hidden">
        <div class="px-4 py-2 rounded-md shadow-md text-white bg-green-500 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span id="toast-message">Leave application submitted successfully!</span>
        </div>
    </div>

    <script>
        // Current date for the calendar
        let currentDate = new Date();
        
        // Initialize the calendar
        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar();
            
            // Set up event listeners
            document.getElementById('duration-type').addEventListener('change', function() {
                if (this.value === 'hours') {
                    document.getElementById('hours-container').classList.remove('hidden');
                } else {
                    document.getElementById('hours-container').classList.add('hidden');
                }
            });
            
            document.getElementById('attachment').addEventListener('change', function(e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
                document.getElementById('file-name').textContent = fileName;
            });
            
            // Form submission
            document.getElementById('leave-application-form').addEventListener('submit', function(e) {
                e.preventDefault();
                submitLeaveApplication();
            });
        });
        
        // Calendar functions
        function renderCalendar() {
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const currentMonth = currentDate.getMonth();
            const currentYear = currentDate.getFullYear();
            
            // Update the month/year display
            document.getElementById('current-month-year').textContent = `${monthNames[currentMonth]} ${currentYear}`;
            
            // Get first day of month and total days in month
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            
            // Get days from previous month to display
            const prevMonthDays = new Date(currentYear, currentMonth, 0).getDate();
            
            // Create calendar body
            let calendarBody = document.getElementById('calendar-body');
            calendarBody.innerHTML = '';
            
            let date = 1;
            let nextMonthDate = 1;
            
            // Create calendar rows
            for (let i = 0; i < 6; i++) {
                // Stop creating rows if all days have been rendered
                if (date > daysInMonth) break;
                
                // Create row
                const row = document.createElement('tr');
                
                // Create cells for each day of the week
                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement('td');
                    cell.className = 'py-1';
                    
                    // Add day number to cell
                    if (i === 0 && j < firstDay) {
                        // Days from previous month
                        const prevDate = prevMonthDays - (firstDay - j - 1);
                        const dayDiv = document.createElement('div');
                        dayDiv.className = 'calendar-day disabled';
                        dayDiv.textContent = prevDate;
                        cell.appendChild(dayDiv);
                    } else if (date > daysInMonth) {
                        // Days from next month
                        const dayDiv = document.createElement('div');
                        dayDiv.className = 'calendar-day disabled';
                        dayDiv.textContent = nextMonthDate++;
                        cell.appendChild(dayDiv);
                    } else {
                        // Days in current month
                        const dayDiv = document.createElement('div');
                        dayDiv.className = 'calendar-day';
                        dayDiv.textContent = date;
                        
                        // Mark today
                        const today = new Date();
                        if (date === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
                            dayDiv.classList.add('selected');
                        }
                        
                        // Sample leave days (in a real app, this would come from the database)
                        if ((currentMonth === 5 && date === 12) || (currentMonth === 5 && date === 13) || 
                            (currentMonth === 5 && date === 14) || (currentMonth === 5 && date === 15) || 
                            (currentMonth === 5 && date === 16)) {
                            dayDiv.classList.add('leave-day');
                        }
                        
                        // Add click event to select day
                        dayDiv.addEventListener('click', function() {
                            // In a real app, this would open the leave application form with the selected date
                            console.log(`Selected date: ${currentMonth + 1}/${date}/${currentYear}`);
                        });
                        
                        cell.appendChild(dayDiv);
                        date++;
                    }
                    
                    row.appendChild(cell);
                }
                
                calendarBody.appendChild(row);
            }
        }
        
        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }
        
        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }
        
        // Modal functions
        function openNewLeaveModal() {
            document.getElementById('new-leave-modal').classList.remove('hidden');
        }
        
        function closeNewLeaveModal() {
            document.getElementById('new-leave-modal').classList.add('hidden');
            // Reset form
            document.getElementById('leave-application-form').reset();
            document.getElementById('file-name').textContent = 'No file chosen';
            document.getElementById('hours-container').classList.add('hidden');
        }
        
        function openViewLeaveModal() {
            document.getElementById('view-leave-modal').classList.remove('hidden');
        }
        
        function closeViewLeaveModal() {
            document.getElementById('view-leave-modal').classList.add('hidden');
        }
        
        // Leave type selection
        function selectLeaveType(type) {
            // In a real app, this would pre-fill the leave type in the application form
            console.log(`Selected leave type: ${type}`);
            openNewLeaveModal();
            document.getElementById('leave-type').value = type;
        }
        
        // Quick leave submission
        function submitQuickLeave() {
            const type = document.getElementById('quick-leave-type').value;
            const date = document.getElementById('quick-leave-date').value;
            const duration = document.getElementById('quick-leave-duration').value;
            
            if (!type || !date) {
                showToast('Please select leave type and date', 'error');
                return;
            }
            
            // In a real app, this would submit to the server
            console.log(`Quick leave submitted: ${type} on ${date} (${duration})`);
            showToast('Quick leave request submitted successfully!');
            
            // Reset form
            document.getElementById('quick-leave-type').value = '';
            document.getElementById('quick-leave-date').value = '';
            document.getElementById('quick-leave-duration').value = 'full';
        }
        
        // Full leave application submission
        function submitLeaveApplication() {
            // In a real app, this would validate and submit to the server
            const formData = new FormData(document.getElementById('leave-application-form'));
            const leaveData = {};
            
            for (let [key, value] of formData.entries()) {
                leaveData[key] = value;
            }
            
            console.log('Leave application submitted:', leaveData);
            showToast('Leave application submitted successfully!');
            closeNewLeaveModal();
        }
        
        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            // Set message and style based on type
            toastMessage.textContent = message;
            
            if (type === 'error') {
                toast.firstElementChild.className = 'px-4 py-2 rounded-md shadow-md text-white bg-red-500 flex items-center';
                toast.firstElementChild.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i><span id="toast-message">${message}</span>`;
            } else {
                toast.firstElementChild.className = 'px-4 py-2 rounded-md shadow-md text-white bg-green-500 flex items-center';
                toast.firstElementChild.innerHTML = `<i class="fas fa-check-circle mr-2"></i><span id="toast-message">${message}</span>`;
            }
            
            // Show toast
            toast.classList.remove('hidden');
            
            // Hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    </script>
</body>
</html>