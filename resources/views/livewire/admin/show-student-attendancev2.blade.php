<div class="mb-4">
        @php
            session(['selectedSchool' => $selectedSchool]);
            session(['selectedDepartment5' => $selectedDepartment5]);
            session(['selectedCourse5' => $selectedCourse5]);
            session(['selectedStudent5' => $selectedStudent5]);
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

    @if(Auth::check() && Auth::user()->hasRole('sao'))
        
        <div class="flex justify-between mb-4 sm:-mt-4">
            <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Student Affair Officer / Manage Student</div>
        </div>

        <div class="flex items-center space-x-4">
           @if($schoolToShow)
                <p class="text-black mt-2 text-sm mb-1">School: 
                    <span class="text-red-500 ml-2 font-bold uppercase">{{ $schoolToShow->abbreviation }}</span>
                </p>
            @endif


                @if(!empty($selectedSchool))
                    <label for="department_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Department:</label>
                    <select wire:model="selectedDepartment5" id="department_id" name="department_id"
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
                        @php
                            $cleanedAbbreviation = str_replace('- student', '', $departmentToShow->department_abbreviation);
                        @endphp

                        <p class="text-black mt-2 text-sm mb-1">
                            Selected Department: 
                            <span class="text-red-500 ml-2">{{ $cleanedAbbreviation }}</span>
                        </p>

                        <!-- <p class="text-black text-sm ml-4">Selected Department: <span class="text-red-500 ml-2">{{ $departmentToShow->department_name }}</span></p> -->
                    @endif
                @endif

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
            <label for="course_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Display student by courses:</label>
            <select wire:model="selectedCourse5" id="course_id" name="course_id"
                    wire:change="updateStudentsByCourse"
                    class="cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror md:w-auto"
                    required>
                @if($courses->isEmpty())
                    <option value="0">No Courses yet</option>
                @else
                    <option value="">Select Course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_id }} | {{ $course->course_name }}({{ $course->course_abbreviation }})</option>
                    @endforeach
                @endif
            </select>

            @if($selectedCourseToShow)
                <p>Selected Course: <span class="text-red-500">{{ $selectedCourseToShow->course_id }} - {{ $selectedCourseToShow->course_name }}({{ $selectedCourseToShow->course_abbreviation}})</span></p>
            @endif

            @if($selectedCourseToShow)

                    <div class="flex flex-col w-full ">

                        <div x-data="{ searchTerm: '' }">
                            <!-- Search Input -->
                            <div class="flex items-end justify-end mb-5">
                                <input 
                                    type="text" 
                                    x-model="searchTerm" 
                                    wire:model.live="searchTerm" 
                                    placeholder="Paste ID number.." 
                                    class="border p-2 rounded w-96" 
                                    autofocus
                                />
                                
                                <!-- Clear Button -->
                                <button 
                                    x-show="searchTerm.length > 0" 
                                    @click="searchTerm = ''" 
                                    wire:click="$set('searchTerm', '')"
                                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 ml-5 -mt-10"
                                >
                                    Clear
                                </button>
                            </div>
                        </div>

                        
                        <div x-data="{ showModal: false, selectedStudent: null }">
                            <table class="table-auto w-full text-center text-sm mb-4 divide-y divide-gray-200">
                                <thead class="bg-gray-200 text-black">
                                    <!-- Full-width Heading Row -->
                                    <tr>
                                        <th colspan="5" class="border border-gray-400 px-3 py-2 bg-gray-300 text-xs font-semibold">
                                            (Click specific row to view attendance)
                                        </th>
                                    </tr>
                                    <!-- Column Headers Row -->
                                    <tr>
                                        <th class="border border-gray-400 px-3 py-2 w-60">
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
                                        <th class="border border-gray-400 px-3 py-2">
                                            <button wire:click="sortBy('student_lastname')" class="w-full h-full flex items-center justify-center">
                                                Last Name
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
                                                First Name
                                                @if ($sortField == 'student_firstname')
                                                    @if ($sortDirection == 'asc')
                                                        &nbsp;<i class="fa-solid fa-down-long fa-xs"></i>
                                                    @else
                                                        &nbsp;<i class="fa-solid fa-up-long fa-xs"></i>
                                                    @endif
                                                @endif
                                            </button>
                                        </th>
                                        <th class="border border-gray-400 px-3 py-2">Course</th>
                                        <th class="border border-gray-400 px-3 py-2">Department</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($students->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center border border-gray-400 px-3 py-2">No Students</td>
                                        </tr>
                                    @else
                                        @foreach($students as $student)
                                            <tr class="cursor-pointer hover:bg-gray-100"
                                                @click="showModal = true; 
                                                        selectedStudent = {
                                                            id: '{{ $student->id }}', 
                                                            student_id: '{{ $student->student_id }}', 
                                                            student_lastname: '{{ $student->student_lastname }}', 
                                                            student_firstname: '{{ $student->student_firstname }}', 
                                                            student_rfid: '{{ $student->student_rfid }}',
                                                            course_abbreviation: '{{ $student->course->course_abbreviation }}',
                                                            course_name: '{{ $student->course->course_name }}',
                                                            course_id: '{{ $student->course->id }}', 
                                                            department_abbreviation: '{{ $student->course->department->department_abbreviation }}'
                                                        }; 
                                                        $wire.set('selectedStudent5', {{ $student->id }})" wire:model="selectedStudent5">

                                                <td class="text-black border border-gray-400 px-3 py-2">{{ $student->student_id }}</td>
                                                <td class="text-black border border-gray-400 px-3 py-2">{{ $student->student_lastname }}</td>
                                                <td class="text-black border border-gray-400 px-3 py-2">{{ $student->student_firstname }}</td>
                                                <td class="text-black border border-gray-400 px-3 py-2">{{ $student->course->course_abbreviation }}</td>
                                                <td class="text-black border border-gray-400 px-3 py-2">{{ $student->course->department->department_abbreviation }}</td>
                                            </tr>

                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                            <!-- Display entry count and pagination links -->
                            <div class="flex justify-between items-center my-4 text-sm text-gray-600">
                                <!-- Only display this text once -->
                                <span>Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} entries</span>

                                <!-- Pagination Links -->
                                <div class="flex justify-end">
                                    {{ $students->links() }}
                                </div>
                            </div>

                            <!-- Modal -->
                            <div x-cloak x-show="showModal"
                                x-transition
                                class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50">
                                <div class="bg-white p-6 rounded-lg shadow-lg w-5/6">
                                    <div class="flex justify-between">
                                        <h2 class="text-lg font-bold mb-4">Student Details</h2>
                                        <div class="flex justify-end mt-4">
                                        <button @click="showModal = false; selectedStudent = null"
                                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                            Close
                                        </button>
                                    </div>
                                    </div>
                                    
                                    <template x-if="selectedStudent">
                                        <div>
                                            <p><strong>School ID:</strong> <span x-text="selectedStudent.student_id" class="text-red-500 font-bold"></span></p>
                                            <p><strong>Student Name:</strong> <span x-text="selectedStudent.student_lastname" class="text-red-500 font-bold"></span>, <span x-text="selectedStudent.student_firstname" class="text-red-500 font-bold"></span></p>
                                            <p><strong>Course:</strong> <span x-text="selectedStudent.course_abbreviation" class="text-red-500 font-bold"></span> <span x-text="selectedStudent.course_name" class="text-red-500 font-bold"></span></p>
                                        </div>
                                    </template>
                                    
                                    <div class="mt-10">
                                        @if($selectedStudentToShow)
                                            @if($search && $attendanceTimeIn->isEmpty() && $attendanceTimeOut->isEmpty() && !$selectedAttendanceToShow->isEmpty())
                                                <p class="text-black mt-8 text-center">No attendance/s found in <span class="text-red-500">{{ $selectedStudentToShow->student_id }} - {{ $selectedStudentToShow->student_lastname }}, {{ $selectedStudentToShow->student_firstname }} {{ ucfirst($selectedStudentToShow->student_middlename) }}</span> for matching "{{ $search }}"</p>
                                                <p class="text-center mt-5">
                                                    <button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="clearSearch">
                                                        <i class="fa-solid fa-remove"></i> Clear
                                                    </button>
                                                </p>
                                            @elseif(!$search && $attendanceTimeIn->isEmpty() && $attendanceTimeOut->isEmpty())
                                                <p class="text-black mt-11 text-center uppercase">No Time In and Time Out Recorded!
                                                    <button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('startDate', '')"><i class="fa-solid fa-remove"></i> Clear </button>
                                                </p>
                                            @else

                                                <div class="flex justify-end mt-1 mb-2 mr-10">
                                                    
                                                    <div class="flex flex-col -mt-16">
                                                        <div class="flex justify-end items-center mb-2">
                                                            <div class="grid grid-rows-2 grid-flow-col ml-10">
                                                        
                                                                <div class="text-center uppercase ml-16 font-bold">
                                                                    Select Specific Date
                                                                </div>
                                                                <div class="flex items-center space-x-4">
                                                                    <!-- <label for="startDate" class="text-gray-600">Start Date:</label>
                                                                    <input 
                                                                        id="startDate" 
                                                                        type="date" 
                                                                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                                                        wire:model="startDate"
                                                                        wire:change="updateAttendanceByDateRange"
                                                                    >
                                                                    <label for="endDate" class="text-gray-600">End Date:</label>
                                                                    <input 
                                                                        id="endDate" 
                                                                        type="date" 
                                                                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                                                        wire:model="endDate"
                                                                        wire:change="updateAttendanceByDateRange"
                                                                    > -->
                                                                    <label for="endDate" class="text-gray-600">Select Month of:</label>
                                                                    <select wire:model="selectedMonth" id="selectedMonth" name="selectedMonth"
                                                                            wire:change="updateMonth"
                                                                            class="mr-5 cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline md:w-auto"
                                                                            required>
                                                                        <option value="">-- Select Month --</option>
                                                                        @foreach($months as $key => $monthName)
                                                                            <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $monthName }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <button wire:click="generatePDF"  wire:loading.attr="disabled" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                        <i class="fa-solid fa-file"></i> Generate PDF 
                                                                    </button>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="overflow-x-auto">
                                                    @if ($attendanceTimeIn->isNotEmpty())
                                                        @php
                                                            $groupedAttendance = [];

                                                            // Step 1: Process attendanceTimeIn
                                                            $attendanceInRecords = [];
                                                            foreach ($attendanceTimeIn as $attendanceIn) {
                                                                $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
                                                                $studentId = $attendanceIn->student_id;

                                                                $attendanceInRecords[] = (object) [
                                                                    'date' => $date,
                                                                    'student_id' => $studentId,
                                                                    'check_in_time' => $attendanceIn->check_in_time,
                                                                    'check_out_time' => null
                                                                ];
                                                            }

                                                            // Step 2: Process attendanceTimeOut
                                                            $attendanceOutRecords = [];
                                                            foreach ($attendanceTimeOut as $attendanceOut) {
                                                                $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
                                                                $studentId = $attendanceOut->student_id;

                                                                $attendanceOutRecords[] = (object) [
                                                                    'date' => $date,
                                                                    'student_id' => $studentId,
                                                                    'check_out_time' => $attendanceOut->check_out_time
                                                                ];
                                                            }

                                                            // Step 3: Pair Check-in and Check-out in Sequence
                                                            foreach ($attendanceInRecords as $inRecord) {
                                                                $date = $inRecord->date;
                                                                $studentId = $inRecord->student_id;

                                                                if (!isset($groupedAttendance[$date])) {
                                                                    $groupedAttendance[$date] = [];
                                                                }

                                                                // Find the first unmatched attendanceOut record
                                                                foreach ($attendanceOutRecords as $key => $outRecord) {
                                                                    if ($outRecord->date == $date && $outRecord->student_id == $studentId) {
                                                                        $inRecord->check_out_time = $outRecord->check_out_time;

                                                                        // Remove the matched check-out record to avoid duplication
                                                                        unset($attendanceOutRecords[$key]);
                                                                        break;
                                                                    }
                                                                }

                                                                $groupedAttendance[$date][] = $inRecord;
                                                            }
                                                        @endphp

                                                                <div class="flex">
                                                                    <div class="w-full border border-gray-400">
                                                                        <table class="w-full table-fixed border-collapse">
                                                                            <thead class="bg-gray-200">
                                                                                <tr>
                                                                                    <th class="text-black border border-gray-400 w-1/3 text-center">Date</th>
                                                                                    <th class="text-black border border-gray-400 w-1/3 text-center">Check-In Time (On-Campus)</th>
                                                                                    <th class="text-black border border-gray-400 w-1/3 text-center">Check-Out Time (Off-Campus)</th>
                                                                                </tr>
                                                                            </thead>
                                                                        </table>
                                                                        <div class="overflow-y-auto max-h-96">
                                                                            <table class="w-full table-fixed border-collapse">
                                                                                <tbody>
                                                                                    @foreach ($groupedAttendance as $date => $records)
                                                                                        @foreach ($records as $index => $record)
                                                                                            <tr class="hover:bg-gray-100">
                                                                                                @if ($index == 0)
                                                                                                    <!-- Only show the date once for the first record in each date group -->
                                                                                                    <td class="text-black border text-center font-bold border-gray-400 align-middle w-1/3" rowspan="{{ count($records) }}">
                                                                                                        {{ date('m-d-Y (l)', strtotime($date)) }}
                                                                                                    </td>
                                                                                                @endif
                                                                                                <td class="text-black border border-gray-400 w-1/3 text-center align-middle">
                                                                                                    @if ($record->check_in_time)
                                                                                                        {{ date('g:i:s A', strtotime($record->check_in_time)) }}
                                                                                                    @else
                                                                                                        No check-in recorded
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td class="text-black border border-gray-400 w-1/3 text-center align-middle">
                                                                                                    @if ($record->check_out_time)
                                                                                                        {{ date('g:i:s A', strtotime($record->check_out_time)) }}
                                                                                                    @else
                                                                                                        No check-out recorded
                                                                                                    @endif
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @else
                                                                <p>No Time In & Time Out Records found.</p>
                                                            @endif
                                                        </div>


                                             
                                            @endif
                                        @else
                                            <p>No selected Student</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            @else
                @if($courses->isEmpty())
                    <p class="text-black text-sm mt-11 mb-4 uppercase text-center">Add Course first in the department</p>
                @else
                    <p class="text-black text-sm mt-11 mb-4 uppercase text-center">No selected Course</p>
                @endif
                
            @endif

        @endif
    @endif                                                        
</div>
    
<!--  -->