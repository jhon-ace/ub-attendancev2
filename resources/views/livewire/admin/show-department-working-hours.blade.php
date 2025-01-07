<div>
    @php
        session(['selectedSchool' => $selectedSchool]);
        session(['selectedDepartment' => $selectedDepartment]);
        session(['showSelectedDepartment' => $showSelectedDepartment]);
    @endphp

    @if(Auth::user()->hasRole('admin'))
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
                <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Admin / Manage Work Schedule</div>
            @else
                <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Staff / Manage Work Schedule</div>
            @endif
        </div>
        <div class="flex flex-col md:flex-row items-start md:items-center md:justify-start">
            <!-- Dropdown and Delete Button -->
        @if($schoolToShow)
                    <p class="w-96 text-black mt-2 text-sm mb-1">School: <span class="text-red-500 ml-2 font-bold uppercase">{{ $schoolToShow->abbreviation }}</span></p>
                @endif
        
        </div>
        <hr class="border-gray-200 my-4">
        @if($schoolToShow)
        <div class="flex justify-between">
            <p class="text-black text-sm mb-4">Selected School Year: <text class="uppercase text-red-500">{{ $schoolToShow->abbreviation }}</text></p>
        
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
                <label for="school_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Department:</label>
                <select wire:model="selectedDepartment" id="school_id" name="school_id" wire:change="showDepartmentSchedule"
                        class="cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror md:w-auto"
                        required>
                    <option value="">Select department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->department_abbreviation }}</option>
                    @endforeach
                </select>
                
                        <div wire:loading wire:target="selectedDepartment" class="fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50 z-50">
                            <div class="h-full flex mx-auto justify-center items-center space-x-2  bg-opacity-50 p-6 rounded shadow-lg">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 border-4 border-t-transparent border-blue-500 rounded-full animate-spin"></div>
                                    <span class="text-center mt-3 text-white">Fetching data...</span>
                                </div>
                            </div>
                        </div>

                @if($showSelectedDepartment)
                    <div class="flex justify-between">
                        <p class="text-black mt-2 text-sm mb-4">Selected Department: <text class="uppercase text-red-500">{{ $showSelectedDepartment->department_abbreviation }}</text></p>
                        <div x-data="{ open: false }">
                            <button @click="open = true" class="bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Schedule in {{ $showSelectedDepartment->department_abbreviation }}
                            </button>
                            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                <div @click.away="open = true" class="w-[30%] max-h-[90%]  bg-white p-6 rounded-lg shadow-lg  mx-auto overflow-y-auto">
                                    <div class="flex justify-between items-center pb-3">
                                        <p class="text-xl font-bold">Add Schedule in {{ $showSelectedDepartment->department_abbreviation }} department</p>
                                        <button @click="open = false" class=" text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                                    </div>
                                    <div class="mb-4">
                                        @if (Auth::user()->hasRole('admin')) 
                                            <form action="{{ route('admin.workinghour.store') }}" method="POST" class="">
                                        @else
                                            <form action="{{ route('admin_staff.workinghour.store') }}" method="POST" class="">
                                        @endif
                                        <x-caps-lock-detector />
                                            @csrf

                                                <div class="mb-2">
                                                    <label for="day_of_week" class="block text-gray-700 text-md font-bold mb-2">Select Day: </label>
                                                    <select id="dept_identifier" name="day_of_week" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('day_of_week') is-invalid @enderror" required>
                                                            <option value="0">Sunday</option>
                                                            <option value="1">Monday</option>
                                                            <option value="2">Tuesday</option>
                                                            <option value="3">Wednesday</option>
                                                            <option value="4">Thursday</option>
                                                            <option value="5">Friday</option>
                                                            <option value="6">Saturday</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label class="block text-gray-700 text-md font-bold mb-2">Set Working Hours</label>
                                                    <div class="flex mb-2">
                                                        <div x-data="{ isDisabled: false }" class="w-1/2 pr-2">
                                                            <!-- Checkbox to toggle input type -->
                                                            <input type="checkbox" id="toggleCheckbox" x-model="isDisabled" class="mr-2">
                                                            <label for="toggleCheckbox" class="text-gray-700 text-sm font-bold">No Time In AM</label>

                                                            <label for="morning_start_time" class="block text-gray-700 text-sm font-bold mb-2">Morning Start Time</label>
                                                            
                                                            <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                            <input 
                                                                :type="isDisabled ? 'text' : 'time'" 
                                                                name="morning_start_time" 
                                                                id="morning_start_time" 
                                                                :value="isDisabled ? '' : ''" 
                                                                :disabled="isDisabled" 
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('morning_start_time') is-invalid @enderror"
                                                            >
                                                            
                                                            <x-input-error :messages="$errors->get('morning_start_time')" class="mt-2" />
                                                        </div>

                                                        
                                                        <div x-data="{ isDisabled: false }" class="w-1/2 pl-2">
                                                            <!-- Checkbox to toggle input type -->
                                                            <input type="checkbox" id="toggleCheckboxMorningEnd" x-model="isDisabled" class="mr-2 text-left">
                                                            <label for="toggleCheckboxMorningEnd" class="text-gray-700 text-sm font-bold mb-2">No Time Out AM</label>

                                                            <label for="morning_end_time" class="block text-gray-700 text-sm font-bold mb-1">Morning End Time</label>
                                                            
                                                            <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                            <input 
                                                                :type="isDisabled ? 'text' : 'time'" 
                                                                name="morning_end_time" 
                                                                id="morning_end_time" 
                                                                :value="isDisabled ? '' : ''" 
                                                                :disabled="isDisabled" 
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('morning_end_time') is-invalid @enderror"
                                                            >
                                                            
                                                            <x-input-error :messages="$errors->get('morning_end_time')" class="mt-2" />
                                                        </div>

                                                    </div>
                                                    
                                                    <div class="flex">
                                                        <div x-data="{ isDisabled: false }" class="w-1/2 pr-2">
                                                            <!-- Checkbox to toggle input type -->
                                                            <input type="checkbox" id="toggleCheckboxPMStart" x-model="isDisabled" class="mr-2">
                                                            <label for="toggleCheckboxPMStart" class="text-gray-700 text-sm font-bold">No Time In PM</label>

                                                            <label for="afternoon_start_time" class="block text-gray-700 text-sm font-bold mb-2">Afternoon Start Time</label>
                                                            
                                                            <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                            <input 
                                                                :type="isDisabled ? 'text' : 'time'" 
                                                                name="afternoon_start_time" 
                                                                id="afternoon_start_time" 
                                                                :value="isDisabled ? '' : ''" 
                                                                :disabled="isDisabled" 
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('afternoon_start_time') is-invalid @enderror"
                                                            >
                                                            
                                                            <x-input-error :messages="$errors->get('afternoon_start_time')" class="mt-2" />
                                                        </div>

                                                            
                                                        <div x-data="{ isDisabled: false }" class="w-1/2 pl-2">
                                                            <!-- Checkbox to toggle input type -->
                                                            <input type="checkbox" id="toggleCheckboxPMEnd" x-model="isDisabled" class="mr-2 text-left">
                                                            <label for="toggleCheckboxPMEnd" class="text-gray-700 text-sm font-bold mb-2">No Time Out PM</label>

                                                            <label for="afternoon_end_time" class="block text-gray-700 text-sm font-bold mb-1">Afternoon End Time</label>
                                                            
                                                            <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                            <input 
                                                                :type="isDisabled ? 'text' : 'time'" 
                                                                name="afternoon_end_time" 
                                                                id="afternoon_end_time" 
                                                                :value="isDisabled ? '' : ''" 
                                                                :disabled="isDisabled" 
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('afternoon_end_time') is-invalid @enderror"
                                                            >
                                                            
                                                            <x-input-error :messages="$errors->get('afternoon_end_time')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-2 hidden">
                                                    <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School where department belong: </label>
                                                    <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                            <option value="{{ $schoolToShow->id }}">{{ $schoolToShow->id }} - {{ $schoolToShow->school_name }}</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="department_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Department:</label>
                                                    <select id="department_id" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required>
                                                        <option value="{{ $showSelectedDepartment->id }}">{{ $showSelectedDepartment->department_abbreviation }}</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
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
                    @if($workingHour->isEmpty())
                        <p class="text-black mt-8 text-center">No work schedule found in <text class="text-red-500">{{ $showSelectedDepartment->department_abbreviation }} department.</text></p>  
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-[20%] table-fixed min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                                <thead class="bg-gray-200 text-black">
                                    <tr>
                                        <th class="border border-gray-400 px-3 py-2">Schedule of Work</th>
                                        <th class="border border-gray-400 px-3 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    @foreach ($workingHour as $schedule)
                                        <tr class="hover:bg-gray-100">
                                            <td class="text-black border border-gray-400 text-left">&nbsp;&nbsp;{{ $daysOfWeek[$schedule->day_of_week] ?? 'Unknown' }} - 
                                                @if ($schedule->morning_start_time)
                                                    {{ date('h:i A', strtotime($schedule->morning_start_time)) }}
                                                @else
                                                    No AM Time IN 
                                                @endif
                                                &nbsp; - &nbsp;
                                                @if ($schedule->morning_end_time)
                                                    {{ date('h:i A', strtotime($schedule->morning_end_time)) }}
                                                @else
                                                    No AM Time Out 
                                                @endif
                                                &nbsp; / &nbsp;
                                                @if ($schedule->afternoon_start_time)
                                                    {{ date('h:i A', strtotime($schedule->afternoon_start_time)) }}
                                                @else
                                                    No PM Time IN 
                                                @endif
                                                &nbsp; - &nbsp;
                                                @if ($schedule->afternoon_end_time)
                                                    {{ date('h:i A', strtotime($schedule->afternoon_end_time)) }}
                                                @else
                                                    No PM Time OUT 
                                                @endif
                                            </td>                                 
                                            <td class="text-black border border-gray-400 px-1 py-3">
                                                <div class="flex justify-center items-center space-x-2">
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
                                                            <div @click.away="open = true" class="w-[30%] max-h-[90%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                                <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                    <p class="text-xl font-bold">Edit Schedule in {{ $showSelectedDepartment->department_abbreviation }}</p>
                                                                    <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                </div>
                                                                <div class="mb-4">
                                                                    @if(Auth::user()->hasRole('admin'))
                                                                        <form action="{{ route('admin.workinghour.update', $schedule->id ) }}" method="POST" class="">
                                                                    @else
                                                                        <form action="{{ route('admin_staff.workinghour.update', $schedule->id ) }}" method="POST" class="">
                                                                    @endif
                                                                    <x-caps-lock-detector />
                                                                        @csrf
                                                                        @method('PUT')
                                                                            <div class="mb-2">
                                                                                <label for="day_of_week" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Day: </label>
                                                                                <select id="dept_identifier" name="day_of_week" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('day_of_week') is-invalid @enderror" required>
                                                                                    <option value="0" @if(($schedule->day_of_week ?? 'Unknown') == 0) selected @endif>Sunday</option>
                                                                                    <option value="1" @if(($schedule->day_of_week ?? 'Unknown') == 1) selected @endif>Monday</option>
                                                                                    <option value="2" @if(($schedule->day_of_week ?? 'Unknown') == 2) selected @endif>Tuesday</option>
                                                                                    <option value="3" @if(($schedule->day_of_week ?? 'Unknown') == 3) selected @endif>Wednesday</option>
                                                                                    <option value="4" @if(($schedule->day_of_week ?? 'Unknown') == 4) selected @endif>Thursday</option>
                                                                                    <option value="5" @if(($schedule->day_of_week ?? 'Unknown') == 5) selected @endif>Friday</option>
                                                                                    <option value="6" @if(($schedule->day_of_week ?? 'Unknown') == 6) selected @endif>Saturday</option>
                                                                                </select>
                                                                                <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="block text-gray-700 text-md font-bold mb-2">Set Working Hours</label>
                                                                                <div class="flex mb-2">
                                                                                    <div x-data="{ isDisabled: false }" class="w-1/2 pr-2">
                                                                                        <!-- Checkbox to toggle input type -->
                                                                                        <input type="checkbox" id="toggleCheckbox" x-model="isDisabled" class="mr-2">
                                                                                        <label for="toggleCheckbox" class="text-gray-700 text-sm font-bold">No Time In AM</label>

                                                                                        <label for="morning_start_time" class="block text-gray-700 text-sm font-bold mb-2">Morning Start Time</label>
                                                                                        
                                                                                        <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                                                        <input 
                                                                                            :type="isDisabled ? 'text' : 'time'" 
                                                                                            name="morning_start_time" 
                                                                                            id="morning_start_time" 
                                                                                            :value="isDisabled ? '' : '{{ $schedule->morning_start_time }}'" 
                                                                                            :disabled="isDisabled" 
                                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('morning_start_time') is-invalid @enderror"
                                                                                        >
                                                                                        
                                                                                        <x-input-error :messages="$errors->get('morning_start_time')" class="mt-2" />
                                                                                    </div>

                                                                                    <!-- <input type="hidden" name="morning_start_time_null" id="morning_start_time_null" value="{{ $schedule->morning_start_time }}"> -->
                                                                                    <div x-data="{ isDisabled: false }" class="w-1/2 pl-2">
                                                                                        <!-- Checkbox to toggle input type -->
                                                                                        <input type="checkbox" id="toggleCheckboxMorningEnd" x-model="isDisabled" class="mr-2 text-left">
                                                                                        <label for="toggleCheckboxMorningEnd" class="text-gray-700 text-sm font-bold mb-2">No Time Out AM</label>

                                                                                        <label for="morning_end_time" class="block text-gray-700 text-sm font-bold mb-1">Morning End Time</label>
                                                                                        
                                                                                        <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                                                        <input 
                                                                                            :type="isDisabled ? 'text' : 'time'" 
                                                                                            name="morning_end_time" 
                                                                                            id="morning_end_time" 
                                                                                            :value="isDisabled ? '' : '{{ $schedule->morning_end_time }}'" 
                                                                                            :disabled="isDisabled" 
                                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('morning_end_time') is-invalid @enderror"
                                                                                        >
                                                                                        
                                                                                        <x-input-error :messages="$errors->get('morning_end_time')" class="mt-2" />
                                                                                    </div>

                                                                                </div>
                                                                                
                                                                                <div class="flex">
                                                                                    <div x-data="{ isDisabled: false }" class="w-1/2 pr-2">
                                                                                        <!-- Checkbox to toggle input type -->
                                                                                        <input type="checkbox" id="toggleCheckboxPMStart" x-model="isDisabled" class="mr-2">
                                                                                        <label for="toggleCheckboxPMStart" class="text-gray-700 text-sm font-bold">No Time In PM</label>

                                                                                        <label for="afternoon_start_time" class="block text-gray-700 text-sm font-bold mb-2">Afternoon Start Time</label>
                                                                                        
                                                                                        <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                                                        <input 
                                                                                            :type="isDisabled ? 'text' : 'time'" 
                                                                                            name="afternoon_start_time" 
                                                                                            id="afternoon_start_time" 
                                                                                            :value="isDisabled ? '' : '{{ $schedule->afternoon_start_time }}'" 
                                                                                            :disabled="isDisabled" 
                                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('afternoon_start_time') is-invalid @enderror"
                                                                                        >
                                                                                        
                                                                                        <x-input-error :messages="$errors->get('afternoon_start_time')" class="mt-2" />
                                                                                    </div>

                                                                                    <!-- <input type="hidden" name="morning_start_time_null" id="morning_start_time_null" value="{{ $schedule->morning_start_time }}"> -->
                                                                                    <div x-data="{ isDisabled: false }" class="w-1/2 pl-2">
                                                                                        <!-- Checkbox to toggle input type -->
                                                                                        <input type="checkbox" id="toggleCheckboxPMEnd" x-model="isDisabled" class="mr-2 text-left">
                                                                                        <label for="toggleCheckboxPMEnd" class="text-gray-700 text-sm font-bold mb-2">No Time Out PM</label>

                                                                                        <label for="afternoon_end_time" class="block text-gray-700 text-sm font-bold mb-1">Afternoon End Time</label>
                                                                                        
                                                                                        <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                                                        <input 
                                                                                            :type="isDisabled ? 'text' : 'time'" 
                                                                                            name="afternoon_end_time" 
                                                                                            id="afternoon_end_time" 
                                                                                            :value="isDisabled ? '' : '{{ $schedule->afternoon_end_time }}'" 
                                                                                            :disabled="isDisabled" 
                                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('afternoon_end_time') is-invalid @enderror"
                                                                                        >
                                                                                        
                                                                                        <x-input-error :messages="$errors->get('afternoon_end_time')" class="mt-2" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mb-2 hidden">
                                                                                <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School where department belong: </label>
                                                                                <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                                                        <option value="{{ $schoolToShow->id }}">{{ $schoolToShow->id }} - {{ $schoolToShow->school_name }}</option>
                                                                                </select>
                                                                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label for="department_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Department:</label>
                                                                                <select id="department_id" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required>
                                                                                    <option value="{{ $showSelectedDepartment->id }}">{{ $showSelectedDepartment->department_abbreviation }}</option>
                                                                                </select>
                                                                                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                                                            </div>
                                                                        <div class="flex mb-4 mt-10 justify-center">
                                                                            <button type="submit" id="time" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                                Save changes
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (Auth::user()->hasRole('admin'))
                                                        @if (Auth::user()->hasRole('admin')) 
                                                            <form id="deleteSelected" action="{{ route('admin.workinghour.delete', [':id', ':department_id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $schedule->id }}');">
                                                        @else
                                                            <form id="deleteSelected" action="{{ route('staff.workinghour.delete', [':id', ':department_id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $schedule->id }}');">
                                                        @endif
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
                        </div>
                    @endif
                @else
                <p class="text-black mt-16  text-center uppercase">No selected department</p>
                @endif
            @else
                <p class="text-black mt-10  text-center">Select table to show data</p>
            @endif
        @endif






        
    @elseif(Auth::check() && Auth::user()->hasRole('admin_staff'))

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
                <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Admin / Manage Work Schedule</div>
            @else
                <div class="font-bold text-md tracking-tight text-md text-black  mt-2 uppercase">Staff / Manage Work Schedule</div>
            @endif
        </div>
        <div class="flex flex-col md:flex-row items-start md:items-center md:justify-start">
            @if($schoolToShow)
                    <p class="w-96 text-black mt-2 text-sm mb-1">School: <span class="text-red-500 ml-2 font-bold uppercase">{{ $schoolToShow->abbreviation }}</span></p>
            @endif
        </div>
        <hr class="border-gray-200 my-4">

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
                <label for="school_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Department:</label>
                <select wire:model="selectedDepartment" id="school_id" name="school_id" wire:change="showDepartmentSchedule"
                        class="cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror md:w-auto"
                        required>
                    <option value="">Select department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->department_abbreviation }}</option>
                    @endforeach
                </select>

                @if($showSelectedDepartment)
                    <div class="flex justify-between">
                        <p class="text-black mt-2 text-sm mb-4">Selected Department: <text class="uppercase text-red-500">{{ $showSelectedDepartment->department_abbreviation }}</text></p>
                        <div x-data="{ open: false }">
                            <button @click="open = true" class="bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Schedule in {{ $showSelectedDepartment->department_abbreviation }}
                            </button>
                            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                <div @click.away="open = true" class="w-[30%] max-h-[90%]  bg-white p-6 rounded-lg shadow-lg  mx-auto overflow-y-auto">
                                    <div class="flex justify-between items-center pb-3">
                                        <p class="text-xl font-bold">Add Schedule in {{ $showSelectedDepartment->department_abbreviation }} department</p>
                                        <button @click="open = false" class=" text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                                    </div>
                                    <div class="mb-4">
                                        @if (Auth::user()->hasRole('admin')) 
                                            <form action="{{ route('admin.workinghour.store') }}" method="POST" class="">
                                        @else
                                            <form action="{{ route('admin_staff.workinghour.store') }}" method="POST" class="">
                                        @endif
                                        <x-caps-lock-detector />
                                            @csrf

                                                <div class="mb-2">
                                                    <label for="day_of_week" class="block text-gray-700 text-md font-bold mb-2">Select Day: </label>
                                                    <select id="dept_identifier" name="day_of_week" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('day_of_week') is-invalid @enderror" required>
                                                            <option value="0">Sunday</option>
                                                            <option value="1">Monday</option>
                                                            <option value="2">Tuesday</option>
                                                            <option value="3">Wednesday</option>
                                                            <option value="4">Thursday</option>
                                                            <option value="5">Friday</option>
                                                            <option value="6">Saturday</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label class="block text-gray-700 text-md font-bold mb-2">Set Working Hours</label>
                                                    <div class="flex mb-2">
                                                        <div x-data="{ isDisabled: false }" class="w-1/2 pr-2">
                                                            <!-- Checkbox to toggle input type -->
                                                            <input type="checkbox" id="toggleCheckbox" x-model="isDisabled" class="mr-2">
                                                            <label for="toggleCheckbox" class="text-gray-700 text-sm font-bold">No Time In AM</label>

                                                            <label for="morning_start_time" class="block text-gray-700 text-sm font-bold mb-2">Morning Start Time</label>
                                                            
                                                            <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                            <input 
                                                                :type="isDisabled ? 'text' : 'time'" 
                                                                name="morning_start_time" 
                                                                id="morning_start_time" 
                                                                :value="isDisabled ? '' : ''" 
                                                                :disabled="isDisabled" 
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('morning_start_time') is-invalid @enderror"
                                                            >
                                                            
                                                            <x-input-error :messages="$errors->get('morning_start_time')" class="mt-2" />
                                                        </div>

                                                        
                                                        <div x-data="{ isDisabled: false }" class="w-1/2 pl-2">
                                                            <!-- Checkbox to toggle input type -->
                                                            <input type="checkbox" id="toggleCheckboxMorningEnd" x-model="isDisabled" class="mr-2 text-left">
                                                            <label for="toggleCheckboxMorningEnd" class="text-gray-700 text-sm font-bold mb-2">No Time Out AM</label>

                                                            <label for="morning_end_time" class="block text-gray-700 text-sm font-bold mb-1">Morning End Time</label>
                                                            
                                                            <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                            <input 
                                                                :type="isDisabled ? 'text' : 'time'" 
                                                                name="morning_end_time" 
                                                                id="morning_end_time" 
                                                                :value="isDisabled ? '' : ''" 
                                                                :disabled="isDisabled" 
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('morning_end_time') is-invalid @enderror"
                                                            >
                                                            
                                                            <x-input-error :messages="$errors->get('morning_end_time')" class="mt-2" />
                                                        </div>

                                                    </div>
                                                    
                                                    <div class="flex">
                                                        <div x-data="{ isDisabled: false }" class="w-1/2 pr-2">
                                                            <!-- Checkbox to toggle input type -->
                                                            <input type="checkbox" id="toggleCheckboxPMStart" x-model="isDisabled" class="mr-2">
                                                            <label for="toggleCheckboxPMStart" class="text-gray-700 text-sm font-bold">No Time In PM</label>

                                                            <label for="afternoon_start_time" class="block text-gray-700 text-sm font-bold mb-2">Afternoon Start Time</label>
                                                            
                                                            <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                            <input 
                                                                :type="isDisabled ? 'text' : 'time'" 
                                                                name="afternoon_start_time" 
                                                                id="afternoon_start_time" 
                                                                :value="isDisabled ? '' : ''" 
                                                                :disabled="isDisabled" 
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('afternoon_start_time') is-invalid @enderror"
                                                            >
                                                            
                                                            <x-input-error :messages="$errors->get('afternoon_start_time')" class="mt-2" />
                                                        </div>

                                                            
                                                        <div x-data="{ isDisabled: false }" class="w-1/2 pl-2">
                                                            <!-- Checkbox to toggle input type -->
                                                            <input type="checkbox" id="toggleCheckboxPMEnd" x-model="isDisabled" class="mr-2 text-left">
                                                            <label for="toggleCheckboxPMEnd" class="text-gray-700 text-sm font-bold mb-2">No Time Out PM</label>

                                                            <label for="afternoon_end_time" class="block text-gray-700 text-sm font-bold mb-1">Afternoon End Time</label>
                                                            
                                                            <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                            <input 
                                                                :type="isDisabled ? 'text' : 'time'" 
                                                                name="afternoon_end_time" 
                                                                id="afternoon_end_time" 
                                                                :value="isDisabled ? '' : ''" 
                                                                :disabled="isDisabled" 
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('afternoon_end_time') is-invalid @enderror"
                                                            >
                                                            
                                                            <x-input-error :messages="$errors->get('afternoon_end_time')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-2 hidden">
                                                    <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School where department belong: </label>
                                                    <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                            <option value="{{ $schoolToShow->id }}">{{ $schoolToShow->id }} - {{ $schoolToShow->school_name }}</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                </div>
                                                <div class="mb-2">
                                                    <label for="department_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Department:</label>
                                                    <select id="department_id" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required>
                                                        <option value="{{ $showSelectedDepartment->id }}">{{ $showSelectedDepartment->department_abbreviation }}</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
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
                    @if($workingHour->isEmpty())
                        <p class="text-black mt-8 text-center">No work schedule found in <text class="text-red-500">{{ $showSelectedDepartment->department_abbreviation }} department.</text></p>  
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-[20%] table-fixed min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                                <thead class="bg-gray-200 text-black">
                                    <tr>
                                        <th class="border border-gray-400 px-3 py-2">Schedule of Work</th>
                                        <th class="border border-gray-400 px-3 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    @foreach ($workingHour as $schedule)
                                        <tr class="hover:bg-gray-100">
                                            <td class="text-black border border-gray-400 text-left">&nbsp;&nbsp;{{ $daysOfWeek[$schedule->day_of_week] ?? 'Unknown' }} - 
                                                @if ($schedule->morning_start_time)
                                                    {{ date('h:i A', strtotime($schedule->morning_start_time)) }}
                                                @else
                                                    No AM Time IN 
                                                @endif
                                                &nbsp; - &nbsp;
                                                @if ($schedule->morning_end_time)
                                                    {{ date('h:i A', strtotime($schedule->morning_end_time)) }}
                                                @else
                                                    No AM Time Out 
                                                @endif
                                                &nbsp; / &nbsp;
                                                @if ($schedule->afternoon_start_time)
                                                    {{ date('h:i A', strtotime($schedule->afternoon_start_time)) }}
                                                @else
                                                    No PM Time IN 
                                                @endif
                                                &nbsp; - &nbsp;
                                                @if ($schedule->afternoon_end_time)
                                                    {{ date('h:i A', strtotime($schedule->afternoon_end_time)) }}
                                                @else
                                                    No PM Time OUT 
                                                @endif
                                            </td>                                 
                                            <td class="text-black border border-gray-400 px-1 py-3">
                                                <div class="flex justify-center items-center space-x-2">
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
                                                            <div @click.away="open = true" class="w-[30%] max-h-[90%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                                <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                    <p class="text-xl font-bold">Edit Schedule in {{ $showSelectedDepartment->department_abbreviation }}</p>
                                                                    <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                </div>
                                                                <div class="mb-4">
                                                                    @if(Auth::user()->hasRole('admin'))
                                                                        <form action="{{ route('admin.workinghour.update', $schedule->id ) }}" method="POST" class="">
                                                                    @else
                                                                        <form action="{{ route('admin_staff.workinghour.update', $schedule->id ) }}" method="POST" class="">
                                                                    @endif
                                                                    <x-caps-lock-detector />
                                                                        @csrf
                                                                        @method('PUT')
                                                                            <div class="mb-2">
                                                                                <label for="day_of_week" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Day: </label>
                                                                                <select id="dept_identifier" name="day_of_week" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('day_of_week') is-invalid @enderror" required>
                                                                                    <option value="0" @if(($schedule->day_of_week ?? 'Unknown') == 0) selected @endif>Sunday</option>
                                                                                    <option value="1" @if(($schedule->day_of_week ?? 'Unknown') == 1) selected @endif>Monday</option>
                                                                                    <option value="2" @if(($schedule->day_of_week ?? 'Unknown') == 2) selected @endif>Tuesday</option>
                                                                                    <option value="3" @if(($schedule->day_of_week ?? 'Unknown') == 3) selected @endif>Wednesday</option>
                                                                                    <option value="4" @if(($schedule->day_of_week ?? 'Unknown') == 4) selected @endif>Thursday</option>
                                                                                    <option value="5" @if(($schedule->day_of_week ?? 'Unknown') == 5) selected @endif>Friday</option>
                                                                                    <option value="6" @if(($schedule->day_of_week ?? 'Unknown') == 6) selected @endif>Saturday</option>
                                                                                </select>
                                                                                <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="block text-gray-700 text-md font-bold mb-2">Set Working Hours</label>
                                                                                <div class="flex mb-2">
                                                                                    <div x-data="{ isDisabled: false }" class="w-1/2 pr-2">
                                                                                        <!-- Checkbox to toggle input type -->
                                                                                        <input type="checkbox" id="toggleCheckbox" x-model="isDisabled" class="mr-2">
                                                                                        <label for="toggleCheckbox" class="text-gray-700 text-sm font-bold">No Time In AM</label>

                                                                                        <label for="morning_start_time" class="block text-gray-700 text-sm font-bold mb-2">Morning Start Time</label>
                                                                                        
                                                                                        <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                                                        <input 
                                                                                            :type="isDisabled ? 'text' : 'time'" 
                                                                                            name="morning_start_time" 
                                                                                            id="morning_start_time" 
                                                                                            :value="isDisabled ? '' : '{{ $schedule->morning_start_time }}'" 
                                                                                            :disabled="isDisabled" 
                                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('morning_start_time') is-invalid @enderror"
                                                                                        >
                                                                                        
                                                                                        <x-input-error :messages="$errors->get('morning_start_time')" class="mt-2" />
                                                                                    </div>

                                                                                    <!-- <input type="hidden" name="morning_start_time_null" id="morning_start_time_null" value="{{ $schedule->morning_start_time }}"> -->
                                                                                    <div x-data="{ isDisabled: false }" class="w-1/2 pl-2">
                                                                                        <!-- Checkbox to toggle input type -->
                                                                                        <input type="checkbox" id="toggleCheckboxMorningEnd" x-model="isDisabled" class="mr-2 text-left">
                                                                                        <label for="toggleCheckboxMorningEnd" class="text-gray-700 text-sm font-bold mb-2">No Time Out AM</label>

                                                                                        <label for="morning_end_time" class="block text-gray-700 text-sm font-bold mb-1">Morning End Time</label>
                                                                                        
                                                                                        <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                                                        <input 
                                                                                            :type="isDisabled ? 'text' : 'time'" 
                                                                                            name="morning_end_time" 
                                                                                            id="morning_end_time" 
                                                                                            :value="isDisabled ? '' : '{{ $schedule->morning_end_time }}'" 
                                                                                            :disabled="isDisabled" 
                                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('morning_end_time') is-invalid @enderror"
                                                                                        >
                                                                                        
                                                                                        <x-input-error :messages="$errors->get('morning_end_time')" class="mt-2" />
                                                                                    </div>

                                                                                </div>
                                                                                
                                                                                <div class="flex">
                                                                                    <div x-data="{ isDisabled: false }" class="w-1/2 pr-2">
                                                                                        <!-- Checkbox to toggle input type -->
                                                                                        <input type="checkbox" id="toggleCheckboxPMStart" x-model="isDisabled" class="mr-2">
                                                                                        <label for="toggleCheckboxPMStart" class="text-gray-700 text-sm font-bold">No Time In PM</label>

                                                                                        <label for="afternoon_start_time" class="block text-gray-700 text-sm font-bold mb-2">Afternoon Start Time</label>
                                                                                        
                                                                                        <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                                                        <input 
                                                                                            :type="isDisabled ? 'text' : 'time'" 
                                                                                            name="afternoon_start_time" 
                                                                                            id="afternoon_start_time" 
                                                                                            :value="isDisabled ? '' : '{{ $schedule->afternoon_start_time }}'" 
                                                                                            :disabled="isDisabled" 
                                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('afternoon_start_time') is-invalid @enderror"
                                                                                        >
                                                                                        
                                                                                        <x-input-error :messages="$errors->get('afternoon_start_time')" class="mt-2" />
                                                                                    </div>

                                                                                    <!-- <input type="hidden" name="morning_start_time_null" id="morning_start_time_null" value="{{ $schedule->morning_start_time }}"> -->
                                                                                    <div x-data="{ isDisabled: false }" class="w-1/2 pl-2">
                                                                                        <!-- Checkbox to toggle input type -->
                                                                                        <input type="checkbox" id="toggleCheckboxPMEnd" x-model="isDisabled" class="mr-2 text-left">
                                                                                        <label for="toggleCheckboxPMEnd" class="text-gray-700 text-sm font-bold mb-2">No Time Out PM</label>

                                                                                        <label for="afternoon_end_time" class="block text-gray-700 text-sm font-bold mb-1">Afternoon End Time</label>
                                                                                        
                                                                                        <!-- Input field with dynamic type, disabled state, and value based on checkbox state -->
                                                                                        <input 
                                                                                            :type="isDisabled ? 'text' : 'time'" 
                                                                                            name="afternoon_end_time" 
                                                                                            id="afternoon_end_time" 
                                                                                            :value="isDisabled ? '' : '{{ $schedule->afternoon_end_time }}'" 
                                                                                            :disabled="isDisabled" 
                                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('afternoon_end_time') is-invalid @enderror"
                                                                                        >
                                                                                        
                                                                                        <x-input-error :messages="$errors->get('afternoon_end_time')" class="mt-2" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mb-2 hidden">
                                                                                <label for="school_id" class="block text-gray-700 text-md font-bold mb-2">School where department belong: </label>
                                                                                <select id="school_id" name="school_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror" required>
                                                                                        <option value="{{ $schoolToShow->id }}">{{ $schoolToShow->id }} - {{ $schoolToShow->school_name }}</option>
                                                                                </select>
                                                                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label for="department_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Department:</label>
                                                                                <select id="department_id" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror" required>
                                                                                    <option value="{{ $showSelectedDepartment->id }}">{{ $showSelectedDepartment->department_abbreviation }}</option>
                                                                                </select>
                                                                                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                                                            </div>
                                                                        <div class="flex mb-4 mt-10 justify-center">
                                                                            <button type="submit" id="time" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                                Save changes
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (Auth::user()->hasRole('admin'))
                                                        @if (Auth::user()->hasRole('admin')) 
                                                            <form id="deleteSelected" action="{{ route('admin.workinghour.delete', [':id', ':department_id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $schedule->id }}');">
                                                        @else
                                                            <form id="deleteSelected" action="{{ route('staff.workinghour.delete', [':id', ':department_id']) }}" method="POST" onsubmit="return ConfirmDeleteSelected(event, '{{ $schedule->id }}');">
                                                        @endif
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
                        </div>
                    @endif
                @else
                <p class="text-black mt-16  text-center uppercase">No selected department</p>
                @endif
            @else
                <p class="text-black mt-10  text-center">Select table to show data</p>
            @endif
        @endif
    @else

    @endif
</div>

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

        

        function ConfirmDeleteSelected(event, rowId) {
            event.preventDefault(); // Prevent form submission initially

            Swal.fire({
                title: `Are you sure you want to delete the schedule # ${rowId} ?`,
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
                    const actionUrl = form.action.replace(':id', rowId);
                    form.action = actionUrl;
                    form.submit();
                }
            });

            return false; 
        }




    </script>
