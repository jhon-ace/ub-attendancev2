@php
    session(['selectedSchool' => $selectedSchool]);
    
@endphp
@if (Auth::user()->hasRole('admin')) 

    <div>
        @if (session('success'))
            <x-sweetalert type="success" :message="session('success')" />
        @endif

        @if (session('info'))
            <x-sweetalert type="info" :message="session('info')" />
        @endif

        @if (session('error'))
            <x-sweetalert type="error" :message="session('error')" />
        @endif

        <div class="flex justify-between mb-4 sm:-mt-4">
            <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Admin / Manage Department</div>
        </div>
        <div class="flex flex-col md:flex-row items-start md:items-center md:justify-start">
            <!-- Dropdown and Delete Button -->
            <div class="flex items-center w-full md:w-auto">
                <!-- <label for="school_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">School Name:</label> -->
                <div class="col-span-3">
                    @if($schoolToShow)
                            <p class="w-64 text-black mt-10 text-sm mb-1">School: <span class="text-red-500 ml-2 font-bold uppercase">{{ $schoolToShow->abbreviation }}</span></p>
                    @endif
                </div>
            </div>
        </div>
        <hr class="border-gray-200 my-4">
        @if($schoolToShow)
        <!-- <form action="{{ route('admin.csv.import.department') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center space-x-2 mb-2">
                            <label for="csv_file" class="text-sm font-medium text-gray-700">Import CSV file:</label>
                            <div class="relative">
                                <input id="csv_file" type="file" name="csv_file" accept=".csv,.txt" class="hidden" required>
                                <label for="csv_file" class="cursor-pointer bg-white border border-gray-300 text-gray-700 rounded-md py-1 px-3 inline-block text-sm hover:bg-gray-50 hover:border-blue-500">
                                    <i class="fa-solid fa-file-import mr-1"></i> Browse
                                </label>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                                Import
                            </button>
                        </div>
                    </form> -->
        <div class="flex justify-between">
            <p class="text-black mt-2 text-sm mb-4">Selected School Name: <text class="uppercase text-red-500">{{ $schoolToShow->abbreviation }}</text></p>
            <div x-data="{ open: false }">
                <button @click="open = true" class="bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                    <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Department
                </button>
                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div @click.away="open = true" class="w-[30%] max-h-[90%]  bg-white p-6 rounded-lg shadow-lg  mx-auto overflow-y-auto">
                        <div class="flex justify-between items-center pb-3">
                            <p class="text-xl font-bold">Add Department</p>
                            <button @click="open = false" class=" text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                        </div>
                        <div class="mb-4">
                            <form action="{{ route('admin.department.store') }}" method="POST" class="">
                            <x-caps-lock-detector />
                                @csrf

                                    <div class="mb-2">
                                        <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School Name: </label>
                                        <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                <option value="{{ $schoolToShow->id }}">{{ $schoolToShow->abbreviation }}</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                    </div>
                                    <div class="mb-2">
                                        <label for="department_id" class="block text-gray-700 text-md font-bold mb-2">Department School ID</label>
                                        <input type="text" name="department_id" id="department_id" value="{{ old('department_id') }}" class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required autofocus>
                                        <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                    </div>

                                    <div class="mb-2">
                                        <label for="department_abbreviation" class="block text-gray-700 text-md font-bold mb-2">Department Abbreviation</label>
                                        <input type="text" name="department_abbreviation" id="department_abbreviation" value="{{ old('department_abbreviation') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_abbreviation') is-invalid @enderror" required>
                                        <x-input-error :messages="$errors->get('department_abbreviation')" class="mt-2" />
                                    </div>

                                    <div class="mb-2">
                                        <label for="department_name" class="block text-gray-700 text-md font-bold mb-2">Department Name</label>
                                        <input type="text" name="department_name" id="department_name" value="{{ old('department_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_name') is-invalid @enderror" required>
                                        <x-input-error :messages="$errors->get('department_name')" class="mt-2" />
                                    </div>
                                   
                                    <div class="mb-2">
                                        <label for="dept_identifier" class="block text-gray-700 text-md font-bold mb-2">This department is for: </label>
                                        <select id="dept_identifier" name="dept_identifier" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('dept_identifier') is-invalid @enderror" required>
                                                <option value="">Select Option</option>
                                                <option value="employee">Employee</option>
                                                <option value="student">Student</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                    </div>
                                <div class="flex mb-4 mt-10 justify-center">
                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            
        @endif
        @if($search && $departments->isEmpty())
        <p class="text-black mt-8 text-center">No employee/s found in <text class="text-red-500">{{ $schoolToShow->school_name }}</text> for matching "{{ $search }}"</p>  
        <div class="flex justify-center mt-2">
            @if($search)
                <p><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
            @endif
        </div>
        @elseif(!$search && $departments->isEmpty())
            
            <p class="text-black mt-8 text-center uppercase">No data available in school <text class="text-red-500">
                @if($schoolToShow)
                {{ $schoolToShow->school_name}}
            @endif</text></p>
        @else

            @if($schoolToShow)
                
                <div class="overflow-x-auto">
                    <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                        <thead class="bg-gray-200 text-black">
                            <tr>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('id')" class="w-full h-full flex items-center justify-center">
                                        Count #
                                        @if ($sortField == 'id')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('department_abbreviation')" class="w-full h-full flex items-center justify-center">
                                        Department Abbreviation
                                        @if ($sortField == 'department_abbreviation')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('department_name')" class="w-full h-full flex items-center justify-center">
                                        Department Name
                                        @if ($sortField == 'department_name')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('school_id')" class="w-full h-full flex items-center justify-center">
                                        School Name
                                        @if ($sortField == 'school_id')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('dept_identifier')" class="w-full h-full flex items-center justify-center">
                                        Department for
                                        @if ($sortField == 'dept_identifier')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach ($departments as $department)
                                <tr class="hover:bg-gray-100" wire:model="selectedDepartment">
                                    <td class="text-black border border-gray-400  ">{{ $department->id }}</td>
                                    <!-- <td class="text-black border border-gray-400  ">{{ $department->department_id }}</td> -->
                                    <td class="text-black border border-gray-400">{{ $department->department_abbreviation}}</td>
                                    <td class="text-black border border-gray-400">{{ $department->department_name}}</td>
                                    <td class="text-black border border-gray-400">{{ $department->school->abbreviation}}</td>
                                    <td class="text-black border border-gray-400">{{ ucfirst($department->dept_identifier) }}</td>
                                    <td class="text-black border border-gray-400 px-1 py-1">
                                        <div class="flex justify-center items-center space-x-2">
                                            @if($schoolToShow && $department)
                                            <div x-data="{ open: false, 
                                                id: {{ json_encode($department->id) }},
                                                    department_id: {{ json_encode($department->department_id) }},
                                                    department_abbreviation: {{ json_encode($department->department_abbreviation) }},
                                                    school: {{ json_encode($department->school_id) }},
                                                    department_name: {{ json_encode($department->department_name) }},
                                                    
                                                    }">
                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i>
                                                </a>
                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                            <p class="text-xl font-bold">Edit Department</p>
                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                        </div>
                                                        <div class="mb-4">
                                                            <form id="updateStaffForm" action="{{ route('admin.department.update', $department->id )}}" method="POST" class="">
                                                                <x-caps-lock-detector />
                                                                @csrf
                                                                @method('PUT')
                                                                    <div class="mb-2">
                                                                        <label for="school_id" class="block text-gray-700 text-md font-bold mb-2 text-left">School Name: </label>
                                                                        <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                                                <option value="{{ $schoolToShow->id }}">{{ $schoolToShow->abbreviation }}</option>
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <label for="department_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Department School ID</label>
                                                                        <input type="text" name="department_id" id="department_id" x-model="department_id" value="{{ $department->department_id }}"  class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required autofocus>
                                                                        <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <label for="department_abbreviation" class="block text-gray-700 text-md font-bold mb-2 text-left">Department Abbreviation</label>
                                                                        <input type="text" name="department_abbreviation" id="department_abbreviation" x-model="department_abbreviation" value="{{ $department->department_abbreviation }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_abbreviation') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('department_abbreviation')" class="mt-2" />
                                                                    </div>

                                                                    <div class="mb-4">
                                                                        <label for="department_name" class="block text-gray-700 text-md font-bold mb-2 text-left">Department Name</label>
                                                                        <input type="text" name="department_name" id="department_name" x-model="department_name" value="{{ $department->department_name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_name') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('department_name')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="dept_identifier" class="block text-gray-700 text-md font-bold mb-2 text-left">This department is for: </label>
                                                                        <select id="dept_identifier" name="dept_identifier" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('dept_identifier') is-invalid @enderror" required>
                                                                        @if($department->dept_identifier === 'employee')  
                                                                                <option value="{{ $department->dept_identifier }}">{{ ucfirst($department->dept_identifier) }}</option>
                                                                                <option value="student">Student</option>
                                                                            @else
                                                                                <option value="{{ $department->dept_identifier }}">{{ ucfirst($department->dept_identifier) }}</option>
                                                                                <option value="employee">Employee</option>
                                                                            @endif
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                                    </div>
                                                                <div class="flex mb-4 mt-10 justify-center">
                                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                        Save Changes
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form id="deleteSelected" action="{{ route('admin.department.destroy', [':id', ':department_id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $department->id }}', '{{ $department->department_id }}', '{{ $department->department_abbreviation }}', '{{ $department->department_name }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-500 text-white text-sm px-3 py-2 rounded hover:bg-red-700">
                                                    <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($schoolToShow)
                        <tr>
                            <td colspan="2">
                                <div class="flex justify-between">
                                    <div class="uppercase text-black mt-2 text-sm mb-4">
                                        @if($search)
                                            {{ $departments->total() }} Search results 
                                        @endif                                    
                                    </div>
                                    <div class="justify-end">
                                        <p class="text-black mt-2 text-sm mb-4 uppercase">Total # of departments: <text class="ml-2">{{ $departmentCounts[$schoolToShow->id]->department_count ?? 0 }}</text></p>
                                        
                                    </div>
                                </div> 
                            </td>
                            <td>
                                {{ $departments->links() }}
                            </td>
                            <div class="flex justify-center mt-2">
                                @if($search)
                                    <p><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
                                @endif
                            </div>
                        </tr>
                    @endif
                </div>
            @else
                <p class="text-black mt-10  text-center">Select table to show data</p>
            @endif
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restrict morning times to 12:00 AM to 11:59 AM
            var morningStartTime = document.getElementById('morning_start_time');
            var morningEndTime = document.getElementById('morning_end_time');

            morningStartTime.addEventListener('input', function() {
                if (this.value.split(':')[0] >= 12) {
                    this.value = '';
                    alert('Please select a time between 12:00 AM and 11:59 AM');
                }
            });

            morningEndTime.addEventListener('input', function() {
                if (this.value.split(':')[0] >= 12) {
                    this.value = '';
                    alert('Please select a time between 12:00 AM and 11:59 AM');
                }
            });

            // Restrict afternoon times to 12:00 PM to 11:59 PM
            var afternoonStartTime = document.getElementById('afternoon_start_time');
            var afternoonEndTime = document.getElementById('afternoon_end_time');

            afternoonStartTime.addEventListener('input', function() {
                if (this.value.split(':')[0] < 12) {
                    this.value = '';
                    alert('Please select a time between 12:00 PM and 11:59 PM');
                }
            });

            afternoonEndTime.addEventListener('input', function() {
                if (this.value.split(':')[0] < 12) {
                    this.value = '';
                    alert('Please select a time between 12:00 PM and 11:59 PM');
                }
            });
        });
    </script>

    <script>

    function searchDepartments(event) {
            let searchTerm = event.target.value.toLowerCase();
            if (searchTerm === '') {
                this.departmentsToShow = @json($departmentsToShow->toArray());
            } else {
                this.departmentsToShow = this.departmentsToShow.filter(department =>
                    department.department_name.toLowerCase().includes(searchTerm) ||
                    department.department_abbreviation.toLowerCase().includes(searchTerm) ||
                    department.school.school_name.toLowerCase().includes(searchTerm)
                );
            }
        }

            function confirmDeleteAll(event) {
            event.preventDefault(); // Prevent form submission initially

            Swal.fire({
                title: 'Select School to Delete All Records',
                html: `
                    <select id="school_id_select" class="cursor-pointer hover:border-red-500 swal2-select">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->abbreviation }} - {{ $school->school_name }}</option>
                        @endforeach
                    </select>
                `,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete all!',
                preConfirm: () => {
                    const schoolId = Swal.getPopup().querySelector('#school_id_select').value;
                    if (!schoolId) {
                        Swal.showValidationMessage(`Please select a school`);
                    }
                    return { schoolId: schoolId };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const schoolId = result.value.schoolId;
                    document.getElementById('school_id_to_delete').value = schoolId;
                    document.getElementById('deleteAll').submit();
                }
            });
        }

        function ConfirmDeleteSelected(event, rowId, departmentId, departmentAbbreviation, departmentName) {
            event.preventDefault(); // Prevent form submission initially

            Swal.fire({
                title: `Are you sure you want to delete the department ${departmentId} - ${departmentAbbreviation} ${departmentName} ?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteSelected');
                    // Replace the placeholders with the actual rowId and departmentId
                    const actionUrl = form.action.replace(':id', rowId).replace(':department_id', departmentId);
                    form.action = actionUrl;
                    form.submit();
                }
            });

            return false; 
        }

    </script>









<!-- Department Staff Display -->
@elseif (Auth::user()->hasRole('admin_staff')) 


    <div>
        @if (session('success'))
            <x-sweetalert type="success" :message="session('success')" />
        @endif

        @if (session('info'))
            <x-sweetalert type="info" :message="session('info')" />
        @endif

        @if (session('error'))
            <x-sweetalert type="error" :message="session('error')" />
        @endif

        <div class="flex justify-between mb-4 sm:-mt-4">
            <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Staff / Manage Department</div>
        </div>
        <div class="flex flex-col md:flex-row items-start md:items-center md:justify-start">
            <!-- Dropdown and Delete Button -->
            @if($schoolToShow)
                <p class="w-96 text-black mt-2 text-sm mb-1">School: <span class="text-red-500 ml-2 font-bold uppercase">{{ $schoolToShow->abbreviation }}</span></p>
            @endif
            <!-- Search Input -->
            <div class="w-full flex justify-end mt-4 md:mt-0 md:ml-4">
                @if(empty($selectedSchool)) 
                    
                @else
                    <input wire:model.live="search" type="text" class="text-sm border text-black border-gray-300 rounded-md px-3 py-1.5 w-64" placeholder="Search..." autofocus>
                @endif
            </div>
        </div>
        <hr class="border-gray-200 my-2">
        @if($schoolToShow)
            <div class="flex justify-end mb-2">
                <div x-data="{ open: false }">
                    <button @click="open = true" class="bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                        <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Department
                    </button>
                    <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <div @click.away="open = true" class="w-[30%] max-h-[90%]  bg-white p-6 rounded-lg shadow-lg  mx-auto overflow-y-auto">
                            <div class="flex justify-between items-center pb-3">
                                <p class="text-xl font-bold">Add Department</p>
                                <button @click="open = false" class=" text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                            </div>
                            <div class="mb-4">
                                <form action="{{ route('admin_staff.department.store') }}" method="POST" class="">
                                <x-caps-lock-detector />
                                    @csrf

                                        <div class="mb-2">
                                            <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School Name: </label>
                                            <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                    <option value="{{ $schoolToShow->id }}">{{ $schoolToShow->abbreviation }}</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="department_id" class="block text-gray-700 text-md font-bold mb-2">Department School ID</label>
                                            <input type="text" name="department_id" id="department_id" value="{{ old('department_id') }}" class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required autofocus>
                                            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                        </div>

                                        <div class="mb-2">
                                            <label for="department_abbreviation" class="block text-gray-700 text-md font-bold mb-2">Department Abbreviation</label>
                                            <input type="text" name="department_abbreviation" id="department_abbreviation" value="{{ old('department_abbreviation') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_abbreviation') is-invalid @enderror" required>
                                            <x-input-error :messages="$errors->get('department_abbreviation')" class="mt-2" />
                                        </div>

                                        <div class="mb-2">
                                            <label for="department_name" class="block text-gray-700 text-md font-bold mb-2">Department Name</label>
                                            <input type="text" name="department_name" id="department_name" value="{{ old('department_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_name') is-invalid @enderror" required>
                                            <x-input-error :messages="$errors->get('department_name')" class="mt-2" />
                                        </div>


                                        <div class="mb-2">
                                            <label for="dept_identifier" class="block text-gray-700 text-md font-bold mb-2">This department is for: </label>
                                            <select id="dept_identifier" name="dept_identifier" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('dept_identifier') is-invalid @enderror" required>
                                                    <option value="">Select Option</option>
                                                    <option value="employee">Employee</option>
                                                    <option value="student">Student</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                        </div>
                                    <div class="flex mb-4 mt-10 justify-center">
                                        <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            
        @endif
        @if($search && $departments->isEmpty())
            <p class="text-black mt-8 text-center">No employee/s found in <text class="text-red-500">{{ $schoolToShow->school_name }}</text> for matching "{{ $search }}"</p>  
            <div class="flex justify-center mt-2">
                @if($search)
                    <p><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
                @endif
            </div>
        @elseif(!$search && $departments->isEmpty())
            
            <p class="text-black mt-8 text-center uppercase">No data available in school 
                <text class="text-red-500">
                    @if($schoolToShow)
                        {{ $schoolToShow->school_name}}
                    @endif
                </text>
            </p>
        @else

            @if($schoolToShow)
                <div class="overflow-x-auto">
                    <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                        <thead class="bg-gray-200 text-black">
                            <tr>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('department_abbreviation')" class="w-full h-full flex items-center justify-center">
                                        Department Abbreviation
                                        @if ($sortField == 'department_abbreviation')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('department_name')" class="w-full h-full flex items-center justify-center">
                                        Department Name
                                        @if ($sortField == 'department_name')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('school_id')" class="w-full h-full flex items-center justify-center">
                                        School Name
                                        @if ($sortField == 'school_id')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('dept_identifier')" class="w-full h-full flex items-center justify-center">
                                        Department for
                                        @if ($sortField == 'dept_identifier')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach ($departments as $department)
                                <tr class="hover:bg-gray-100" wire:model="selectedDepartment">

                                    <td class="text-black border border-gray-400">{{ $department->department_abbreviation}}</td>
                                    <td class="text-black border border-gray-400">{{ $department->department_name}}</td>
                                    <td class="text-black border border-gray-400">{{ $department->school->abbreviation}}</td>
                                    <td class="text-black border border-gray-400">{{ ucfirst($department->dept_identifier) }}</td>
                                    <td class="text-black border border-gray-400 px-1 py-3">
                                        <div class="flex justify-center items-center space-x-2">
                                            @if($schoolToShow && $department)
                                            <div x-data="{ open: false, 
                                                id: {{ json_encode($department->id) }},
                                                    department_id: {{ json_encode($department->department_id) }},
                                                    department_abbreviation: {{ json_encode($department->department_abbreviation) }},
                                                    school: {{ json_encode($department->school_id) }},
                                                    department_name: {{ json_encode($department->department_name) }},
                                                    
                                                    }">
                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i>
                                                </a>
                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                            <p class="text-xl font-bold">Edit Department</p>
                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                        </div>
                                                        <div class="mb-4">
                                                            <form id="updateStaffForm" action="{{ route('admin_staff.department.update', $department->id )}}" method="POST" class="">
                                                                <x-caps-lock-detector />
                                                                @csrf
                                                                @method('PUT')
                                                                    <div class="mb-2">
                                                                        <label for="school_id" class="block text-gray-700 text-md font-bold mb-2 text-left">School Name: </label>
                                                                        <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                                                <option value="{{ $schoolToShow->id }}">{{ $schoolToShow->abbreviation }}</option>
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <label for="department_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Department School ID</label>
                                                                        <input type="text" name="department_id" id="department_id" x-model="department_id" value="{{ $department->department_id }}"  class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required autofocus>
                                                                        <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <label for="department_abbreviation" class="block text-gray-700 text-md font-bold mb-2 text-left">Department Abbreviation</label>
                                                                        <input type="text" name="department_abbreviation" id="department_abbreviation" x-model="department_abbreviation" value="{{ $department->department_abbreviation }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_abbreviation') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('department_abbreviation')" class="mt-2" />
                                                                    </div>

                                                                    <div class="mb-4">
                                                                        <label for="department_name" class="block text-gray-700 text-md font-bold mb-2 text-left">Department Name</label>
                                                                        <input type="text" name="department_name" id="department_name" x-model="department_name" value="{{ $department->department_name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_name') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('department_name')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="dept_identifier" class="block text-gray-700 text-md font-bold mb-2 text-left">This department is for: </label>
                                                                        <select id="dept_identifier" name="dept_identifier" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('dept_identifier') is-invalid @enderror" required>
                                                                        @if($department->dept_identifier === 'employee')  
                                                                                <option value="{{ $department->dept_identifier }}">{{ ucfirst($department->dept_identifier) }}</option>
                                                                                <option value="student">Student</option>
                                                                            @else
                                                                                <option value="{{ $department->dept_identifier }}">{{ ucfirst($department->dept_identifier) }}</option>
                                                                                <option value="employee">Employee</option>
                                                                            @endif
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                                    </div>
                                                                <div class="flex mb-4 mt-10 justify-center">
                                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                        Save Changes
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (Auth::user()->hasRole('admin'))
                                                <form id="deleteSelected" action="{{ route('staff.department.destroy', [':id', ':department_id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $department->id }}', '{{ $department->department_id }}', '{{ $department->department_abbreviation }}', '{{ $department->department_name }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="bg-red-500 text-white text-sm px-3 py-2 rounded hover:bg-red-700">
                                                        <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($schoolToShow)
                        <tr>
                            <td colspan="2">
                                <div class="flex justify-between">
                                    <div class="uppercase text-black mt-2 text-sm mb-4">
                                        @if($search)
                                            {{ $departments->total() }} Search results 
                                        @endif                                    
                                    </div>
                                    <div class="justify-end">
                                        <p class="text-black mt-2 text-sm mb-4 uppercase">Total # of departments: <text class="ml-2">{{ $departmentCounts[$schoolToShow->id]->department_count ?? 0 }}</text></p>
                                        
                                    </div>
                                </div> 
                            </td>
                            <td>
                                {{ $departments->links() }}
                            </td>
                            <div class="flex justify-center mt-2">
                                @if($search)
                                    <p><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
                                @endif
                            </div>
                        </tr>
                    @endif
                </div>
            @else
                <p class="text-black mt-10  text-center">Select table to show data</p>
            @endif
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restrict morning times to 12:00 AM to 11:59 AM
            var morningStartTime = document.getElementById('morning_start_time');
            var morningEndTime = document.getElementById('morning_end_time');

            morningStartTime.addEventListener('input', function() {
                if (this.value.split(':')[0] >= 12) {
                    this.value = '';
                    alert('Please select a time between 12:00 AM and 11:59 AM');
                }
            });

            morningEndTime.addEventListener('input', function() {
                if (this.value.split(':')[0] >= 12) {
                    this.value = '';
                    alert('Please select a time between 12:00 AM and 11:59 AM');
                }
            });

            // Restrict afternoon times to 12:00 PM to 11:59 PM
            var afternoonStartTime = document.getElementById('afternoon_start_time');
            var afternoonEndTime = document.getElementById('afternoon_end_time');

            afternoonStartTime.addEventListener('input', function() {
                if (this.value.split(':')[0] < 12) {
                    this.value = '';
                    alert('Please select a time between 12:00 PM and 11:59 PM');
                }
            });

            afternoonEndTime.addEventListener('input', function() {
                if (this.value.split(':')[0] < 12) {
                    this.value = '';
                    alert('Please select a time between 12:00 PM and 11:59 PM');
                }
            });
        });
    </script>

    <script>

    function searchDepartments(event) {
            let searchTerm = event.target.value.toLowerCase();
            if (searchTerm === '') {
                this.departmentsToShow = @json($departmentsToShow->toArray());
            } else {
                this.departmentsToShow = this.departmentsToShow.filter(department =>
                    department.department_name.toLowerCase().includes(searchTerm) ||
                    department.department_abbreviation.toLowerCase().includes(searchTerm) ||
                    department.school.school_name.toLowerCase().includes(searchTerm)
                );
            }
        }

            function confirmDeleteAll(event) {
            event.preventDefault(); // Prevent form submission initially

            Swal.fire({
                title: 'Select School to Delete All Records',
                html: `
                    <select id="school_id_select" class="cursor-pointer hover:border-red-500 swal2-select">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->abbreviation }} - {{ $school->school_name }}</option>
                        @endforeach
                    </select>
                `,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete all!',
                preConfirm: () => {
                    const schoolId = Swal.getPopup().querySelector('#school_id_select').value;
                    if (!schoolId) {
                        Swal.showValidationMessage(`Please select a school`);
                    }
                    return { schoolId: schoolId };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const schoolId = result.value.schoolId;
                    document.getElementById('school_id_to_delete').value = schoolId;
                    document.getElementById('deleteAll').submit();
                }
            });
        }

        function ConfirmDeleteSelected(event, rowId, departmentId, departmentAbbreviation, departmentName) {
            event.preventDefault(); // Prevent form submission initially

            Swal.fire({
                title: `Are you sure you want to delete the department ${departmentId} - ${departmentAbbreviation} ${departmentName} ?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteSelected');
                    // Replace the placeholders with the actual rowId and departmentId
                    const actionUrl = form.action.replace(':id', rowId).replace(':department_id', departmentId);
                    form.action = actionUrl;
                    form.submit();
                }
            });

            return false; 
        }

    </script>
@endif