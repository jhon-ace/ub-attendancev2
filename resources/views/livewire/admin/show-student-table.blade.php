<div class="mb-4">
        @php
            session(['selectedSchool' => $selectedSchool]);
            session(['selectedDepartment3' => $selectedDepartment3]);
            session(['selectedCourse' => $selectedCourse]);
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
        <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Admin / Manage Student</div>
    @else
        <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Staff / Manage Student</div>
    @endif
    </div>

        <div class="flex flex-column overflow-x-auto -mb-5">
            <div class="col-span-3 p-4">
                @if($schoolToShow)
                        <p class="w-64 text-black mt-10 text-sm mb-1">School: <span class="text-red-500 ml-2 font-bold uppercase">{{ $schoolToShow->abbreviation }}</span></p>
                @endif
            </div>

            <div class="col-span-1 p-4">
                @if(!empty($selectedSchool))
                    <label for="department_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Department:</label>
                    <select wire:model="selectedDepartment3" id="department_id" name="department_id"
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
                        <p class="text-black mt-2 text-sm mb-1">Department: <span class="text-red-500 ml-2">{{ $departmentToShow->department_abbreviation }}</span></p>
                        <!-- <p class="text-black text-sm ml-4">Selected Department: <span class="text-red-500 ml-2">{{ $departmentToShow->department_name }}</span></p> -->
                    @endif
                @endif
            </div>
            <div class="col-span-1 -ml-2 pt-4 mt-6 "> or </div>
            <div class="col-span-1 p-4 mt-5">
                <div class="justify-end">
                    <input wire:model.live="search" type="text" class="text-sm border text-black border-gray-300 rounded-md px-3 ml-2 py-1.5 w-full md:w-64" placeholder="Search student directly..." autofocus>
                </div>
            </div>
            <div class="justify-end mt-9 w-full">
                @if($search)
                    <p><button class=" ml-2 border rounded-md border-gray-600 px-3 py-1 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
                @endif
            </div>
            <div class="flex justify-end  mt-8 w-full">
                <div x-data="{ open: false }">
                    <button @click="open = true" class="-mt-1 mb-2 bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                     
                        <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Student in any course
                    </button>
                    <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <div  class="w-[50%] bg-white p-6 rounded-lg shadow-lg mx-auto max-h-[90vh] overflow-y-auto">
                            <div class="flex justify-between items-center pb-3">
                                <p class="text-xl font-bold">Add Student</p>
                                <button @click="open = false" class="text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                            </div>
                            <div class="mb-4">
                            @if (Auth::user()->hasRole('admin'))
                                <form action="{{ route('admin.student.store') }}" method="POST" class="" enctype="multipart/form-data">
                            @else
                                <form action="{{ route('staff.student.store') }}" method="POST" class="" enctype="multipart/form-data">
                            @endif
                                <x-caps-lock-detector />
                                    @csrf
