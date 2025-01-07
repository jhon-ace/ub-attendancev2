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
        <div class="font-bold text-md tracking-tight text-sm text-black  mt-2">Admin / Manage Staff</div>
        <div x-data="{ open: false }">
            <button @click="open = true" class="bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Staff
            </button>
            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                    <div class="flex justify-between items-center pb-3">
                        <p class="text-xl font-bold">Add Staff</p>
                        <button @click="open = false" class=" text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                    </div>
                    <div class="mb-4">
                        <form action="{{ route('admin.staff.store') }}" method="POST" class="">
                        <x-caps-lock-detector />
                            @csrf

                            <div class="mb-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">Staff belongs to:</label>
                                    <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                        <option value="" selected>Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}">{{ $school->abbreviation }} - {{ $school->school_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="staff_id" class="block text-gray-700 text-md font-bold mb-2">Staff School ID</label>
                                    <input type="text" name="staff_id" id="staff_id" value="{{ old('staff_id') }}" class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_id') is-invalid @enderror" required autofocus>
                                    <x-input-error :messages="$errors->get('staff_id')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="staff_firstname" class="block text-gray-700 text-md font-bold mb-2">First Name</label>
                                    <input type="text" name="staff_firstname" id="staff_firstname" value="{{ old('staff_firstname') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_firstname') is-invalid @enderror" required>
                                    <x-input-error :messages="$errors->get('staff_firstname')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="staff_middlename" class="block text-gray-700 text-md font-bold mb-2">Middle Name</label>
                                    <input type="text" name="staff_middlename" id="staff_middlename" value="{{ old('staff_middlename') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_middlename') is-invalid @enderror" required>
                                    <x-input-error :messages="$errors->get('staff_middlename')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="staff_lastname" class="block text-gray-700 text-md font-bold mb-2">Last Name</label>
                                    <input type="text" name="staff_lastname" id="staff_lastname" value="{{ old('staff_lastname') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_lastname') is-invalid @enderror" required>
                                    <x-input-error :messages="$errors->get('staff_lastname')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="staff_rfid" class="block text-gray-700 text-md font-bold mb-2">RF ID No</label>
                                    <input type="text" name="staff_rfid" id="staff_rfid" value="{{ old('staff_rfid') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_rfid') is-invalid @enderror" required>
                                    <x-input-error :messages="$errors->get('staff_rfid')" class="mt-2" />
                                </div>

                                
                            </div>
                            <div>
                                    <label for="access_type" class="block text-gray-700 text-md font-bold mb-2">Access Type</label>
                                    <select id="access_type" name="access_type" value="{{ old('access_type') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('access_type') is-invalid @enderror" required>
                                        <option value="" selected>Select Access type</option>
                                        <option value="administrative">Administrative</option>
                                        <option value="departmental">Departmental</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('access_type')" class="mt-2" />
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
    <hr class="border-gray-200 my-4">
    <div class="flex items-center mb-4 justify-between">
    <div class="flex w-24 mr-2 sm:mr-0">
        <form id="deleteAll" action="{{ route('admin.staff.deleteAll') }}" method="POST" onsubmit="return confirmDeleteAll(event);">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs lg:text-sm w-full mt-2 bg-red-500  text-white px-4 py-2.5 rounded-md hover:bg-red-700">
                Delete All
            </button>
        </form>
    </div>
    <div class="flex w-full sm:w-auto mt-2 sm:mt-0 sm:ml-2">
        <input wire:model.live="search" type="text" class="border text-black border-gray-300 rounded-md p-2 w-full" placeholder="Search..." autofocus>
    </div>
