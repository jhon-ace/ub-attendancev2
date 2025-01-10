
@if(Auth::guard('web')->check())
    @if (Auth::user()->hasRole('admin'))
        <div x-cloak x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                            window.addEventListener('resize', () => {
                                isFullScreen = (window.innerHeight === screen.height);
                            });
                        " x-show="!isFullScreen" id="sidebarContainer"  class="fixed flex flex-col left-0 w-14 hover:w-48 md:w-48 bg-gradient-to-r from-red-500 to-orange-500 h-full text-black transition-all duration-300 border-r-2 border-gray-300 dark:border-gray-600 sidebar">
            <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow mr-0.5">
                <ul class="flex flex-col py-2 space-y-1 text-gray-800" >
                    <a href="#" class="flex justify-center items-center">
                        <img class="w-32 h-auto object-contain" src="{{ asset('assets/img/logo.png') }}" alt="SCMS Logo">
                    </a>

                    <label class="relative flex flex-row justify-center items-center h-2 focus:outline-none   text-white-600 hover:text-white-800 border-l-4 border-transparent  pr-3 ">
                        <span class=" text-sm tracking-wide truncate text-gray-200">{{ Auth::user()->name }}</span>
                    </label>
                    <label class="relative flex flex-row justify-center h-5 focus:outline-none   text-white-600 hover:text-white-800 border-l-4 border-transparent   ">
                        <span class=" text-xs tracking-wide truncate text-gray-200">{{ Auth::user()->email }}</span>
                    </label>
                    <div class="border-t"></div>
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                        {{ request()->routeIs('admin.dashboard') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center ml-4">
                                <i class="fa-solid fa-gauge-high fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Dashboard</span>
                        </a>
                    </li>
                    <li class="hidden">
                        <a href="{{ route('admin.school.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                        {{ request()->routeIs('admin.school.index') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center ml-4">
                                <i class="fa-solid fa-school fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate text-gray-200">School</span>
                        </a>
                    </li>
                    <li x-data="{ open: {{ request()->routeIs('admin.department.index')  || request()->routeIs('admin.workinghour.index') ? 'true'  : 'false' }} }">
                        <a @click="open = !open" class="hidden cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-3">
                                <i class="fa-solid fa-users fa-sm text-gray-200"></i>
                            </span>
                            <span class="text-sm tracking-wide truncate text-gray-200 ml-2">Department</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open"  x-cloak class="ml-3 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.department.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                                {{ request()->routeIs('admin.department.index') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <span class="inline-flex justify-center items-center ml-4">
                                        <i class="fa-solid fa-school fa-sm text-gray-200 "></i>
                                    </span>
                                    <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Department</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.workinghour.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                                {{ request()->routeIs('admin.workinghour.index') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <span class="inline-flex justify-center items-center ml-4">
                                        <i class="fa-solid fa-school fa-sm text-gray-200 "></i>
                                    </span>
                                    <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Working Hour</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('admin.course.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                        {{ request()->routeIs('admin.course.index') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center ml-4">
                                <i class="fa-solid fa-school fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Courses</span>
                        </a>
                    </li>
                    <li x-data="{ open: {{ request()->routeIs('admin.staff.index')  || request()->routeIs('admin.employee.index') || request()->routeIs('admin.student.index') ? 'true'  : 'false' }} }">
                        <a @click="open = !open" class="cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-3">
                                <i class="fa-solid fa-users fa-sm text-gray-200"></i>
                            </span>
                            <span class="text-sm tracking-wide truncate text-gray-200 ml-2">Users</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open"  x-cloak class="ml-3 mt-1 space-y-1">
                            <!-- <li>
                                <a href="{{ route('admin.staff.index') }}" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white {{ request()->routeIs('admin.staff.index') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Admin Staff
                                </a>
                            </li> -->
                            <li>
                                <a href="{{ route('admin.employee.index') }}" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white {{ request()->routeIs('admin.employee.index') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Employee
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.student.index') }}" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white {{ request()->routeIs('admin.student.index') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Student
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="hidden" x-data="{ open: {{ request()->routeIs('admin.attendance.employee_attendance') || request()->routeIs('admin.attendance.student_attendance') || request()->routeIs('admin.attendance.employee_attendance.search') || request()->routeIs('admin.attendance.employee_attendance.payroll') || request()->routeIs('admin.attendance.employee_attendance.payroll.all') ? 'true'  : 'false' }} }">
                        <a @click="open = !open" class="w-full cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-3">
                                <i class="fa-solid fa-users fa-sm text-gray-200"></i>
                            </span>
                            <span class="text-sm tracking-wide truncate text-gray-200 ml-2">Attendance</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open"  x-cloak class="ml-3 mt-1 space-y-1 w-full">
                            <li>
                                <a href="{{ route('admin.attendance.employee_attendance.search') }}" class="w-[500px] flex items-center h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white {{ request()->routeIs('admin.attendance.employee_attendance.search') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Search Attendance
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ route('admin.attendance.employee_attendance') }}" class=" w-full flex items-center  h-16 pl-4  text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin.attendance.employee_attendance') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Individual Attendance Report by Department
                                </a>
                            </li> -->
                            <li class="w-full">
                                <a href="{{ route('admin.attendance.employee_attendance') }}" class="flex items-center  h-20 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin.attendance.employee_attendance') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Individual Attendance Report by Department
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.attendance.employee_attendance.payroll') }}" class="flex items-center  h-20 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin.attendance.employee_attendance.payroll') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>All Employee's Attendance Report by Department
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.attendance.student_attendance') }}" class="flex items-center h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white {{ request()->routeIs('admin.attendance.student_attendance') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Student
                                </a>
                            </li>
                            <li>
                                <a  href="{{ route('admin.dashboard') }}" class="flex items-center h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white ">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Daily Monitor
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li x-data="{ open: {{ request()->routeIs('admin.attendance.gracePeriodSet') || request()->routeIs('admin.attendance.holiday') ? 'true'  : 'false' }} }">
                        <a @click="open = !open" class="w-full cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-3">
                                <i class="fa-solid fa-cogs text-gray-200"></i>
                            </span>
                            <span class="text-sm tracking-wide truncate text-gray-200 ml-2">Settings</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open"  x-cloak class="ml-3 mt-1 space-y-1 w-full">
                            <li>
                                <a href="{{ route('admin.attendance.gracePeriodSet') }}" class="w-[500px] flex items-center h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin.attendance.gracePeriodSet') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Set Grace Period
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.attendance.holiday') }}" class=" w-full flex items-center  h-16 pl-4  text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                            {{ request()->routeIs('admin.attendance.holiday') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Set Holiday Date
                                </a>
                            </li>
                            <li class="hidden">
                                <a href="" class=" w-full flex items-center  h-16 pl-4  text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                ">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Change Password
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                    <!-- <li x-data="{ open: false }">
                        <a @click="open = !open" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-4">
                                <i class="fa-solid fa-file-lines fa-sm text-gray-200"></i>
                            </span>
                            <span class=" text-sm tracking-wide truncate text-gray-200 ml-2">Reports</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open" @click.away="open = false" x-cloak class="ml-4 mt-1 space-y-1">
                            <li>
                                <a href="" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white">
                                <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Admin Staff
                                </a>
                            </li>
                            <li>
                                <a href="" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Employee
                                </a>
                            </li>
                            <li>
                                <a href="" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Student
                                </a>
                            </li>
                        </ul>
                    </li> -->

                    <li>
                        <form id="logout" method="POST" action="{{ route('logout') }}" onsubmit="return confirmLogout(event)">
                            @csrf

                            <button type="submit" class="relative flex flex-row items-center w-full h-11 focus:outline-none  hover:bg-[#172029] text-white] dark:hover:bg-slate-700 text-gray-200 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                                <span class="inline-flex justify-center items-center ml-5">
                                    <i class="fa-solid fa-right-from-bracket fa-sm text-gray-200"></i>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate text-gray-200">{{ __('Sign Out') }}</span>
                            </button>
                        </form>
                    </li>
                </ul>
                    <p class="mb-14 px-5 py-3 hidden md:block text-center text-xs text-white">Copyright @2025</p>
            </div>
        </div>










    @elseif (Auth::user()->hasRole('admin_staff'))

        <div x-cloak x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                            window.addEventListener('resize', () => {
                                isFullScreen = (window.innerHeight === screen.height);
                            });
                        " x-show="!isFullScreen" id="sidebarContainer"  class="fixed flex flex-col left-0 w-14 hover:w-48 md:w-48 bg-gradient-to-r from-red-500 to-orange-500 h-full text-black transition-all duration-300 border-r-2 border-gray-300 dark:border-gray-600 sidebar z-50">
            <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow mr-0.5">
                <ul class="flex flex-col py-2 space-y-1 text-gray-800" >
                    <a href="#" class="flex justify-center items-center">
                        <img class="w-32 h-auto object-contain" src="{{ asset('assets/img/logo.png') }}" alt="SCMS Logo">
                    </a>

                    <label class="relative flex flex-row justify-center items-center h-2 focus:outline-none   text-white-600 hover:text-white-800 border-l-4 border-transparent  pr-3 ">
                        <span class=" text-sm tracking-wide truncate text-gray-200">{{ Auth::user()->name }}</span>
                    </label>
                    <label class="relative flex flex-row justify-center h-5 focus:outline-none   text-white-600 hover:text-white-800 border-l-4 border-transparent   ">
                        <span class=" text-xs tracking-wide truncate text-gray-200">{{ Auth::user()->email }}</span>
                    </label>
                    <div class="border-t"></div>
                    <!-- <li>
                        <a href="{{ route('admin_staff.dashboard') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                        {{ request()->routeIs('admin_staff.dashboard') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center ml-4">
                                <i class="fa-solid fa-gauge-high fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-xs tracking-wide truncate text-gray-200">Current Day Monitoring</span>
                        </a>
                    </li> -->
                    <li class="w-full">
                        <a href="{{ route('admin_staff.dashboard') }}" class="flex items-center  h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                        {{ request()->routeIs('admin_staff.dashboard') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center">
                                <i class="fa-solid fa-list fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Current Day Monitoring</span>
                        </a>
                    </li>
                    <li x-data="{ open: {{ request()->routeIs('admin_staff.attendance.employee_attendance.payroll') || request()->routeIs('admin_staff.attendance.employee_attendance') || request()->routeIs('admin.attendance.student_attendance') || request()->routeIs('admin_staff.attendance.employee_attendance.search') || request()->routeIs('admin.attendance.employee_attendance.payroll') || request()->routeIs('admin.attendance.employee_attendance.payroll.all') ? 'true'  : 'false' }} }">
                        <a @click="open = !open" class="w-full cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6
                                    {{ request()->routeIs('') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center ml-3">
                                <i class="fa-solid fa-users fa-sm text-gray-200"></i>
                            </span>
                            <span class="text-sm tracking-wide truncate text-gray-200 ml-2">Attendances</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open"  x-cloak class="ml-3 mt-1 space-y-1 w-full">
                            <li class="w-full">
                                <a href="{{ route('admin_staff.attendance.employee_attendance.search') }}" class="flex items-center  h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin_staff.attendance.employee_attendance.search') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Search / Add Attendance
                                </a>
                            </li>
                            <li class="w-full">
                                <a href="{{ route('admin_staff.attendance.employee_attendance') }}" class="flex items-center  h-20 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin_staff.attendance.employee_attendance') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Employee Attendance by Department
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_staff.attendance.employee_attendance.payroll') }}" class="flex items-center  h-20 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin_staff.attendance.employee_attendance.payroll') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>All Employee's Attendance by Department
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li x-data="{ open: {{ request()->routeIs('admin_staff.workinghour.index') || request()->routeIs('admin_staff.department.index')  || request()->routeIs('admin.workinghour.index') ? 'true'  : 'false' }} }">
                        <a @click="open = !open" class="cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-3">
                                <i class="fa-solid fa-users fa-sm text-gray-200"></i>
                            </span>
                            <span class="text-sm tracking-wide truncate text-gray-200 ml-2">Departments</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open"  x-cloak class="ml-3 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin_staff.department.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                                {{ request()->routeIs('admin_staff.department.index') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <span class="inline-flex justify-center items-center ml-4">
                                        <i class="fa-solid fa-school fa-sm text-gray-200 "></i>
                                    </span>
                                    <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Department</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_staff.workinghour.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                                {{ request()->routeIs('admin_staff.workinghour.index') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <span class="inline-flex justify-center items-center ml-4">
                                        <i class="fa-solid fa-school fa-sm text-gray-200 "></i>
                                    </span>
                                    <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Working Hour</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li>
                        <a href="{{ route('admin.course.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                        {{ request()->routeIs('admin.course.index') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center ml-4">
                                <i class="fa-solid fa-school fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Courses</span>
                        </a>
                    </li> -->
                    <li x-data="{ open: {{ request()->routeIs('admin_staff.employee.index') || request()->routeIs('admin_staff.show.fingerprint') || request()->routeIs('admin.staff.index')  || request()->routeIs('admin.employee.index') || request()->routeIs('admin.student.index') ? 'true'  : 'false' }} }">
                        <a @click="open = !open" class="cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-3">
                                <i class="fa-solid fa-users fa-sm text-gray-200"></i>
                            </span>
                            <span class="text-sm tracking-wide truncate text-gray-200 ml-2">Employees</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open"  x-cloak class="ml-3 mt-1 space-y-1">
                            <!-- <li>
                                <a href="{{ route('admin.staff.index') }}" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white {{ request()->routeIs('admin.staff.index') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Admin Staff
                                </a>
                            </li> -->
                            <li>
                                <a href="{{ route('admin_staff.employee.index') }}" class="flex items-center h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin_staff.employee.index') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Employee Lists
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_staff.show.fingerprint') }}" class="flex items-center h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin_staff.show.fingerprint') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Enroll Fingerprint
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ route('admin.student.index') }}" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white {{ request()->routeIs('admin.student.index') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Student
                                </a>
                            </li> -->
                        </ul>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin_staff.attendance.holiday') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                        {{ request()->routeIs('admin_staff.attendance.holiday') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center ml-2">
                                <i class="fa-solid fa-gauge-high fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Set Holiday Date</span>
                        </a>
                    </li>
                    <li x-data="{ open: {{ request()->routeIs('admin.attendance.gracePeriodSet') || request()->routeIs('admin_staff.fingerprint') || request()->routeIs('admin.attendance.holiday') || request()->routeIs('admin_staff.attendance.gracePeriodSet') || request()->routeIs('admin_staff.delete_attendance') ? 'true'  : 'false' }} }">
                        <a @click="open = !open" class="w-full cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-3">
                                <i class="fa-solid fa-cogs text-gray-200"></i>
                            </span>
                            <span class="text-sm tracking-wide truncate text-gray-200 ml-2">Settings</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open"  x-cloak class="ml-3 mt-1 space-y-1 w-full">
                            <li>
                                <a href="{{ route('admin_staff.attendance.gracePeriodSet') }}" class="w-[500px] flex items-center h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin_staff.attendance.gracePeriodSet') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Set Grace Period
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ route('admin.attendance.holiday') }}" class=" w-full flex items-center  h-16 pl-4  text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                            {{ request()->routeIs('admin.attendance.holiday') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Set Holiday Date
                                </a>
                            </li> -->
                            <li>
                                <a href="{{route('admin_staff.delete_attendance')}}" class=" w-full flex items-center  h-16 pl-4  text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin_staff.delete_attendance') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}"
                                >
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Delete a date of attendance
                                </a>
                            </li>
                            <li>
                                <a href="{{route('admin_staff.fingerprint')}}" class=" w-full flex items-center  h-16 pl-4  text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                                {{ request()->routeIs('admin_staff.fingerprint') ? 'border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}"
                                >
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Enable / Disable Fingerprint
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                    <!-- <li x-data="{ open: false }">
                        <a @click="open = !open" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                            <span class="inline-flex justify-center items-center ml-4">
                                <i class="fa-solid fa-file-lines fa-sm text-gray-200"></i>
                            </span>
                            <span class=" text-sm tracking-wide truncate text-gray-200 ml-2">Reports</span>
                            <span class="ml-auto">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4">
                                    <path x-show="!open" fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path x-show="open" fill-rule="evenodd" d="M14.707 10.707a1 1 0 01-1.414 0L10 7.414 6.707 10.707a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                        <ul x-show="open" @click.away="open = false" x-cloak class="ml-4 mt-1 space-y-1">
                            <li>
                                <a href="" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white">
                                <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Admin Staff
                                </a>
                            </li>
                            <li>
                                <a href="" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Employee
                                </a>
                            </li>
                            <li>
                                <a href="" class="flex items-center h-11 pl-8 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white">
                                    <i class="fa-solid fa-user-circle fa-sm text-gray-200 mr-2"></i>Student
                                </a>
                            </li>
                        </ul>
                    </li> -->

                    <li>
                        <form id="logout" method="POST" action="{{ route('logout') }}" onsubmit="return confirmLogout(event)">
                            @csrf

                            <button type="submit" class="relative flex flex-row items-center w-full h-11 focus:outline-none  hover:bg-[#172029] text-white] dark:hover:bg-slate-700 text-gray-200 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                                <span class="inline-flex justify-center items-center ml-5">
                                    <i class="fa-solid fa-right-from-bracket fa-sm text-gray-200"></i>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate text-gray-200">{{ __('Sign Out') }}</span>
                            </button>
                        </form>
                    </li>
                </ul>
                    <p class="mb-14 px-5 py-3 hidden md:block text-center text-xs text-white">Copyright @2025</p>
            </div>
        </div>






    @elseif (Auth::user()->hasRole('sao'))

        <div x-cloak x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                            window.addEventListener('resize', () => {
                                isFullScreen = (window.innerHeight === screen.height);
                            });
                        " x-show="!isFullScreen" id="sidebarContainer"  class="fixed flex flex-col left-0 w-14 hover:w-48 md:w-48 bg-gradient-to-r from-red-500 to-orange-500 h-full text-black transition-all duration-300 border-r-2 border-gray-300 dark:border-gray-600 sidebar z-50">
            <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow mr-0.5">
                <ul class="flex flex-col py-2 space-y-1 text-gray-800" >
                    <a href="#" class="flex justify-center items-center">
                        <img class="w-32 h-auto object-contain" src="{{ asset('assets/img/logo.png') }}" alt="SCMS Logo">
                    </a>

                    <label class="relative flex flex-row justify-center items-center h-2 focus:outline-none   text-white-600 hover:text-white-800 border-l-4 border-transparent  pr-3 ">
                        <span class=" text-sm tracking-wide truncate text-gray-200">{{ Auth::user()->name }}</span>
                    </label>
                    <label class="relative flex flex-row justify-center h-5 focus:outline-none   text-white-600 hover:text-white-800 border-l-4 border-transparent   ">
                        <span class=" text-xs tracking-wide truncate text-gray-200">{{ Auth::user()->email }}</span>
                    </label>
                    <div class="border-t"></div>
                    <!-- <li>
                        <a href="{{ route('admin_staff.dashboard') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                        {{ request()->routeIs('admin_staff.dashboard') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center ml-4">
                                <i class="fa-solid fa-gauge-high fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-xs tracking-wide truncate text-gray-200">Current Day Monitoring</span>
                        </a>
                    </li> -->
                    <li class="w-full">
                        <a href="{{ route('sao.dashboard') }}" class="flex items-center  h-11 pl-4 pr-6 text-sm hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 over:bg-blue-800 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white 
                        {{ request()->routeIs('sao.dashboard') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                            <span class="inline-flex justify-center items-center">
                                <i class="fa-solid fa-list fa-sm text-gray-200 "></i>
                            </span>
                            <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Student Attendance</span>
                        </a>
                    </li>
                    <li>
                        <form id="logout" method="POST" action="{{ route('logout') }}" onsubmit="return confirmLogout(event)">
                            @csrf

                            <button type="submit" class="relative flex flex-row items-center w-full h-11 focus:outline-none  hover:bg-[#172029] text-white] dark:hover:bg-slate-700 text-gray-200 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                                <span class="inline-flex justify-center items-center ml-5">
                                    <i class="fa-solid fa-right-from-bracket fa-sm text-gray-200"></i>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate text-gray-200">{{ __('Sign Out') }}</span>
                            </button>
                        </form>
                    </li>
                </ul>
                    <p class="mb-14 px-5 py-3 hidden md:block text-center text-xs text-white">Copyright @2025</p>
            </div>
        </div>

    @endif

        <!-- this is HRD -->
    
@else



        <div x-cloak x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                            window.addEventListener('resize', () => {
                                isFullScreen = (window.innerHeight === screen.height);
                            });
                        " x-show="!isFullScreen" id="sidebarContainer"  class="fixed flex flex-col left-0 w-14 hover:w-48 md:w-48 bg-gradient-to-r from-red-500 to-orange-500 h-full text-black transition-all duration-300 border-r-2 border-gray-300 dark:border-gray-600 z-10 sidebar">
            <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow mr-0.5">
                <ul class="flex flex-col py-2 space-y-1 text-gray-800" >
                    @if (Auth::guard('employee')->user()->employee_photo && Storage::exists('public/employee_photo/' . Auth::guard('employee')->user()->employee_photo))
                        <a href="{{ asset('storage/employee_photo/' . Auth::guard('employee')->user()->employee_photo) }}" 
                        class="flex justify-center items-center hover:border border-red-500 rounded-full" 
                        title="Click to view Picture">
                            <img src="{{ asset('storage/employee_photo/' . Auth::guard('employee')->user()->employee_photo) }}" 
                                class="w-32 h-32 object-cover rounded-full mb-5 mt-3">
                        </a>
                    @else
                        <div class="flex justify-center items-center">
                            <img src="{{ asset('assets/img/user.png') }}" 
                                class="cursor-pointer w-32 h-32 hover:border hover:border-red-500 rounded-full" 
                                title="Click to view Picture">
                        </div>
                    @endif


                    <label class="relative flex flex-row justify-center items-center h-2 focus:outline-none   text-white-600 hover:text-white-800 border-l-4 border-transparent  pr-3 ">
                        <span class=" text-sm tracking-wide truncate text-gray-200 font-bold">{{ Auth::guard('employee')->user()->employee_firstname }} {{ Auth::guard('employee')->user()->employee_lastname }} </span>
                    </label>
                    <label class="relative flex flex-row justify-center h-5 focus:outline-none   text-white-600 hover:text-white-800 border-l-4 border-transparent   ">
                        <span class=" text-xs tracking-wide truncate text-gray-200 mt-.5">{{ Auth::guard('employee')->user()->department->department_abbreviation }}</span>
                    </label>
                    <div class="border-t"></div>
                    @php
                        $employee = Auth::guard('employee')->user()->password_change;
                    @endphp
                    @if($employee === 0)
                        
                    @else
                        <li>
                            <a href="{{ route('employee.dashboard') }}" class="relative flex flex-row items-center h-11 focus:outline-none  hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6 
                            {{ request()->routeIs('employee.dashboard') ? ' border-l-green-500 bg-[#172029] text-white' : 'hover:bg-blue-800 dark:hover:bg-slate-700 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white' }}">
                                <span class="inline-flex justify-center items-center ml-4">
                                    <i class="fa-solid fa-gauge-high fa-sm text-gray-200 "></i>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate text-gray-200">My Attendance Reports</span>
                            </a>
                        </li>
                        <li>
                            
                            <div x-data="{ open2: false }" x-cloak>
                                <!-- Modal Background -->
                                <a @click="open2 = true" class="hover:cursor-pointer relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-slate-700 text-white hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                                    <span class="inline-flex justify-center items-center ml-4">
                                        <i class="fa-solid fa-gauge-high fa-sm text-gray-200 "></i>
                                    </span>
                                    <span class="ml-2 text-sm tracking-wide truncate text-gray-200">Change password</span>
                                </a>
                                <div x-show="open2" x-transition.opacity.duration.300ms class="fixed inset-0 bg-black bg-opacity-50 z-50" @click="open2 = false"></div>

                                <!-- Modal Content -->
                                <div x-show="open2" x-transition.duration.300ms class="fixed inset-0 flex items-center justify-center z-50 mt-4">
                                    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full">
                                        <div class="mt-2 flex justify-between">
                                            <h2 class="text-lg font-semibold mb-4">Update Credentials</h2>
                                            <button @click="open2 = false" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2">
                                                <i class="fa-solid fa-times fa-xs"></i> Close
                                            </button>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="space-y-4">
                                            <form method="POST" action="{{ route('employee.change.credentials.submit', ['id' => Auth::guard('employee')->user()->id]) }}" onsubmit="return confirm('Are you sure you want to update?');">
                                                @csrf
                                                @method('PUT')
                                                
                                                
                                                <div class="mb-4">
                                                    <x-input-label for="username" :value="__('Enter Username')" />
                                                    <x-text-input id="username" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                                type="text"
                                                                name="username"
                                                                value="{{ old('username', Auth::guard('employee')->user()->username) }}"
                                                                required
                                                                autofocus
                                                                autocomplete="username" />
                                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                                </div>

                                                <div class="mb-4">
                                                    <x-input-label for="password" :value="__('Enter password')" />
                                                    <div class="relative">
                                                        <x-text-input id="password" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                                    type="password"
                                                                    name="password"
                                                                    value="{{ old('username', Auth::guard('employee')->user()->password) }}"
                                                                    required
                                                                    autocomplete="password" />
                                                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                                            <i id="eye-icon" class="fas fa-eye"></i> <!-- Font Awesome icon -->
                                                        </button>
                                                    </div>
                                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                    <span class="text-red-500">Note: </span><span>This is a hash password, erase to change password</span>
                                                </div>

                                                <div class="flex justify-center">
                                                    <x-primary-button class="">
                                                        {{ __('Save Changes') }}
                                                    </x-primary-button>
                                                </div>

                                            </form>
                                            <div>
                                                <span class="text-red-500">Note: </span>Upon next login, use the username and password as your new login credentials
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                    @endif
                    @if($employee === 0)
                        <li>
                            <form id="logout" method="POST" action="{{ route('logout.employee') }}" onsubmit="return confirmLogout(event)">
                                @csrf

                                <button type="submit" class="relative flex flex-row items-center w-full h-11 focus:outline-none  hover:bg-[#172029] text-white] dark:hover:bg-slate-700 text-gray-200 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                                    <span class="inline-flex justify-center items-center ml-5">
                                        <i class="fa-solid fa-right-from-bracket fa-sm text-gray-200"></i>
                                    </span>
                                    <span class="ml-2 text-sm tracking-wide truncate text-gray-200">{{ __('Sign Out') }}</span>
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <form id="logout" method="POST" action="{{ route('logout.employee_new_credentials') }}" onsubmit="return confirmLogout(event)">
                                @csrf

                                <button type="submit" class="relative flex flex-row items-center w-full h-11 focus:outline-none  hover:bg-[#172029] text-white] dark:hover:bg-slate-700 text-gray-200 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-green-500 hover:text-white pr-6">
                                    <span class="inline-flex justify-center items-center ml-5">
                                        <i class="fa-solid fa-right-from-bracket fa-sm text-gray-200"></i>
                                    </span>
                                    <span class="ml-2 text-sm tracking-wide truncate text-gray-200">{{ __('Sign Out') }}</span>
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
                    <p class="mb-14 px-5 py-3 hidden md:block text-center text-xs text-white">Copyright @2025</p>
            </div>
        </div>

@endif


<script>
    const togglePassword = document.getElementById('toggle-password');
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    togglePassword.addEventListener('click', () => {
        // Toggle the type attribute of the password input
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Toggle the Font Awesome icon between eye and eye-slash
        if (type === 'password') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });
</script>
<!-- end of admin navigation -->
    <script>
            function confirmLogout(event) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: 'Are you sure you want to logout?',
            text: "Save everything before leaving",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the deleteSelectedForm form programmatically
                document.getElementById('logout').submit();
            }
        });
    }
    </script>