<!--  -->
                                    <div class="mb-2">
                                        <input type="file" name="student_photo" id="student_photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                                        <label for="student_photo" class="cursor-pointer flex flex-col items-center">
                                            <div id="imagePreviewContainer" class="mb-2 text-center">
                                                <img id="imagePreview" src="{{ asset('assets/img/user.png') }}" class="rounded-lg w-32 h-auto">
                                            </div>
                                            <span class="text-sm text-gray-500">Select Photo</span>
                                        </label>
                                        <x-input-error :messages="$errors->get('student_photo')" class="mt-2" />
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="mb-2">
                                            <label for="student_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Student ID</label>
                                            <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_id') is-invalid @enderror" required autofocus>
                                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="student_lastname" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Lastname</label>
                                            <input type="text" name="student_lastname" id="student_lastname" value="{{ old('student_lastname') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_lastname') is-invalid @enderror" required autofocus>
                                            <x-input-error :messages="$errors->get('student_lastname')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="student_firstname" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Firstname</label>
                                            <input type="text" name="student_firstname" id="student_firstname" value="{{ old('student_firstname') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_firstname') is-invalid @enderror" required autofocus>
                                            <x-input-error :messages="$errors->get('student_firstname')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="student_middlename" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Middlename</label>
                                            <input type="text" name="student_middlename" id="student_middlename" value="{{ old('student_middlename') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_middlename') is-invalid @enderror" required>
                                            <x-input-error :messages="$errors->get('student_middlename')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="student_rfid" class="block text-gray-700 text-md font-bold mb-2 text-left">Student RFID No</label>
                                            <input type="text" name="student_rfid" id="student_rfid" value="{{ old('student_rfid') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_rfid') is-invalid @enderror" required>
                                            <x-input-error :messages="$errors->get('student_rfid')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="student_year_grade" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Year/Grade</label>
                                            <input type="text" name="student_year_grade" id="student_year_grade" value="{{ old('student_year_grade') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_year_grade') is-invalid @enderror" required>
                                            <x-input-error :messages="$errors->get('student_year_grade')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="student_status" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Status</label>
                                            <input type="text" name="student_status" id="student_status" value="{{ old('student_status') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_status') is-invalid @enderror" required>
                                            <x-input-error :messages="$errors->get('student_status')" class="mt-2" />
                                        </div>
                                        <div class="mb-2">
                                            <label for="course_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Course:</label>
                                            <select id="course_id" name="course_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('course_id') is-invalid @enderror" required>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->course_abbreviation }} - {{ $course->course_name }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                                        </div>
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
        <!-- s -->
        @if($search && $students->isEmpty())
            @if ($search)
                <p class="text-black mt-8 text-center">No student/s found for matching "{{ $search }}"</p>
            @else
                <p class="text-black mt-8 text-center">No student/s found for matching "{{ $search }}"</p>
            @endif
        @elseif(!$search && $students->isEmpty())
            @if ($search)
                <p class="text-black mt-8 text-center uppercase">No data available in <text class="text-red-500">{{$departmentToShow->department_abbreviation}} - {{ $departmentToShow->department_name }} department.</text></p>
            @else

            @endif
        @elseif($search && $students->isNotEmpty())
            <div class="overflow-x-auto mt-10">
                <div class="flex justify-center mb-2">
                    <p>Search Result</p>
                </div>
                <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                    <thead class="bg-gray-200 text-black">
                        <tr>
                            <th class="border border-gray-400 px-3 py-2">

                                <button wire:click="sortBy('student_id')" class="w-full h-full flex items-center justify-center">
                                    Student ID
                                    @if ($sortField == 'student_id')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">Student Photo</th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('student_lastname')" class="w-full h-full flex items-center justify-center">
                                    Student Lastname
                                    @if ($sortField == 'student_lastname')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('student_firstname')" class="w-full h-full flex items-center justify-center">
                                    Student Firstname
                                    @if ($sortField == 'student_firstname')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('student_middlename')" class="w-full h-full flex items-center justify-center">
                                    Student Middlename
                                    @if ($sortField == 'student_middlename')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('student_rfid')" class="w-full h-full flex items-center justify-center">
                                    Student RFID No
                                    @if ($sortField == 'student_rfid')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('student_year_grade')" class="w-full h-full flex items-center justify-center">
                                    Student Year/Grade Level
                                    @if ($sortField == 'student_year_grade')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="border border-gray-400 px-3 py-2">
                                <button wire:click="sortBy('student_status')" class="w-full h-full flex items-center justify-center">
                                    Student Status
                                    @if ($sortField == 'student_status')
                                        @if ($sortDirection == 'asc')
                                            &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                        @else
                                            &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <!-- <th class="border border-gray-400 px-3 py-2">Course ID</th> -->
                            <th class="border border-gray-400 px-3 py-2">Course</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                                <tr class="hover:bg-gray-100" wire:model="selectedDepartment">
                                    <td class="text-black border border-gray-400">{{ $student->student_id}}</td>
                                    <td class="text-black border border-gray-400 border-t-0 border-r-0 border-l-0 px-2 py-1 flex items-center justify-center" >
                                        @if ($student->student_photo && Storage::exists('public/student_photo/' . $student->student_photo))
                                            <a  href="{{ asset('storage/student_photo/' . $student->student_photo) }}" 
                                                class="hover:border border-red-500 rounded-full" title="Click to view Picture"
                                                data-fancybox data-caption="Student: {{ $student->student_lastname }}, {{ $student->student_firstname }} {{ucfirst($student->student_middlename)}}">
                                                <img src="{{ asset('storage/student_photo/' . $student->student_photo) }}" class="rounded-full w-9 h-9">
                                            </a>
                                        @else
                                            <img data-fancybox src="{{ asset('assets/img/user.png') }}" class="cursor-pointer w-9 h-9 hover:border hover:border-red-500 rounded-full" title="Click to view Picture" >
                                        @endif
                                    </td>
                                    <td class="text-black border border-gray-400">{{ $student->student_lastname }}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_firstname }}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_middlename }}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_rfid}}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_year_grade }}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_status }}</td>
                                    <!-- <td class="text-black border border-gray-400 text-xs">{{ $student->course->course_id}}</td> -->
                                    <td class="text-black border border-gray-400 text-xs">{{ $student->course->course_abbreviation}}</td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
                <div class="flex justify-between">
                    <div class="uppercase text-black mt-2 text-sm mb-4">
                        @if($search)
                            {{ $students->total() }} Search results
                        @endif
                    </div>
                </div>
                <text class="font-bold uppercase">{{ $students->links() }}</text>
            </div>
        @endif
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
        <label for="course_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Course:</label>
        <select wire:model="selectedCourse" id="course_id" name="course_id"
                wire:change="updateStudentsByCourse"
                class="cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror md:w-auto"
                required>
            @if($courses->isEmpty())
                <option value="0">No Course</option>
            @else
                <option value="">Select Course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->course_name }}({{ $course->course_abbreviation }})</option>
                @endforeach
            @endif
        </select>

        @if($selectedCourseToShow)
            <p>Selected Course: <text class="text-red-500">{{ $selectedCourseToShow->course_name }}({{ $selectedCourseToShow->course_abbreviation}})</text></p>
        @endif

        @if($selectedCourseToShow)
            @if($search && $students->isEmpty())
                <p class="text-black mt-8 text-center">No student/s found in <span class="text-red-500">{{ $selectedCourseToShow->course_id }} - {{ $selectedCourseToShow->course_name }}({{ $selectedCourseToShow->course_abbreviation}})</span> for matching "{{ $search }}"</p>
                <p class="text-center mt-5"><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
            @elseif(!$search && $students->isEmpty())
                <p class="text-black mt-8 text-center uppercase">No data available in <text class="text-red-500">{{ $selectedCourseToShow->course_name }}({{ $selectedCourseToShow->course_abbreviation}}) course.</text></p>
                <div class="flex justify-center items-center mt-5">
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="-mt-1 mb-2 bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                            <!-- <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> {{$departmentToShow->department_id}} - {{$departmentToShow->department_name}} -->
                            <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Student in {{$selectedCourseToShow->course_name}} Course
                        </button>
                        <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div  class="w-[35%] bg-white p-6 rounded-lg shadow-lg mx-auto max-h-[90vh] overflow-y-auto">
                                <div class="flex justify-between items-center pb-3">
                                    <p class="text-xl font-bold">Add Student</p>
                                    <button @click="open = false" class="text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                                </div>
                                <div class="mb-4">
                                @if (Auth::user()->hasRole('admin'))
                                    <form action="{{ route('admin.student.store') }}" method="POST" class="" enctype="multipart/form-data">
                                @else
                                    <form action="{{ route('staff.student.store') }}" method="POST" class="" enctype="multipart/form-data">
                                @endif
                                        <x-caps-lock-detector />
                                        @csrf

                                        <div class="mb-2">
                                            <input type="file" name="student_photo" id="student_photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                                            <label for="student_photo" class="cursor-pointer flex flex-col items-center">
                                                <div id="imagePreviewContainer" class="mb-2 text-center">
                                                    <img id="imagePreview" src="{{ asset('assets/img/user.png') }}" class="rounded-lg w-32 h-auto">
                                                </div>
                                                <span class="text-sm text-gray-500">Select Photo</span>
                                            </label>
                                            <x-input-error :messages="$errors->get('student_photo')" class="mt-2" />
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div class="mb-2">
                                                <label for="student_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Student ID</label>
                                                <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_id') is-invalid @enderror" required autofocus>
                                                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                                            </div>
                                            <div class="mb-2">
                                                <label for="student_lastname" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Lastname</label>
                                                <input type="text" name="student_lastname" id="student_lastname" value="{{ old('student_lastname') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_lastname') is-invalid @enderror" required autofocus>
                                                <x-input-error :messages="$errors->get('student_lastname')" class="mt-2" />
                                            </div>
                                            <div class="mb-2">
                                                <label for="student_firstname" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Firstname</label>
                                                <input type="text" name="student_firstname" id="student_firstname" value="{{ old('student_firstname') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_firstname') is-invalid @enderror" required autofocus>
                                                <x-input-error :messages="$errors->get('student_firstname')" class="mt-2" />
                                            </div>
                                            <div class="mb-2">
                                                <label for="student_middlename" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Middlename</label>
                                                <input type="text" name="student_middlename" id="student_middlename" value="{{ old('student_middlename') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_middlename') is-invalid @enderror" required>
                                                <x-input-error :messages="$errors->get('student_middlename')" class="mt-2" />
                                            </div>
                                            <div class="mb-2">
                                                <label for="student_rfid" class="block text-gray-700 text-md font-bold mb-2 text-left">Student RFID No</label>
                                                <input type="text" name="student_rfid" id="student_rfid" value="{{ old('student_rfid') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_rfid') is-invalid @enderror" required>
                                                <x-input-error :messages="$errors->get('student_rfid')" class="mt-2" />
                                            </div>
                                            <div class="mb-2">
                                                <label for="student_year_grade" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Year/Grade</label>
                                                <input type="text" name="student_year_grade" id="student_year_grade" value="{{ old('student_year_grade') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_year_grade') is-invalid @enderror" required>
                                                <x-input-error :messages="$errors->get('student_year_grade')" class="mt-2" />
                                            </div>
                                            <div class="mb-2">
                                                <label for="student_status" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Status</label>
                                                <input type="text" name="student_status" id="student_status" value="{{ old('student_status') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_status') is-invalid @enderror" required>
                                                <x-input-error :messages="$errors->get('student_status')" class="mt-2" />
                                            </div>
                                            <div class="mb-2">
                                                <label for="course_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Course:</label>
                                                <select id="course_id" name="course_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('course_id') is-invalid @enderror" required>
                                                    <option value="{{ $selectedCourseToShow->id }}">{{ $selectedCourseToShow->course_abbreviation }}</option>
                                                    @foreach($coursesAll as $course)
                                                        <option value="{{ $course->id }}">{{ $course->course_abbreviation }} - {{ $course->course_name }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                                            </div>
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
                                                                        <!-- Data is present -->
                <div class="flex justify-between">
                    <div class="">
                        <!-- delete area -->
                    </div>
                    <div class="flex justify-center items-center">
                        <div x-data="{ open: false }">
                            <button @click="open = true" class="-mt-1 mb-2 bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                <!-- <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> {{$departmentToShow->department_id}} - {{$departmentToShow->department_name}} -->
                                 <!-- {{$selectedCourseToShow->course_id}} {{$selectedCourseToShow->course_name}}  -->
                                <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Student in {{$selectedCourseToShow->course_name}}
                            </button>
                            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                <div  class="w-[35%] bg-white p-6 rounded-lg shadow-lg mx-auto max-h-[90vh] overflow-y-auto">
                                    <div class="flex justify-between items-center pb-3">
                                        <p class="text-xl font-bold">Add Student</p>
                                        <button @click="open = false" class="text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                                    </div>
                                    <div class="mb-4">
                                    @if (Auth::user()->hasRole('admin'))
                                        <form action="{{ route('admin.student.store') }}" method="POST" class="" enctype="multipart/form-data">
                                    @else
                                        <form action="{{ route('staff.student.store') }}" method="POST" class="" enctype="multipart/form-data">
                                    @endif
                                            <x-caps-lock-detector />
                                            @csrf

                                            <div class="mb-2">
                                                <input type="file" name="student_photo" id="student_photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                                                <label for="student_photo" class="cursor-pointer flex flex-col items-center">
                                                    <div id="bla2" class="mb-2 text-center">
                                                        <img id="imagePreview" src="{{ asset('assets/img/user.png') }}" class="rounded-lg w-32 h-auto">
                                                    </div>
                                                    <span class="text-sm text-gray-500">Select Photo</span>
                                                </label>
                                                <x-input-error :messages="$errors->get('student_photo')" class="mt-2" />
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div class="mb-2">
                                                    <label for="student_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Student ID</label>
                                                    <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_id') is-invalid @enderror" required autofocus>
                                                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="student_lastname" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Lastname</label>
                                                    <input type="text" name="student_lastname" id="student_lastname" value="{{ old('student_lastname') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_lastname') is-invalid @enderror" required autofocus>
                                                    <x-input-error :messages="$errors->get('student_lastname')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="student_firstname" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Firstname</label>
                                                    <input type="text" name="student_firstname" id="student_firstname" value="{{ old('student_firstname') }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_firstname') is-invalid @enderror" required autofocus>
                                                    <x-input-error :messages="$errors->get('student_firstname')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="student_middlename" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Middlename</label>
                                                    <input type="text" name="student_middlename" id="student_middlename" value="{{ old('student_middlename') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_middlename') is-invalid @enderror" required>
                                                    <x-input-error :messages="$errors->get('student_middlename')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="student_rfid" class="block text-gray-700 text-md font-bold mb-2 text-left">Student RFID No</label>
                                                    <input type="text" name="student_rfid" id="student_rfid" value="{{ old('student_rfid') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_rfid') is-invalid @enderror" required>
                                                    <x-input-error :messages="$errors->get('student_rfid')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="student_year_grade" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Year/Grade</label>
                                                    <input type="text" name="student_year_grade" id="student_year_grade" value="{{ old('student_year_grade') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_year_grade') is-invalid @enderror" required>
                                                    <x-input-error :messages="$errors->get('student_year_grade')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="student_status" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Status</label>
                                                    <input type="text" name="student_status" id="student_status" value="{{ old('student_status') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_status') is-invalid @enderror" required>
                                                    <x-input-error :messages="$errors->get('student_status')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="course_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Course:</label>
                                                    <select id="course_id" name="course_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('course_id') is-invalid @enderror" required>
                                                        <option value="{{ $selectedCourseToShow->id }}">{{ $selectedCourseToShow->course_abbreviation }}</option>
                                                        @foreach($courses as $course)
                                                            <option value="{{ $course->id }}">{{ $course->course_abbreviation }} - {{ $course->course_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                                                </div>
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
                    <div class="mt-2 text-sm font-bold ">
                        <text class="uppercase">Student List in {{$selectedCourseToShow->course_name}}</text>({{$selectedCourseToShow->course_abbreviation}})  Course
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

                                    <button wire:click="sortBy('student_id')" class="w-full h-full flex items-center justify-center">
                                        Student ID
                                        @if ($sortField == 'student_id')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">Student Photo</th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('student_lastname')" class="w-full h-full flex items-center justify-center">
                                        Student Lastname
                                        @if ($sortField == 'student_lastname')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('student_firstname')" class="w-full h-full flex items-center justify-center">
                                        Student Firstname
                                        @if ($sortField == 'student_firstname')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('student_middlename')" class="w-full h-full flex items-center justify-center">
                                        Student Middlename
                                        @if ($sortField == 'student_middlename')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('student_rfid')" class="w-full h-full flex items-center justify-center">
                                        Student RFID No
                                        @if ($sortField == 'student_rfid')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('student_year_grade')" class="w-full h-full flex items-center justify-center">
                                        Student Year/Grade Level
                                        @if ($sortField == 'student_year_grade')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th class="border border-gray-400 px-3 py-2">
                                    <button wire:click="sortBy('student_status')" class="w-full h-full flex items-center justify-center">
                                        Student Status
                                        @if ($sortField == 'student_status')
                                            @if ($sortDirection == 'asc')
                                                &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                            @else
                                                &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <!-- <th class="border border-gray-400 px-3 py-2">Course ID</th> -->
                                 <th class="border border-gray-400 px-3 py-2">Course</th>
                                <th class="border border-gray-400 px-3 py-2">Department</th>
                                <th class="border border-gray-400 px-3 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach ($students as $student)
                                <tr class="hover:bg-gray-100" wire:model="selectedDepartment">
                                    <td class="text-black border border-gray-400">{{ $student->student_id}}</td>
                                    <td class="text-black border border-gray-400 border-t-0 border-r-0 border-l-0 px-2 py-1 flex items-center justify-center" >
                                        @if ($student->student_photo && Storage::exists('public/student_photo/' . $student->student_photo))
                                            <a  href="{{ asset('storage/student_photo/' . $student->student_photo) }}" 
                                                class="hover:border border-red-500 rounded-full" title="Click to view Picture"
                                                data-fancybox data-caption="Student: {{ $student->student_lastname }}, {{ $student->student_firstname }} {{ucfirst($student->student_middlename)}}">
                                                <img src="{{ asset('storage/student_photo/' . $student->student_photo) }}" class="rounded-full w-9 h-9">
                                            </a>
                                        @else
                                            <img data-fancybox src="{{ asset('assets/img/user.png') }}" class="cursor-pointer w-9 h-9 hover:border hover:border-red-500 rounded-full" title="Click to view Picture" >
                                        @endif
                                    </td>
                                    <td class="text-black border border-gray-400">{{ $student->student_lastname }}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_firstname }}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_middlename }}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_rfid}}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_year_grade }}</td>
                                    <td class="text-black border border-gray-400">{{ $student->student_status }}</td>
                                    <td class="text-black border border-gray-400 text-xs">{{ $student->course->course_abbreviation}}</td>
                                    <td class="text-black border border-gray-400 text-xs">{{ $student->course->department->department_abbreviation}}</td>
                                    <td class="text-black border border-gray-400">
                                        <div class="flex justify-center items-center space-x-2">
                                            <div x-data="{ open: false
                                                    }">
                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-[5px] rounded hover:bg-blue-700">
                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i>
                                                </a>
                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg max-h-[90vh] overflow-y-auto  mx-auto z-50">
                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                            <p class="text-xl font-bold">Edit Student</p>
                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                        </div>
                                                        <div class="mb-4">
                                                        @if (Auth::user()->hasRole('admin'))
                                                            <form action="{{ route('admin.student.update', $student->id) }}" method="POST" class="" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to update this student\'s information?')">
                                                        @else
                                                            <form action="{{ route('staff.student.update', $student->id) }}" method="POST" class="" enctype="multipart/form-data">
                                                        @endif  
                                                            
                                                                <x-caps-lock-detector />
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="mb-4 text-center flex flex-col items-center">
                                                                    <img id="blah2" src="{{ $student->student_photo ? asset('storage/student_photo/' . $student->student_photo) : asset('assets/img/user.png') }}" alt="Default photo Icon" class="max-w-xs mb-2" />
                                                                    <input type="file" onchange="readURL2(this);" name="student_photo" id="student_photo" class="p-2 bg-gray-800 text-white" accept="image/*" />
                                                                </div>
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                                    <div class="mb-2">
                                                                        <label for="student_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Student ID</label>
                                                                        <input type="text" name="student_id" id="student_id" value="{{ $student->student_id }}"  class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_id') is-invalid @enderror" required autofocus>
                                                                        <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="student_lastname" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Lastname</label>
                                                                        <input type="text" name="student_lastname" id="student_lastname" value="{{ $student->student_lastname }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_lastname') is-invalid @enderror" required autofocus>
                                                                        <x-input-error :messages="$errors->get('student_lastname')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="student_firstname" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Firstname</label>
                                                                        <input type="text" name="student_firstname" id="student_firstname" value="{{ $student->student_firstname }}" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_firstname') is-invalid @enderror" required autofocus>
                                                                        <x-input-error :messages="$errors->get('student_firstname')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="student_middlename" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Middlename</label>
                                                                        <input type="text" name="student_middlename" id="student_middlename" value="{{ $student->student_middlename }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_middlename') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('student_middlename')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="student_rfid" class="block text-gray-700 text-md font-bold mb-2 text-left">Student RFID No</label>
                                                                        <input type="text" name="student_rfid" id="student_rfid" value="{{ $student->student_rfid }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_rfid') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('student_rfid')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="student_year_grade" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Year/Grade</label>
                                                                        <input type="text" name="student_year_grade" id="student_year_grade" value="{{ $student->student_year_grade }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_year_grade') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('student_year_grade')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="student_status" class="block text-gray-700 text-md font-bold mb-2 text-left">Student Status</label>
                                                                        <input type="text" name="student_status" id="student_status" value="{{ $student->student_status }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_status') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('student_status')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="course_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Course Name</label>
                                                                        <select id="course_id" name="course_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('course_id') is-invalid @enderror" required>
                                                                            <option selected value="{{ $selectedCourseToShow->id }}">{{ $selectedCourseToShow->course_abbreviation }}</option>
                                                                            @foreach($coursesAll as $course)
                                                                                <option value="{{ $course->id }}">{{ $course->course_abbreviation }} - {{ $course->course_name }}</option>
                                                                            @endforeach
                                                                        
                                                                         
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                                                                    </div>
                                                                    
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="department_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Department</label>
                                                                    <select id="department_id" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required>
                                                                        <option selected value="{{ $departmentToShow->id }}">{{ $departmentToShow->department_abbreviation }}</option>
                                                                        @foreach($departments as $department)
                                                                            <option value="{{ $department->id }}">{{ $department->department_abbreviation }}</option>
                                                                        @endforeach
                                                                    
                                                                    </select>
                                                                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                                                </div>
                                                                <div class="flex mb-4 mt-10 justify-center">
                                                                    <span class="text-red-500 font-bold">Note: </span>  Review course and department, make sure selected course is belong in the selected department first before saving.
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
                                            <form id="deleteSelected" action="{{ route('admin.student.destroy', [':id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $student->id }}', '{{ $student->student_lastname }}', '{{ $student->student_firstname }}', '{{ $student->student_middlename }}');">
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
                                            {{ $students->total() }} Search results 
                                        @endif                                    
                                    </div>
                                    <div class="justify-end">
                                        <p class="text-black mt-2 text-sm mb-4 uppercase">Total # of Student: <text class="ml-2">{{ $studentsCounts[$selectedCourseToShow->id]->student_count ?? 0 }}</text></p>
                                        @if($search)
                                            <p>
                                                <button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="clearSearch">
                                                    <i class="fa-solid fa-remove"></i> Clear Search
                                                </button>
                                            </p>
                                        @endif
                                    </div>
                                </div> 
                            </td>
                        </tr>
                    @endif
                </div>
                <text  class="font-bold uppercase">{{ $students->links() }}</text>
            @endif
        @else
            @if($courses->isEmpty())
                <p class="text-black text-sm mt-11 mb-4 uppercase text-center">Add Course first in the department</p>
            @else
                <p class="text-black text-sm mt-11 mb-4 uppercase text-center">No selected Course</p>
            @endif
            
        @endif

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

    function ConfirmDeleteSelected(event, rowId, studentLastname, studentFirstname, studentMiddlename) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: `Are you sure you want to delete this student:  ${studentLastname}, ${studentFirstname} ${studentMiddlename}?`,
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
<script>
         function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah2')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
<!--  -->