</div>


    @if($search && $staffs->isEmpty())
        <p class="text-black mt-8 text-center">No staff found for matching "{{ $search }}"</p>
    @elseif(!$search && $staffs->isEmpty())
        <p class="text-black mt-8 text-center">No data available in table</p>
    @else
    <div class="overflow-x-auto">
        <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
            <thead class="bg-gray-200 text-black">
                <tr>
                    <th class="border border-gray-400 px-3 py-2">
                        <button wire:click="sortBy('staff_id')" class="w-full h-full flex items-center justify-center">
                            Staff ID
                            @if ($sortField == 'staff_id')
                                @if ($sortDirection == 'asc')
                                    &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                @else
                                    &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                @endif
                            @endif
                        </button>
                    </th>
                     <th class="border border-gray-400 px-3 py-2">
                        <button wire:click="sortBy('staff_lastname')" class="w-full h-full flex items-center justify-center">
                            Last Name
                            @if ($sortField == 'staff_lastname')
                                @if ($sortDirection == 'asc')
                                    &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                @else
                                    &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                @endif
                            @endif
                        </button>
                    </th>
                    <th class="border border-gray-400 px-3 py-2">
                        <button wire:click="sortBy('staff_firstname')" class="w-full h-full flex items-center justify-center">
                            First Name
                            @if ($sortField == 'staff_firstname')
                                @if ($sortDirection == 'asc')
                                    &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                @else
                                    &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                @endif
                            @endif
                        </button>
                    </th>
                     <th class="border border-gray-400 px-3 py-2">
                        <button wire:click="sortBy('staff_middlename')" class="w-full h-full flex items-center justify-center">
                            Middle Name
                            @if ($sortField == 'staff_middlename')
                                @if ($sortDirection == 'asc')
                                    &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                @else
                                    &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                @endif
                            @endif
                        </button>
                    </th>
                     <th class="border border-gray-400 px-3 py-2">
                        <button wire:click="sortBy('staff_rfid')" class="w-full h-full flex items-center justify-center">
                            RFID No
                            @if ($sortField == 'staff_rfid')
                                @if ($sortDirection == 'asc')
                                    &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                @else
                                    &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                @endif
                            @endif
                        </button>
                    </th>
                    <th class="border border-gray-400 px-3 py-2">
                        <button wire:click="sortBy('access_type')" class="w-full h-full flex items-center justify-center">
                            Access Type
                            @if ($sortField == 'access_type')
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
                            School / Department
                            @if ($sortField == 'school_id')
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
            <tbody>
                @foreach ($staffs as $staff)
                    <tr class="hover:bg-gray-100">
                        <td class="text-black border border-gray-400  ">{{ $staff->staff_id }}</td>
                        <td class="text-black border border-gray-400">{{ $staff->staff_lastname}}</td>
                        <td class="text-black border border-gray-400">{{ $staff->staff_firstname}}</td>
                        <td class="text-black border border-gray-400">{{ $staff->staff_middlename}}</td>
                        <td class="text-black border border-gray-400">{{ $staff->staff_rfid}}</td>
                        <td class="text-black border border-gray-400 uppercase">{{ $staff->access_type}}</td>
                        <td class="text-black border border-gray-400">{{ $staff->school->abbreviation }} - {{ $staff->school->school_name }}</td>
                        <td class="text-black border border-gray-400 px-1 py-1">
                            <div class="flex justify-center items-center space-x-2">
                                <div x-data="{ open: false, 
                                        id: '{{ $staff->id }}', 
                                        staff_id: '{{ $staff->staff_id }}',
                                        staff_name: '{{ $staff->staff_name }}',
                                        access_type: '{{ $staff->access_type }}',
                                        school: '{{ $staff->school_id }}',
                                        staff_firstname: '{{ $staff->staff_firstname }}',
                                        staff_middlename: '{{ $staff->staff_middlename }}',
                                        staff_lastname: '{{ $staff->staff_lastname }}',
                                        staff_rfid: '{{ $staff->staff_rfid }}',
                                        }">
                                    <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                        <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i>
                                    </a>
                                    <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                        <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                            <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                <p class="text-xl font-bold">Edit Staff</p>
                                                <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                            </div>
                                            <div class="mb-4">
                                                <form id="updateStaffForm" action="{{ route('admin.staff.update', $staff->id )}}" method="POST" class="">
                                                    <x-caps-lock-detector />
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-4 grid grid-cols-2 gap-4">
                                                        <div>
                                                            <div class="mb-4">
                                                                <label for="school_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Staff belongs to:</label>
                                                                <select id="school_id" name="school_id" x-model="school" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                                    @foreach($schools as $school)
                                                                        <option value="{{ $school->id }}" {{ $staff->school_id == $school->id ? 'selected' : '' }}>
                                                                            {{ $school->abbreviation }} - {{ $school->school_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="mb-4">
                                                                <label for="staff_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Staff School ID</label>
                                                                <input type="text" name="staff_id" id="staff_id" x-model="staff_id" class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_id') is-invalid @enderror" required autofocus>
                                                                <x-input-error :messages="$errors->get('staff_id')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="mb-4">
                                                                <label for="staff_firstname" class="block text-gray-700 text-md font-bold mb-2 text-left">First Name</label>
                                                                <input type="text" name="staff_firstname" id="staff_firstname" x-model="staff_firstname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_firstname') is-invalid @enderror" required>
                                                                <x-input-error :messages="$errors->get('staff_firstname')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="mb-4">
                                                                <label for="staff_middlename" class="block text-gray-700 text-md font-bold mb-2 text-left">Middle Name</label>
                                                                <input type="text" name="staff_middlename" id="staff_middlename" x-model="staff_middlename" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_middlename') is-invalid @enderror" required>
                                                                <x-input-error :messages="$errors->get('staff_middlename')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="mb-4">
                                                                <label for="staff_lastname" class="block text-gray-700 text-md font-bold mb-2 text-left">Last Name</label>
                                                                <input type="text" name="staff_lastname" id="staff_lastname" x-model="staff_lastname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_lastname') is-invalid @enderror" required>
                                                                <x-input-error :messages="$errors->get('staff_lastname')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="mb-4">
                                                                <label for="staff_rfid" class="block text-gray-700 text-md font-bold mb-2 text-left">RFID No</label>
                                                                <input type="text" name="staff_rfid" id="staff_rfid" x-model="staff_rfid" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('staff_rfid') is-invalid @enderror" required>
                                                                <x-input-error :messages="$errors->get('staff_rfid')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="access_type" class="block text-gray-700 text-md font-bold mb-2 text-left">Access Type</label>
                                                        <select id="access_type" name="access_type" x-model="access_type" class="uppercase shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('access_type') is-invalid @enderror" required>
                                                            @if($staff->access_type === 'departmental')
                                                                <option value="{{ $staff->access_type }}" selected>{{ $staff->access_type }}</option>
                                                                <option value="administrative">Administrative</option>
                                                            @else
                                                                <option value="{{ $staff->access_type }}" selected>{{ $staff->access_type }}</option>
                                                                <option value="departmental">Departmental</option>
                                                            @endif
                                                        </select>
                                                        <x-input-error :messages="$errors->get('access_type')" class="mt-2" />
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
                                <form id="deleteSelected" action="{{ route('admin.staff.destroy',  [':id', ':staff_id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $staff->id }}', '{{ $staff->staff_id }}', '{{ $staff->staff_lastname }}', '{{ $staff->staff_firstname }}', '{{ $staff->staff_middlename }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 text-white text-sm px-3 py-2 rounded hover:bg-red-700">
                                        <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        {{ $staffs->links() }}
    @endif
</div>


<script>

    function confirmDeleteAll(event) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: 'Are you sure to delete all records?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete all!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form programmatically
                document.getElementById('deleteAll').submit();
            }
        });
    }

    function ConfirmDeleteSelected(event, rowId, staffId, staffLastname, staffFirstname, staffMiddlename) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: `Are you sure you want to delete the staff ${staffFirstname} ${staffMiddlename} ${staffLastname}?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteSelected');
                // Replace the placeholders with the actual rowId and staffId
                const actionUrl = form.action.replace(':id', rowId).replace(':staff_id', staffId);
                form.action = actionUrl;
                form.submit();
            }
        });

        return false; 
    }



</script>