@foreach($employees as $employee)
<tr class="employee-row" data-id="{{ $employee->id }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
                <img class="h-10 w-10 rounded-full" src="{{ $employee->profile_photo_url }}" alt="{{ $employee->first_name }}">
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                <div class="text-sm text-gray-500">{{ $employee->email }}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $employee->employee_id }}
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
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <button class="edit-employee text-blue-600 hover:text-blue-900 mr-3" data-id="{{ $employee->id }}">Edit</button>
        <button class="delete-employee text-red-600 hover:text-red-900" data-id="{{ $employee->id }}">Delete</button>
    </td>
</tr>
@endforeach

@if($employees->hasPages())
<tr>
    <td colspan="6" class="px-6 py-4">
        {{ $employees->links() }}
    </td>
</tr>
@endif