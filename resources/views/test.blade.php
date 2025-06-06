@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="p-6" x-data="employeeModule()" x-cloak>
    <!-- Employee Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="w-full md:w-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    x-model="searchQuery" 
                    @input="filterEmployees()"
                    type="text" 
                    class="block w-full md:w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                    placeholder="Search employees..."
                >
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <select 
                x-model="selectedDepartment" 
                @change="filterEmployees()"
                class="block w-full md:w-48 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
            >
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
            <select 
                x-model="selectedStatus" 
                @change="filterEmployees()"
                class="block w-full md:w-40 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
            >
                <option value="">All Status</option>
                <option>Active</option>
                <option>On Leave</option>
                <option>Terminated</option>
                <option>Suspended</option>
            </select>
            <button 
                @click="openAddEmployeeModal()"
                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center justify-center"
            >
                <i class="fas fa-plus mr-2"></i> Add Employee
            </button>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="flex mb-6 border-b border-gray-200">
        <button 
            @click="viewMode = 'list'" 
            :class="{'border-b-2 border-blue-500 text-blue-600': viewMode === 'list'}" 
            class="mr-4 py-2 px-1 text-sm font-medium"
        >
            <i class="fas fa-list mr-2"></i> List View
        </button>
        <button 
            @click="viewMode = 'grid'" 
            :class="{'border-b-2 border-blue-500 text-blue-600': viewMode === 'grid'}" 
            class="mr-4 py-2 px-1 text-sm font-medium"
        >
            <i class="fas fa-th-large mr-2"></i> Grid View
        </button>
    </div>

    <!-- Employee List View -->
    <div x-show="viewMode === 'list'" class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortEmployees('id')">
                            Employee ID
                            <i class="fas fa-sort ml-1" :class="{'fa-sort-up': sortColumn === 'id' && sortDirection === 'asc', 'fa-sort-down': sortColumn === 'id' && sortDirection === 'desc'}"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortEmployees('name')">
                            Name
                            <i class="fas fa-sort ml-1" :class="{'fa-sort-up': sortColumn === 'name' && sortDirection === 'asc', 'fa-sort-down': sortColumn === 'name' && sortDirection === 'desc'}"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortEmployees('department')">
                            Department
                            <i class="fas fa-sort ml-1" :class="{'fa-sort-up': sortColumn === 'department' && sortDirection === 'asc', 'fa-sort-down': sortColumn === 'department' && sortDirection === 'desc'}"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortEmployees('position')">
                            Position
                            <i class="fas fa-sort ml-1" :class="{'fa-sort-up': sortColumn === 'position' && sortDirection === 'asc', 'fa-sort-down': sortColumn === 'position' && sortDirection === 'desc'}"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortEmployees('status')">
                            Status
                            <i class="fas fa-sort ml-1" :class="{'fa-sort-up': sortColumn === 'status' && sortDirection === 'asc', 'fa-sort-down': sortColumn === 'status' && sortDirection === 'desc'}"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="employee in filteredEmployees" :key="employee.id">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="employee.employee_id"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" :src="employee.profile_photo_url" :alt="employee.first_name + ' ' + employee.last_name">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="employee.first_name + ' ' + employee.last_name"></div>
                                        <div class="text-sm text-gray-500" x-text="employee.email"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="employee.department"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="employee.role"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                    :class="{
                                        'bg-green-100 text-green-800': employee.status === 'Active',
                                        'bg-yellow-100 text-yellow-800': employee.status === 'On Leave',
                                        'bg-red-100 text-red-800': employee.status === 'Terminated',
                                        'bg-gray-100 text-gray-800': employee.status === 'Suspended'
                                    }"
                                    x-text="employee.status || 'Active'"
                                ></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button 
                                    @click="viewEmployee(employee.id)"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button 
                                    @click="editEmployee(employee.id)"
                                    class="text-yellow-600 hover:text-yellow-900 mr-3"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    @click="confirmDeleteEmployee(employee.id)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredEmployees.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No employees found matching your criteria.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
            <div class="flex-1 flex justify-between sm:hidden">
                <button 
                    @click="currentPage = Math.max(1, currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Previous
                </button>
                <button 
                    @click="currentPage = Math.min(totalPages, currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium" x-text="(currentPage - 1) * pageSize + 1"></span> to 
                        <span class="font-medium" x-text="Math.min(currentPage * pageSize, filteredEmployees.length)"></span> of 
                        <span class="font-medium" x-text="filteredEmployees.length"></span> employees
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button 
                            @click="currentPage = 1"
                            :disabled="currentPage === 1"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        >
                            <span class="sr-only">First</span>
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                        <button 
                            @click="currentPage = Math.max(1, currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        >
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <template x-for="page in visiblePages" :key="page">
                            <button 
                                @click="currentPage = page"
                                :class="{'z-10 bg-blue-50 border-blue-500 text-blue-600': currentPage === page}"
                                class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                x-text="page"
                            ></button>
                        </template>
                        <button 
                            @click="currentPage = Math.min(totalPages, currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        >
                            <span class="sr-only">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <button 
                            @click="currentPage = totalPages"
                            :disabled="currentPage === totalPages"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        >
                            <span class="sr-only">Last</span>
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Grid View -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="employee in filteredEmployees" :key="employee.id">
            <div class="employee-card bg-white rounded-lg shadow overflow-hidden transition duration-300 ease-in-out">
                <div class="p-4 border-b">
                    <div class="flex items-center">
                        <img class="w-16 h-16 rounded-full mr-4" :src="employee.profile_photo_url" :alt="employee.first_name + ' ' + employee.last_name">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900" x-text="employee.first_name + ' ' + employee.last_name"></h3>
                            <p class="text-sm text-gray-500" x-text="employee.role"></p>
                            <span 
                                class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                :class="{
                                    'bg-green-100 text-green-800': employee.status === 'Active',
                                    'bg-yellow-100 text-yellow-800': employee.status === 'On Leave',
                                    'bg-red-100 text-red-800': employee.status === 'Terminated',
                                    'bg-gray-100 text-gray-800': employee.status === 'Suspended'
                                }"
                                x-text="employee.status || 'Active'"
                            ></span>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="fas fa-id-card mr-2"></i>
                        <span x-text="employee.employee_id"></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="fas fa-building mr-2"></i>
                        <span x-text="employee.department"></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="fas fa-envelope mr-2"></i>
                        <span x-text="employee.email"></span>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-2">
                    <button 
                        @click="viewEmployee(employee.id)"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none"
                    >
                        <i class="fas fa-eye mr-1"></i> View
                    </button>
                    <button 
                        @click="editEmployee(employee.id)"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none"
                    >
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button 
                        @click="confirmDeleteEmployee(employee.id)"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none"
                    >
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </div>
            </div>
        </template>
        <template x-if="filteredEmployees.length === 0">
            <div class="col-span-full text-center py-10">
                <i class="fas fa-users-slash text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500">No employees found matching your criteria.</p>
            </div>
        </template>
    </div>

    <!-- Add/Edit Employee Modal -->
    <div 
        x-show="isEmployeeModalOpen" 
        @keydown.escape="closeEmployeeModal()"
        class="modal fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    >
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-semibold" x-text="isEditing ? 'Edit Employee' : 'Add New Employee'"></h3>
                <button @click="closeEmployeeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-4">
                <form @submit.prevent="saveEmployee()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Personal Information -->
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Personal Information</h4>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input 
                                x-model="currentEmployee.first_name"
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input 
                                x-model="currentEmployee.last_name"
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input 
                                x-model="currentEmployee.email"
                                type="email" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required
                            >
                        </div>
                        
                        <!-- Employment Information -->
                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Employment Information</h4>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID *</label>
                            <input 
                                x-model="currentEmployee.employee_id"
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required
                                :readonly="isEditing"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                            <select 
                                x-model="currentEmployee.department"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required
                            >
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                            <select 
                                x-model="currentEmployee.role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                                required
                            >
                                <option value="">Select Position</option>
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select 
                                x-model="currentEmployee.category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                            >
                                <option value="staff">Staff</option>
                                <option value="faculty">Faculty</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button 
                            type="button" 
                            @click="closeEmployeeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Save Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div 
        x-show="isDeleteModalOpen" 
        @keydown.escape="closeDeleteModal()"
        class="modal fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    >
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-semibold">Confirm Deletion</h3>
                <button @click="closeDeleteModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-4">
                <p class="text-gray-700">Are you sure you want to delete employee <span class="font-semibold" x-text="employeeToDeleteName"></span>? This action cannot be undone.</p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        @click="closeDeleteModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="deleteEmployee()"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        Delete Employee
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    function employeeModule() {
        return {
            // Data
            employees: @json($employees->map(function($emp) {
                return [
                    'id' => $emp->id,
                    'employee_id' => $emp->employee_id,
                    'first_name' => $emp->first_name,
                    'last_name' => $emp->last_name,
                    'email' => $emp->email,
                    'profile_photo_url' => $emp->profile_photo_url,
                    'department' => $emp->department,
                    'role' => $emp->role,
                    'status' => 'Active',
                    'category' => $emp->category ?? 'staff',
                    'created_at' => $emp->created_at->toDateTimeString()
                ];
            }),
            departments: @json($departments),
            filteredEmployees: [],
            currentEmployee: {
                id: '',
                employee_id: '',
                first_name: '',
                last_name: '',
                email: '',
                department: '',
                role: '',
                category: 'staff'
            },
            employeeToDelete: null,
            employeeToDeleteName: '',
            searchQuery: '',
            selectedDepartment: '',
            selectedStatus: '',
            viewMode: 'list',
            isEmployeeModalOpen: false,
            isDeleteModalOpen: false,
            isEditing: false,
            sortColumn: 'id',
            sortDirection: 'asc',
            pageSize: 8,
            currentPage: 1,
            visiblePages: [1, 2, 3, 4, 5],

            // Methods
            init() {
                this.filterEmployees();
            },

            filterEmployees() {
                this.filteredEmployees = this.employees.filter(employee => {
                    const matchesSearch = 
                        `${employee.first_name} ${employee.last_name}`.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        employee.email.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        employee.employee_id.toLowerCase().includes(this.searchQuery.toLowerCase());
                    
                    const matchesDepartment = 
                        !this.selectedDepartment || 
                        employee.department === this.selectedDepartment;
                    
                    const matchesStatus = 
                        !this.selectedStatus || 
                        employee.status === this.selectedStatus;
                    
                    return matchesSearch && matchesDepartment && matchesStatus;
                });

                this.sortEmployees(this.sortColumn, false);
                this.currentPage = 1;
                this.updateVisiblePages();
            },

            sortEmployees(column, updateDirection = true) {
                if (updateDirection) {
                    if (this.sortColumn === column) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortColumn = column;
                        this.sortDirection = 'asc';
                    }
                }

                this.filteredEmployees.sort((a, b) => {
                    let valueA, valueB;

                    switch (column) {
                        case 'id':
                            valueA = a.employee_id;
                            valueB = b.employee_id;
                            break;
                        case 'name':
                            valueA = `${a.last_name}, ${a.first_name}`;
                            valueB = `${b.last_name}, ${b.first_name}`;
                            break;
                        case 'department':
                            valueA = a.department;
                            valueB = b.department;
                            break;
                        case 'position':
                            valueA = a.role;
                            valueB = b.role;
                            break;
                        case 'status':
                            valueA = a.status;
                            valueB = b.status;
                            break;
                        default:
                            valueA = a[column];
                            valueB = b[column];
                    }

                    if (valueA < valueB) {
                        return this.sortDirection === 'asc' ? -1 : 1;
                    }
                    if (valueA > valueB) {
                        return this.sortDirection === 'asc' ? 1 : -1;
                    }
                    return 0;
                });
            },

            get paginatedEmployees() {
                const start = (this.currentPage - 1) * this.pageSize;
                const end = start + this.pageSize;
                return this.filteredEmployees.slice(start, end);
            },

            get totalPages() {
                return Math.ceil(this.filteredEmployees.length / this.pageSize);
            },

            updateVisiblePages() {
                const halfRange = 2;
                let start = Math.max(1, this.currentPage - halfRange);
                let end = Math.min(this.totalPages, this.currentPage + halfRange);

                if (this.currentPage - halfRange < 1) {
                    end = Math.min(2 * halfRange + 1, this.totalPages);
                }

                if (this.currentPage + halfRange > this.totalPages) {
                    start = Math.max(1, this.totalPages - 2 * halfRange);
                }

                this.visiblePages = [];
                for (let i = start; i <= end; i++) {
                    this.visiblePages.push(i);
                }
            },

            openAddEmployeeModal() {
                this.isEditing = false;
                this.currentEmployee = {
                    id: '',
                    employee_id: `EMP-${Math.floor(1000 + Math.random() * 9000)}`,
                    first_name: '',
                    last_name: '',
                    email: '',
                    department: '',
                    role: '',
                    category: 'staff'
                };
                this.isEmployeeModalOpen = true;
            },

            editEmployee(id) {
                const employee = this.employees.find(e => e.id === id);
                if (employee) {
                    this.currentEmployee = JSON.parse(JSON.stringify(employee));
                    this.isEditing = true;
                    this.isEmployeeModalOpen = true;
                }
            },

            saveEmployee() {
                const url = this.isEditing 
                    ? `/employees/${this.currentEmployee.id}`
                    : '/employees';
                    
                const method = this.isEditing ? 'PUT' : 'POST';
                
                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.currentEmployee)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'An error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            },

            closeEmployeeModal() {
                this.isEmployeeModalOpen = false;
            },

            confirmDeleteEmployee(id) {
                const employee = this.employees.find(e => e.id === id);
                if (employee) {
                    this.employeeToDelete = id;
                    this.employeeToDeleteName = `${employee.first_name} ${employee.last_name}`;
                    this.isDeleteModalOpen = true;
                }
            },

            deleteEmployee() {
                if (!this.employeeToDelete) return;

                fetch(`/employees/${this.employeeToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'An error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            },

            closeDeleteModal() {
                this.isDeleteModalOpen = false;
                this.employeeToDelete = null;
                this.employeeToDeleteName = '';
            }
        };
    }
</script>
@endpush
@endsection