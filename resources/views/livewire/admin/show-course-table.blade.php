<div class="mb-4">
        @php
            session(['selectedSchool' => $selectedSchool]);
            session(['selectedDepartment1' => $selectedDepartment1]);
        @endphp
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
    @if (Auth::user()->hasRole('admin'))
        <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Admin / Manage Courses</div>
    @else
        <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Staff / Manage Courses</div>
    @endif
    </div>

        <div class="flex flex-column overflow-x-auto -mb-5">
            <div class="col-span-3 p-4">
                <label for="school_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Select School:</label>
                <select wire:model="selectedSchool" id="school_id" name="school_id" wire:change="updateEmployees"
                        class="cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror md:w-auto"
                        required>
                    <option value="">Select School</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->abbreviation }}</option>
                    @endforeach
                </select>
                @if($schoolToShow)
                    <p class="text-black mt-2 text-sm mb-1 ">Selected School Year: <span class="text-red-500 ml-2">{{ $schoolToShow->abbreviation }}</span></p>
                    <!-- <p class="text-black  text-sm ml-4">Selected School: <span class="text-red-500 ml-2">{{ $schoolToShow->school_name }}</span></p> -->
                @endif
            </div>

        <div class="col-span-1 p-4">
            @if(!empty($selectedSchool))
                <label for="department_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">department:</label>
                <select wire:model="selectedDepartment1" id="department_id" name="department_id"
                        wire:change="updateEmployeesByDepartment"
                        class="cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror md:w-auto"
                        required>
                    @if($departments->isEmpty())
                        <option value="0">No Departments</option>
                    @else
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            @php
                                $cleanedAbbreviation = str_replace('- student', '', $department->department_abbreviation);
                            @endphp
                            <option value="{{ $department->id }}">{{ $cleanedAbbreviation }}</option>
                        @endforeach
                    @endif
                </select>
                @if($departmentToShow)
                    <!-- <p class="text-black mt-2 text-sm mb-1">Selected Department ID: <span class="text-red-500 ml-2">{{ $departmentToShow->department_id }}</span></p> -->
                    <!-- <p class="text-black text-sm ml-4 mt-2">Selected Department: <span class="text-red-500 ml-2 mt">{{ $departmentToShow->department_abbreviation }}</span></p> -->
                     @php
                        $cleanedAbbreviation = str_replace('- student', '', $departmentToShow->department_abbreviation);
                    @endphp

                    <p class="text-black mt-2 text-sm mb-1">
                        Selected Department: 
                        <span class="text-red-500 ml-2">{{ $cleanedAbbreviation }}</span>
                    </p>
                @endif
            @endif
        </div>

    </div>
    <hr class="border-gray-200 my-4">
        @if(!$schoolToShow)
            <p class="text-black text-sm mt-11 mb-4 uppercase text-center">No selected school</p>
        @endif
        @if(!empty($selectedSchool))
            @if(!$departmentToShow)
                <p class="text-black text-sm mt-11 mb-4 uppercase text-center">No selected department</p>
            @endif
        @endif
    <!--  -->
    @if($departmentToShow)
        @if($search && $courses->isEmpty())
        <p class="text-black mt-8 text-center">No course/s found in <text class="text-red-500">{{ $departmentToShow->department_name }}</text> for matching "{{ $search }}"</p>
        <p class="text-center mt-5"><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
        @elseif(!$search && $courses->isEmpty())
            <p class="text-black mt-8 text-center uppercase">No data available in <text class="text-red-500">{{$departmentToShow->department_abbreviation}} - {{ $departmentToShow->department_name }} department.</text></p>
            <div class="flex justify-center items-center mt-5">
                <div x-data="{ open: false }">
                    <button @click="open = true" class="-mt-1 mb-2 bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                        <!-- <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> {{$departmentToShow->department_id}} - {{$departmentToShow->department_name}} -->
                        <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Course in {{$departmentToShow->department_abbreviation}} - {{ $departmentToShow->department_name }} department
                    </button>
                    <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <div  class="w-[35%] bg-white p-6 rounded-lg shadow-lg mx-auto max-h-[90vh] overflow-y-auto">
                            <div class="flex justify-between items-center pb-3">
                                <p class="text-xl font-bold">Add Course</p>
                                <button @click="open = false" class="text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                            </div>
                            <div class="mb-4">
                            @if (Auth::user()->hasRole('admin'))
                                <form action="{{ route('admin.course.store') }}" method="POST" class="" enctype="multipart/form-data">
                            @else
                                <form action="{{ route('staff.course.store') }}" method="POST" class="" enctype="multipart/form-data">
                            @endif
                                    <x-caps-lock-detector />
                                    @csrf

                                    <div class="mb-2">
                                        <input type="file" name="course_logo" id="course_logo" class="hidden" accept="image/*" onchange="previewImage(event)">
                                        <label for="course_logo" class="cursor-pointer flex flex-col items-center">
                                            <div id="imagePreviewContainer" class="mb-2 text-center">
                                                <img id="imagePreview" src="{{ asset('assets/img/user.png') }}" class="rounded-lg w-48 h-auto">
                                            </div>
                                            <span class="text-sm text-gray-500">Select Logo</span>
                                        </label>
                                        <x-input-error :messages="$errors->get('course_logo')" class="mt-2" />
                                    </div>

                                    <div class="mb-2">
                                        <label for="course_id" class="block text-gray-700 text-md font-bold mb-2">Course ID</label>
                                        <input type="text" name="course_id" id="coursee_id" value="{{ old('course_id') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_id') is-invalid @enderror" required autofocus>
                                        <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                                    </div>
                                    <div class="mb-2">
                                        <label for="course_abbreviation" class="block text-gray-700 text-md font-bold mb-2">Course_abbreviation</label>
                                        <input type="text" name="course_abbreviation" id="course_abbreviation" value="{{ old('course_abbreviation') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_abbreviation') is-invalid @enderror" required>
                                        <x-input-error :messages="$errors->get('course_abbreviation')" class="mt-2" />
                                    </div>
                                    <div class="mb-2">
                                        <label for="course_name" class="block text-gray-700 text-md font-bold mb-2">Course Description</label>
                                        <input type="text" name="course_name" id="course_name" value="{{ old('course_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_name') is-invalid @enderror" required>
                                        <x-input-error :messages="$errors->get('course_name')" class="mt-2" />
                                    </div>
                                    <div class="mb-2">
                                        <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School Year:</label>
                                        <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                            <!-- <option value="{{ $departmentToShow->school->id }}">{{ $departmentToShow->school->id }} | {{ $departmentToShow->school->school_name }}</option> -->
                                                <option value="{{ $departmentToShow->school->id }}">{{ $departmentToShow->school->abbreviation }}</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                    </div>

                                    <div class="mb-2">
                                        <label for="department_id" class="block text-gray-700 text-md font-bold mb-2">Department:</label>
                                        <select id="department_id" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required>
                                            <!-- <option value="{{ $departmentToShow->id }}">{{ $departmentToShow->department_id }} | {{ $departmentToShow->department_name }}</option> -->
                                            <option value="{{ $departmentToShow->id }}">{{ $departmentToShow->department_abbreviation }}</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('id')" class="mt-2" />
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
            <div class="flex justify-between">
                <div class="">
                    <!-- delete area -->
                </div>
                <div class="flex justify-center items-center">
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="-mt-1 mb-2 bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                            <!-- <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> {{$departmentToShow->department_id}} - {{$departmentToShow->department_name}} -->
                            <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Course
                        </button>
                        <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div  class="w-[35%] bg-white p-6 rounded-lg shadow-lg mx-auto max-h-[90vh] overflow-y-auto">
                                <div class="flex justify-between items-center pb-3">
                                    <p class="text-xl font-bold">Add Course</p>
                                    <button @click="open = false" class="text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                                </div>
                                <div class="mb-4">
                                @if (Auth::user()->hasRole('admin'))
                                    <form action="{{ route('admin.course.store') }}" method="POST" class="" enctype="multipart/form-data">
                                @else
                                    <form action="{{ route('staff.course.store') }}" method="POST" class="" enctype="multipart/form-data">
                                @endif
                                        
                                        <x-caps-lock-detector />
                                        @csrf

                                        <div class="mb-2">
                                            <input type="file" name="course_logo" id="course_logo" class="hidden" accept="image/*" onchange="previewImage(event)">
                                            <label for="course_logo" class="cursor-pointer flex flex-col items-center">
                                                <div id="imagePreviewContainer" class="mb-2 text-center">
                                                    <img id="imagePreview" src="{{ asset('assets/img/user.png') }}" class="rounded-lg w-48 h-auto">
                                                </div>
                                                <span class="text-sm text-gray-500">Select Logo</span>
                                            </label>
                                            <x-input-error :messages="$errors->get('course_logo')" class="mt-2" />
                                        </div>

                                        <div class="mb-2">
                                            <label for="course_id" class="block text-gray-700 text-md font-bold mb-2">Course ID</label>
                                            <input type="text" name="course_id" id="coursee_id" value="{{ old('course_id') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_id') is-invalid @enderror" required autofocus>
                                            <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="course_abbreviation" class="block text-gray-700 text-md font-bold mb-2">Course_abbreviation</label>
                                            <input type="text" name="course_abbreviation" id="course_abbreviation" value="{{ old('course_abbreviation') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_abbreviation') is-invalid @enderror" required>
                                            <x-input-error :messages="$errors->get('course_abbreviation')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="course_name" class="block text-gray-700 text-md font-bold mb-2">Course Description</label>
                                            <input type="text" name="course_name" id="course_name" value="{{ old('course_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_name') is-invalid @enderror" required>
                                            <x-input-error :messages="$errors->get('course_name')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School Year:</label>
                                            <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                <!-- <option value="{{ $departmentToShow->school->id }}">{{ $departmentToShow->school->id }} | {{ $departmentToShow->school->school_name }}</option> -->
                                                 <option value="{{ $departmentToShow->school->id }}">{{ $departmentToShow->school->abbreviation }}</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                        </div>

                                        <div class="mb-2">
                                            <label for="department_id" class="block text-gray-700 text-md font-bold mb-2">Department ID:</label>
                                            <select id="department_id" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required>
                                                <!-- <option value="{{ $departmentToShow->id }}">{{ $departmentToShow->department_id }} | {{ $departmentToShow->department_name }}</option> -->
                                                <option value="{{ $departmentToShow->id }}">{{ $departmentToShow->department_abbreviation }}</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('id')" class="mt-2" />
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
            </div>
            <div class="flex justify-between mt-1 mb-2">
                <div class="mt-2 text-sm font-bold uppercase">
                    Course List in <text class="text-red-500">{{$departmentToShow->department_abbreviation}} - {{$departmentToShow->department_name}}</text> department
                </div>
                <div>
                    <input wire:model.live="search" type="text" class="text-sm border text-black border-gray-300 rounded-md px-3 py-1.5 w-full md:w-64" placeholder="Search..." autofocus>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                    <thead class="bg-gray-200 text-black">
                        <!-- <tr >
                            <th colspan="9" class="border-none bg-white border border-gray-400 px-3 py-2 uppercase"></th>
                        </tr> -->
                        <tr>
                            <th class="border border-gray-400 px-3 py-2">

                                <button wire:click="sortBy('course_id')" class="w-full h-full flex items-center justify-center">
                                    Course ID
                                    @if ($sortField == 'employee_id')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('course_logo')" class="w-full h-full flex items-center justify-center">
                                    Course Logo
                                    @if ($sortField == 'course_logo')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('course_abbreviation')" class="w-full h-full flex items-center justify-center">
                                    Course Abbreviation
                                    @if ($sortField == 'course_abbreviation')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('course_name')" class="w-full h-full flex items-center justify-center">
                                    Course Description
                                    @if ($sortField == 'course_name')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('department_id')" class="w-full h-full flex items-center justify-center">
                                    Course Department
                                    @if ($sortField == 'department_id')
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
                        @foreach ($courses as $course)
                            <tr class="hover:bg-gray-100" wire:model="selectedDepartment">
                                <td class="text-black border border-gray-400">{{ $course->course_id}}</td>
                                <td class="text-black border border-gray-400 border-t-0 border-r-0 border-l-0 px-2 py-1 flex items-center justify-center" >
                                    @if ($course->course_logo && Storage::exists('public/course_logo/' . $course->course_logo))
                                        <a  href="{{ asset('storage/course_logo/' . $course->course_logo) }}" 
                                            class="hover:border border-red-500 rounded-full" title="Click to view Picture"
                                            data-fancybox data-caption="LOGO: {{ $course->course_abbreviation }} - {{ $course->course_name }}">
                                            <img src="{{ asset('storage/course_logo/' . $course->course_logo) }}" class="rounded-full w-9 h-9">
                                        </a>
                                    @else
                                        <img data-fancybox src="{{ asset('assets/img/user.png') }}" class="cursor-pointer w-9 h-9 hover:border hover:border-red-500 rounded-full" title="Click to view Picture" >
                                    @endif
                                </td>
                                <td class="text-black border border-gray-400">{{ $course->course_abbreviation}}</td>
                                <td class="text-black border border-gray-400">{{ $course->course_name}}</td>
                                <td class="text-black border border-gray-400">{{ $course->department->department_abbreviation}}</td>
                                <td class="text-black border border-gray-400">
                                    <div class="flex justify-center items-center space-x-2">
                                        <div x-data="{ open: false
                                                }">
                                            <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-[5px] rounded hover:bg-blue-700">
                                                <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i>
                                            </a>
                                            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg max-h-[90vh] overflow-y-auto  mx-auto">
                                                    <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                        <p class="text-xl font-bold">Edit Course</p>
                                                        <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                    </div>
                                                    <div class="mb-4">
                                                    @if (Auth::user()->hasRole('admin'))
                                                        <form action="{{ route('admin.course.update', $course->id) }}" method="POST" class="" enctype="multipart/form-data">
                                                    @else
                                                        <form action="{{ route('staff.course.update', $course->id) }}" method="POST" class="" enctype="multipart/form-data">
                                                    @endif
                                                        
                                                            <x-caps-lock-detector />
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="mb-4 text-center flex flex-col items-center">
                                                                <img id="blah" src="{{ $course->course_logo ? asset('storage/course_logo/' . $course->course_logo) : asset('assets/img/user.png') }}" alt="Default photo Icon" class="max-w-xs mb-2" />
                                                                <input type="file" onchange="readURL(this);" name="course_logo" id="course_logo" class="p-2 bg-gray-800 text-white" accept="image/*" />
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="course_id" class="block text-gray-700 text-md font-bold mb-2">Course ID</label>
                                                                <input type="text" name="course_id" id="coursee_id" value="{{ $course->course_id }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_id') is-invalid @enderror" required autofocus>
                                                                <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="course_abbreviation" class="block text-gray-700 text-md font-bold mb-2">Course_abbreviation</label>
                                                                <input type="text" name="course_abbreviation" id="course_abbreviation" value="{{ $course->course_abbreviation }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_abbreviation') is-invalid @enderror" required>
                                                                <x-input-error :messages="$errors->get('course_abbreviation')" class="mt-2" />
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="course_name" class="block text-gray-700 text-md font-bold mb-2">Course Description</label>
                                                                <input type="text" name="course_name" id="course_name" value="{{ $course->course_name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_name') is-invalid @enderror" required>
                                                                <x-input-error :messages="$errors->get('course_name')" class="mt-2" />
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School Year:</label>
                                                                <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                                    <!-- <option value="{{ $departmentToShow->school->id }}">{{ $departmentToShow->school->id }} | {{ $departmentToShow->school->school_name }}</option> -->
                                                                    <option value="{{ $departmentToShow->school->id }}">{{ $departmentToShow->school->abbreviation }}</option>
                                                                </select>
                                                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="department_id" class="block text-gray-700 text-md font-bold mb-2">Department:</label>
                                                                <select id="department_id" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required>
                                                                    <!-- <option value="{{ $departmentToShow->id }}">{{ $departmentToShow->department_id }} | {{ $departmentToShow->department_name }}</option> -->
                                                                    <option value="{{ $departmentToShow->id }}">{{ $departmentToShow->department_abbreviation }}</option>
                                                                </select>
                                                                <x-input-error :messages="$errors->get('id')" class="mt-2" />
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
                                        @if (Auth::user()->hasRole('admin'))
                                            @if (Auth::user()->hasRole('admin'))
                                                <form id="deleteSelected" action="{{ route('admin.course.destroy', [':id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $course->id }}', '{{ $course->course_abbreviation}}', '{{ $course->course_name}}');">
                                            @else
                                                <form id="deleteSelected" action="{{ route('staff.course.destroy', [':id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $course->id }}', '{{ $course->course_abbreviation}}', '{{ $course->course_name}}');">
                                            @endif
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-500 text-white text-sm px-2 py-1 rounded hover:bg-red-700" id="hehe">
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
                @if($departmentToShow)
                    <tr>
                        <td colspan="2">
                            <div class="flex justify-between">
                                <div class="uppercase text-black mt-2 text-sm mb-4">
                                    @if($search)
                                        {{ $courses->total() }} Search results 
                                    @endif                                    
                                </div>
                                <div class="justify-end">
                                    <p class="text-black mt-2 text-sm mb-4 uppercase">Total # of Courses: <text class="ml-2">{{ $departmentCounts[$departmentToShow->id]->employee_count ?? 0 }}</text></p>
                                    @if($search)
                                        <p><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
                                    @endif
                                </div>
                            </div> 
                        </td>
                    </tr>
                @endif
            </div>
            
            <text  class="font-bold uppercase">{{ $courses->links() }}</text>
        @endif
    @else
        
    @endif
   
