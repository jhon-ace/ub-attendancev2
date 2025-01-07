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
            <div class="font-bold text-md tracking-tight text-black  mt-2 uppercase">Admin / Manage Schools</div>
            <div x-data="{ open: false }">
                <button @click="open = true" class="bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                    <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add School
                </button>
                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div @click.away="open = true" class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
                        <div class="flex justify-between items-center pb-3">
                            <p class="text-xl font-bold">Add School</p>
                            <button @click="open = false" class=" text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                        </div>
                        <div class="mb-4">
                            <form action="{{ route('admin.school.store') }}" method="POST" class="">
                            <x-caps-lock-detector />
                                @csrf
                                <div class="mb-4">
                                    <label for="abbreviation" class="block text-gray-700 text-md font-bold mb-2">School:</label>
                                    <input type="text" name="abbreviation" id="abbreviation" value="{{ old('abbreviation') }}"  class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_name') is-invalid @enderror" required autofocus>
                                    <x-input-error :messages="$errors->get('abbreviation')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <label for="school_name" class="block text-gray-700 text-md font-bold mb-2">School Description</label>
                                    <input type="text" name="school_name" id="school_name" value="{{ old('school_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_description') is-invalid @enderror" required>
                                    <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                                </div>
                                <div class="flex mb-4 mt-5 justify-center">
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
        <div class="flex items-center mb-4 justify-end">
            <div class="flex w-full sm:w-auto mt-2 sm:mt-0 sm:ml-2">
                <input wire:model.live="search" type="text" class="border text-black border-gray-300 rounded-md p-2 w-full" placeholder="Search..." autofocus>
            </div>
        </div>


        @if($search && $schools->isEmpty())
            <p class="text-black mt-8 text-center">No school found for matching "{{ $search }}"</p>
        @elseif(!$search && $schools->isEmpty())
            <p class="text-black mt-8 text-center">No data available in table</p>
        @else
            <table class="table-auto border-collapse border border-gray-400 w-full text-sm text-center mb-4">
                <thead class="bg-gray-200 text-black">
                    <tr>
                        <th class="border border-gray-400 px-3 py-2">
                            <button wire:click="sortBy('id')" class="w-full h-full flex items-center justify-center">
                                ID #
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
                            <button wire:click="sortBy('abbreviation')" class="w-full h-full flex items-center justify-center">
                                Shool Name
                                @if ($sortField == 'abbreviation')
                                    @if ($sortDirection == 'asc')
                                        &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                    @else
                                        &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="border border-gray-400 px-3 py-2">
                            <button wire:click="sortBy('school_name')" class="w-full h-full flex items-center justify-center">
                                Description
                                @if ($sortField == 'school_name')
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
                    @foreach ($schools as $school)
                        <tr>
                            <td class="text-black border border-gray-400 px-3 py-2">{{ $school->id }}</td>
                            <td class="text-black border border-gray-400 px-3 py-2">{{ $school->abbreviation }}</td>
                            <td class="text-black border border-gray-400 px-3 py-2">{{ $school->school_name}}</td>
                            <td class="text-black border border-gray-400 px-3 py-2">
                                <div class="flex justify-center items-center space-x-2">
                                    <div x-data="{ open: false, 
                                            schoolId: '{{ $school->id }}', 
                                            abbreviation: '{{ $school->abbreviation }}',
                                            school_name: '{{ $school->school_name }}' }">
                                        <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                            <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i>
                                        </a>
                                        <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                            <div @click.away="open = true" class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
                                                <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                    <p class="text-xl font-bold">Edit School</p>
                                                    <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                </div>
                                                <div class="mb-4">
                                                    <form id="updateSchoolForm" action="{{ route('admin.school.update', $school->id )}}" method="POST" class="">
                                                        <x-caps-lock-detector />
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-4">
                                                            <label for="abbreviation" class="block text-gray-700 text-md font-bold mb-2 text-left">School:</label>
                                                            <input type="text" name="abbreviation" id="abbreviation" x-model="abbreviation" class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_name') is-invalid @enderror" required autofocus>
                                                            <x-input-error :messages="$errors->get('abbreviation')" class="mt-2" />
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="school_name" class="block text-gray-700 text-md font-bold mb-2 text-left">School Description</label>
                                                            <input type="text" name="school_name" id="school_name" x-model="school_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_description') is-invalid @enderror" required>
                                                            <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                                                        </div>
                                                        <div class="flex mb-4 mt-5 justify-center">
                                                            <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                Save
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="deleteSelected" action="{{ route('admin.school.destroy', $school->id ) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $school->id }}', '{{ $school->school_name }}');">
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
            {{ $schools->links() }}
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

        function ConfirmDeleteSelected(event, schoolID, schoolName) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: `Are you sure you want to delete the school ${schoolName}?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteSelected');
                // Replace the placeholder with the actual school ID
                form.action = `{{ route('admin.school.destroy', ':schoolId') }}`.replace(':schoolId', schoolID);
                form.submit();
            }
        });

        return false; 
    }

    </script>

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
            <div class="font-bold text-md tracking-tight text-black  mt-2 uppercase">Staff / Manage School Year</div>
            <div x-data="{ open: false }">
                <button @click="open = true" class="bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                    <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add School
                </button>
                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div @click.away="open = true" class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
                        <div class="flex justify-between items-center pb-3">
                            <p class="text-xl font-bold">Add School</p>
                            <button @click="open = false" class=" text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                        </div>
                        <div class="mb-4">
                            <form action="{{ route('staff.school.store') }}" method="POST" class="">
                            <x-caps-lock-detector />
                                @csrf
                                <div class="mb-4">
                                    <label for="abbreviation" class="block text-gray-700 text-md font-bold mb-2">Abbreviation:</label>
                                    <input type="text" name="abbreviation" id="abbreviation" value="{{ old('abbreviation') }}"  class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_name') is-invalid @enderror" required autofocus>
                                    <x-input-error :messages="$errors->get('abbreviation')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <label for="school_name" class="block text-gray-700 text-md font-bold mb-2">School Name</label>
                                    <input type="text" name="school_name" id="school_name" value="{{ old('school_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_description') is-invalid @enderror" required>
                                    <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                                </div>
                                <div class="flex mb-4 mt-5 justify-center">
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
        <div class="flex items-center mb-4 justify-end">
            <div class="flex w-full sm:w-auto mt-2 sm:mt-0 sm:ml-2">
                <input wire:model.live="search" type="text" class="border text-black border-gray-300 rounded-md p-2 w-full" placeholder="Search..." autofocus>
            </div>
        </div>


        @if($search && $schools->isEmpty())
            <p class="text-black mt-8 text-center">No school found for matching "{{ $search }}"</p>
        @elseif(!$search && $schools->isEmpty())
            <p class="text-black mt-8 text-center">No data available in table</p>
        @else
            <table class="table-auto border-collapse border border-gray-400 w-full text-sm text-center mb-4">
                <thead class="bg-gray-200 text-black">
                    <tr>
                        <th class="border border-gray-400 px-3 py-2">
                            <button wire:click="sortBy('id')" class="w-full h-full flex items-center justify-center">
                                ID #
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
                            <button wire:click="sortBy('abbreviation')" class="w-full h-full flex items-center justify-center">
                                Shool Year
                                @if ($sortField == 'abbreviation')
                                    @if ($sortDirection == 'asc')
                                        &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                    @else
                                        &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="border border-gray-400 px-3 py-2">
                            <button wire:click="sortBy('school_name')" class="w-full h-full flex items-center justify-center">
                                Description
                                @if ($sortField == 'school_name')
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
                    @foreach ($schools as $school)
                        <tr>
                            <td class="text-black border border-gray-400 px-3 py-2">{{ $school->id }}</td>
                            <td class="text-black border border-gray-400 px-3 py-2">{{ $school->abbreviation }}</td>
                            <td class="text-black border border-gray-400 px-3 py-2">{{ $school->school_name}}</td>
                            <td class="text-black border border-gray-400 px-3 py-2">
                                <div class="flex justify-center items-center space-x-2">
                                    <div x-data="{ open: false, 
                                            schoolId: '{{ $school->id }}', 
                                            abbreviation: '{{ $school->abbreviation }}',
                                            school_name: '{{ $school->school_name }}' }">
                                        <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                            <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i>
                                        </a>
                                        <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                            <div @click.away="open = true" class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
                                                <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                    <p class="text-xl font-bold">Edit School</p>
                                                    <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                </div>
                                                <div class="mb-4">
                                                    <form id="updateSchoolForm" action="{{ route('staff.school.update', $school->id )}}" method="POST" class="">
                                                        <x-caps-lock-detector />
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-4">
                                                            <label for="abbreviation" class="block text-gray-700 text-md font-bold mb-2 text-left">Abbreviation:</label>
                                                            <input type="text" name="abbreviation" id="abbreviation" x-model="abbreviation" class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_name') is-invalid @enderror" required autofocus>
                                                            <x-input-error :messages="$errors->get('abbreviation')" class="mt-2" />
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="school_name" class="block text-gray-700 text-md font-bold mb-2 text-left">School Name</label>
                                                            <input type="text" name="school_name" id="school_name" x-model="school_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_description') is-invalid @enderror" required>
                                                            <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                                                        </div>
                                                        <div class="flex mb-4 mt-5 justify-center">
                                                            <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                Save
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="deleteSelected" action="{{ route('staff.school.destroy', $school->id ) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $school->id }}', '{{ $school->school_name }}');">
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
            {{ $schools->links() }}
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

        function ConfirmDeleteSelected(event, schoolID, schoolName) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: `Are you sure you want to delete the school ${schoolName}?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteSelected');
                // Replace the placeholder with the actual school ID
                form.action = `{{ route('staff.school.destroy', ':schoolId') }}`.replace(':schoolId', schoolID);
                form.submit();
            }
        });

        return false; 
    }

    </script>
@endif