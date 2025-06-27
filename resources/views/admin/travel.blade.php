@extends('layouts.app')

@section('title', 'Travel Management')

@section('content')
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
        .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .content-area {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

         
        
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pending Requests</p>
                                <h3 class="text-2xl font-bold mt-1">24</h3>
                            </div>
                            <div class="p-3 rounded-full bg-blue-50 text-primary">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">+2 from yesterday</p>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Approved Requests</p>
                                <h3 class="text-2xl font-bold mt-1">156</h3>
                            </div>
                            <div class="p-3 rounded-full bg-green-50 text-green-500">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">+12 this week</p>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Rejected Requests</p>
                                <h3 class="text-2xl font-bold mt-1">8</h3>
                            </div>
                            <div class="p-3 rounded-full bg-red-50 text-red-500">
                                <i class="fas fa-times-circle text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">-1 from last week</p>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Budget</p>
                                <h3 class="text-2xl font-bold mt-1">$42,580</h3>
                            </div>
                            <div class="p-3 rounded-full bg-purple-50 text-purple-500">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">+$3,200 this month</p>
                    </div>
                </div>
                
                <!-- Action Bar -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" placeholder="Search travel requests..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button class="flex items-center space-x-2 bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-plus"></i>
                                <span>New Request</span>
                            </button>
                            <button class="flex items-center space-x-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg transition">
                                <i class="fas fa-filter"></i>
                                <span>Filter</span>
                            </button>
                            <button class="flex items-center space-x-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg transition">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Navigation -->
                <div class="mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px space-x-8">
                            <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-primary text-primary">
                                All Requests
                            </button>
                            <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Pending
                            </button>
                            <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Approved
                            </button>
                            <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Rejected
                            </button>
                            <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Completed
                            </button>
                        </nav>
                    </div>
                </div>
                
                <!-- Travel Requests Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="table-responsive">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Request ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Employee
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Destination
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Purpose
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dates
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Budget
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Row 1 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #TR-2023-001
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800">
                                                    <span class="font-semibold">JD</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">John Doe</div>
                                                <div class="text-sm text-gray-500">Computer Science</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Tokyo, Japan
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        International Conference
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        May 15-20, 2023
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $2,500
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Row 2 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #TR-2023-002
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-800">
                                                    <span class="font-semibold">AS</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Alice Smith</div>
                                                <div class="text-sm text-gray-500">Physics</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Paris, France
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Research Collaboration
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Jun 5-12, 2023
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $3,200
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-gray-600 hover:text-gray-900">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Row 3 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #TR-2023-003
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-800">
                                                    <span class="font-semibold">RJ</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Robert Johnson</div>
                                                <div class="text-sm text-gray-500">Mathematics</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Berlin, Germany
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Workshop Attendance
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Apr 10-15, 2023
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $1,800
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-gray-600 hover:text-gray-900">
                                                <i class="fas fa-comment"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Row 4 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #TR-2023-004
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-800">
                                                    <span class="font-semibold">MB</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Maria Brown</div>
                                                <div class="text-sm text-gray-500">Biology</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        New York, USA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Conference Presentation
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Jul 20-25, 2023
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $2,800
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Completed
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-gray-600 hover:text-gray-900">
                                                <i class="fas fa-file-invoice"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Row 5 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #TR-2023-005
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-800">
                                                    <span class="font-semibold">DW</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">David Wilson</div>
                                                <div class="text-sm text-gray-500">Engineering</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Singapore
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Faculty Exchange
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Aug 5-20, 2023
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $4,500
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
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
                                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">24</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                    <a href="#" aria-current="page" class="z-10 bg-primary border-primary text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium">
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
                
                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Travel Request Statistics</h2>
                        <div class="h-64">
                            <!-- Chart Placeholder -->
                            <div class="w-full h-full bg-gray-100 rounded-lg flex items-center justify-center">
                                <p class="text-gray-500">Travel request chart visualization</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-800">
                                        <i class="fas fa-user text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">John Doe</p>
                                    <p class="text-sm text-gray-500">Submitted travel request to Tokyo</p>
                                    <p class="text-xs text-gray-400">2 hours ago</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-800">
                                        <i class="fas fa-check text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">System</p>
                                    <p class="text-sm text-gray-500">Alice Smith's request to Paris was approved</p>
                                    <p class="text-xs text-gray-400">1 day ago</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-800">
                                        <i class="fas fa-times text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">System</p>
                                    <p class="text-sm text-gray-500">Robert Johnson's request to Berlin was rejected</p>
                                    <p class="text-xs text-gray-400">3 days ago</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-800">
                                        <i class="fas fa-plane text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Maria Brown</p>
                                    <p class="text-sm text-gray-500">Completed travel to New York</p>
                                    <p class="text-xs text-gray-400">1 week ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar collapse/expand
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content-area');
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
        }

        // Toggle mobile sidebar
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        // Initialize with sidebar collapsed on mobile
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth < 768) {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('show');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>
@endsection