</div>


<script>

    document.addEventListener('DOMContentLoaded', function() {
        tippy('[data-tippy-content]', {
            allowHTML: true,
            theme: 'light', // Optional: Change the tooltip theme (light, dark, etc.)
            placement: 'right-end', // Optional: Adjust tooltip placement
        });
    });

</script> 
<script src="{{asset('assets/js/fancybox.umd.js')}}" defer></script>

<script>
      Fancybox.bind('[data-fancybox]', {
        contentClick: "iterateZoom",
        Images: {
            Panzoom: {
                maxScale: 3,
                },
            initialSize: "fit",
        },
        Toolbar: {
          display: {
            left: ["infobar"],
            middle: [
              "zoomIn",
              "zoomOut",
              "toggle1to1",
              "rotateCCW",
              "rotateCW",
              "flipX",
              "flipY",
            ],
            right: ["slideshow", "download", "thumbs", "close"],
          },
        },
      });    
</script>


<script>
    function confirmDeleteAll(event) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: 'Select Employee to Delete All Records',
            html: `
            
                <select id="department_id_select" class="cursor-pointer hover:border-red-500 swal2-select">
                    <option value="">Select Department</option>
                     @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department_id }} | {{ $department->department_abbreviation }} - {{ $department->department_name }}</option>
                        @endforeach
                </select>
            `,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete all!',
            preConfirm: () => {
                const departmentId = Swal.getPopup().querySelector('#department_id_select').value;
                if (!departmentId) {
                    Swal.showValidationMessage(`Please select a department`);
                }
                return { departmentId: departmentId };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const schoolId = result.value.schoolId;
                document.getElementById('department_id_to_delete').value = schoolId;
                document.getElementById('deleteAll').submit();
            }
        });
    }

    function ConfirmDeleteSelected(event, rowId, courseAbbrv, courseDes) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: `Are you sure you want to delete the course ${courseDes} (${courseAbbrv}) ?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteSelected');
                // Replace the placeholders with the actual rowId and employeeId
                const actionUrl = form.action.replace(':id', rowId);
                form.action = actionUrl;
                form.submit();
            }
        });

        return false; 
    }
</script>
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}" defer></script>
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
<script>
function handleImageError(image) {
    // Set the default image
    image.src = "{{ asset('assets/img/user.png') }}";
    
    // Display the error message
    document.getElementById('errorMessage').style.display = 'block';
}
</script>

<script>
         function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
<!--  -->