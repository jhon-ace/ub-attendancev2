<x-app-layout>
    @if (Auth::user()->hasRole('admin'))
        <x-user-route-page-name :routeName="'admin.attendance.holiday'" />
    @elseif(Auth::user()->hasRole('admin_staff'))
        <x-user-route-page-name :routeName="'admin_staff.attendance.holiday'" />
    @else
        <x-user-route-page-name :routeName="'staff.attendance.holiday'" />
    @endif
    @if(Auth::user()->hasRole('admin'))
        <x-content-design>
            @if (session('success'))
                <x-sweetalert type="success" :message="session('success')" />
            @endif

            @if (session('info'))
                <x-sweetalert type="info" :message="session('info')" />
            @endif

            @if (session('error'))
                <x-sweetalert type="error" :message="session('error')" />
            @endif
            <!-- Content Area -->
            <div x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                window.addEventListener('resize', () => {
                    isFullScreen = (window.innerHeight === screen.height);
                });
                " class="rounded-md p-2 sm:p-2 md:p-2 lg:p-2 text-black font-medium">
                <div class="relative">
                    <div class="container shadow-lg p-5 sm:p-6 md:p-7 lg:p-8 bg-white rounded-md text-black font-medium h-[91vh]"
                        :style="{ 'width': isFullScreen ? 'calc(100vw - 16px)' : 'auto', 'margin-left': isFullScreen ? '-192px' : '0' }">
                        <h1 class="font-bold uppercase">Admin / Manage Holiday</h1>
                        <div class="flex justify-center mt-8 w-full">
                            <div x-data="{ openTab: 1, openModal: false }" class="w-full">
                                <div class="flex justify-start mb-4">
                                    <!-- Tab Buttons -->
                                    <button @click="openTab = 1" :class="{'bg-blue-500 text-white': openTab === 1, 'border border-gray-400 text-black': openTab !== 1}" class="px-4 py-2 rounded-t-md mr-2">Add Holiday</button>
                                    <button @click="openTab = 2; openModal = true" :class="{'bg-blue-500 text-white': openTab === 2, 'border border-gray-400 text-black': openTab !== 2}" class="px-4 py-2 rounded-t-md">View Holiday</button>
                                </div>

                                <!-- Modal Structure -->
                                <div x-cloak x-show="openModal" @click.away="openModal = false" class="w-full fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                                        <h2 class="text-xl font-semibold mb-4">Holiday Information</h2>
                                        <div class="flex flex-col justiy-content mb-6">
                                            <p class="text-center mb-4 text-gray-700 text-sm">1. Holiday dates are excluded from calculations and are not included in the attendance or working hour computations.</p>
                                            <p class="text-center text-gray-700 text-sm">2. Please add holiday dates before the actual dates to avoid system automatic absences for those dates in calculations.</p>
                                        </div>
                                        <div class="flex justify-end">
                                            <button @click="openModal = false" class="bg-blue-500 text-white px-4 py-2 rounded-md">OK</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Content -->
                                <div x-show="openTab === 1" class="w-[50%] mx-auto mb-4">
                                    <form action="{{ route('admin.attendance.setHoliday') }}" method="POST" class="w-[78%] mx-auto">
                                        <x-caps-lock-detector />
                                        @csrf
                                        <br>
                                        <p class="text-[14px]">
                                            <text class="text-red-500">Note:</text> This will apply to all employees' attendance.
                                        </p>
                                        <br>
                                        <div class="mb-2">
                                            <label for="selected-date" class="block mb-2 text-left">Select a Date:</label>
                                            <input type="date" id="selected-date" name="selected_date" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full" required>
                                        </div>
                                        <div class="flex mb-4 mt-10 justify-center">
                                            <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                Submit Date as Holiday
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div x-cloak x-show="openTab === 2" class="w-[50%] mx-auto mb-4">
                                    <!-- Content for Tab 2 -->
                                    <div class="flex flex-col items-center mt-8 w-full mx-auto">
                                        <!-- Title and Description -->
                                        <p class="text-black text-2xl font-semibold text-center mb-2">List of Added Holidays</p>
                                        <!-- Table -->
                                        <div class="w-[80%] lg:w-[60%] flex justify-center mb-6">
                                            @if($holidays->isNotEmpty())
                                                <div class="overflow-y-auto h-64 w-full"> <!-- Adjust the height as needed -->
                                                    <table class="border border-collapse border-gray-300 w-full bg-white shadow-md rounded-lg">
                                                        <thead>
                                                            <tr class="bg-gray-100 border-b border-gray-300">
                                                                <th class="p-3 text-left text-gray-700 font-medium">Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($holidays as $holiday)
                                                                <tr class="border-b border-gray-300 text-center">
                                                                    <td class="p-3 text-gray-800">{{ \Carbon\Carbon::parse($holiday->check_in_date)->format('F j, Y') }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="font-bold text-red-500 mb-4 text-center">No holiday dates confirmed yet.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-content-design>


    @elseif(Auth::user()->hasRole('admin_staff'))

        <x-content-design>
            @if (session('success'))
                <x-sweetalert type="success" :message="session('success')" />
            @endif

            @if (session('info'))
                <x-sweetalert type="info" :message="session('info')" />
            @endif

            @if (session('error'))
                <x-sweetalert type="error" :message="session('error')" />
            @endif
            <!-- Content Area -->
            <div x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                window.addEventListener('resize', () => {
                    isFullScreen = (window.innerHeight === screen.height);
                });
                " class="rounded-md p-2 sm:p-2 md:p-2 lg:p-2 text-black font-medium">
                <div class="relative">
                    <div class="container shadow-lg p-5 sm:p-6 md:p-7 lg:p-8 bg-white rounded-md text-black font-medium h-[91vh]"
                        :style="{ 'width': isFullScreen ? 'calc(100vw - 16px)' : 'auto', 'margin-left': isFullScreen ? '-192px' : '0' }">
                        <h1 class="font-bold uppercase">Staff / Manage Holiday</h1>
                        <div class="flex justify-center mt-8 w-full">
                            <div x-data="{ openTab: 2, openModal: false }" class="w-full">
                                <div class="flex justify-start mb-4">
                                    <!-- Tab Buttons -->
                                    <!-- <button @click="openTab = 1" :class="{'bg-blue-500 text-white': openTab === 1, 'border border-gray-400 text-black': openTab !== 1}" class="px-4 py-2 rounded-t-md mr-2">Add Holiday</button> -->
                                    <button @click="openTab = 2; openModal = true" :class="{'bg-blue-500 text-white': openTab === 2, 'border border-gray-400 text-black': openTab !== 2}" class="px-4 py-2 rounded-t-md">View Holiday</button>
                                </div>

                                <!-- Modal Structure -->
                                

                                <!-- Tab Content -->
                                <div x-show="openTab === 1" class="w-[50%] mx-auto mb-4">
                                    <form action="{{ route('admin_staff.attendance.setHoliday') }}" method="POST" class="w-[78%] mx-auto">
                                        <x-caps-lock-detector />
                                        @csrf
                                        <br>
                                        <p class="text-[14px]">
                                            <text class="text-red-500">Note:</text> This will apply to all employees' attendance.
                                        </p>
                                        <br>
                                        <div class="mb-2">
                                            <label for="selected-date" class="block mb-2 text-left">Select a Date:</label>
                                            <input type="date" id="selected-date" name="selected_date" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full" required>
                                        </div>
                                        <div class="flex mb-4 mt-10 justify-center">
                                            <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                Submit Date as Holiday
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div x-cloak x-show="openTab === 2" class="w-[50%] mx-auto mb-4">
                                    <!-- Content for Tab 2 -->
                                    <div class="flex flex-col w-full mx-auto">
                                        <!-- Title and Description -->
                                        <p class="text-black text-2xl font-semibold text-center mb-2">List of Added Holidays</p>
                                        <!-- Table -->
                                         <div class="flex justify-between">
                                                <p class="text-black text-sm px-3 py-2 rounded">Total Count of Holidays: <span class="text-red-500 font-bold">{{ $holidayCount }}</span></p>
                                                <div x-data="{ open: false, open2:false }">
                                                    <button @click="open = true, open2 = true" class="bg-blue-500 text-white text-sm px-3 py-2 rounded hover:bg-blue-700">
                                                        <i class="fa-solid fa-plus fa-xs" style="color: #ffffff;"></i> Add Holiday
                                                    </button>

                                                    <div x-cloak x-show="open2" @click.away="open2 = false" class="w-full fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                                                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                                                            <h2 class="text-xl font-semibold mb-4">Holiday Information</h2>
                                                            <div class="flex flex-col justify-content mb-6">
                                                                <p class="text-center mb-4 text-gray-700 text-sm">1. Holiday dates are excluded from calculations and are not included in the attendance or working hour computations.</p>
                                                                <p class="text-center text-gray-700 text-sm">2. Please add holiday dates before the actual dates to avoid system automatic absences for those dates in calculations.</p>
                                                            </div>
                                                            <div class="flex justify-end">
                                                                <button @click="open2 = false" class="bg-blue-500 text-white px-4 py-2 rounded-md">OK</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                        <div @click.away="open = true" class="w-[30%] max-h-[100%] bg-white p-6 rounded-lg shadow-lg mx-auto overflow-y-auto">
                                                            <div class="flex justify-between items-center pb-3">
                                                                <p class="text-xl font-bold">Add Holiday Date</p>
                                                                <button @click="open = false" class="text-black text-sm px-3 py-2 rounded hover:text-red-500">X</button>
                                                            </div>
                                                            <div class="mb-4">
                                                                <form action="{{ route('admin_staff.attendance.setHoliday') }}" method="POST">
                                                                    <x-caps-lock-detector />
                                                                    @csrf

                                                                    <div class="mb-2">
                                                                        <label for="selected-date" class="block mb-2 text-left">Select a Date:</label>
                                                                        <input type="date" id="selected-date" name="selected_date" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full" required>
                                                                    </div>

                                                                    <div class="mb-2">
                                                                        <label for="holiday_description" class="block text-gray-700 text-md font-bold mb-2">Holiday Description</label>
                                                                        <input type="text" name="holiday_description" id="holiday_description" value="{{ old('holiday_description') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('holiday_description') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('holiday_description')" class="mt-2" />
                                                                    </div>

                                                                    <div class="flex mb-4 mt-10 justify-center">
                                                                        <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                            Submit Holiday
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                         </div>
                                        
                                        <div class="w-[80%] lg:w-[100%] flex justify-center mb-6 mt-4">
                                            @if($holidays->isNotEmpty())
                                                <div class="overflow-y-auto h-64 w-full"> <!-- Adjust the height as needed -->
                                                    <table class="border border-collapse border-gray-300 w-full bg-white shadow-md rounded-lg">
                                                        <thead>
                                                            <tr class="bg-gray-100 border-b border-gray-300">
                                                                <th class="p-3 text-left text-gray-700 font-medium">Date of Holiday</th>
                                                                <th class="p-3 text-left text-gray-700 font-medium">Description</th>
                                                                <th class="p-3 text-left text-gray-700 font-medium">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($holidays as $holiday)
                                                                <tr class="border-b border-gray-300 text-left">
                                                                    <td class="p-3 text-gray-800">{{ \Carbon\Carbon::parse($holiday->check_in_date)->format('F j, Y') }}</td>
                                                                    <td class="p-3 text-gray-800">{{ $holiday->holiday_description }}</td> 
                                                                    <td class="p-3 text-gray-800">
                                                                        <form id="deleteSelected" action="{{ route('admin_staff.holiday.destroy', $holiday->id)}}" method="POST" 
                                                                            onsubmit="return confirm('Are you sure you want to delete this holiday, {{ \Carbon\Carbon::parse($holiday->check_in_date)->format('F j, Y') }} ?')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="bg-red-500 text-white text-sm px-3 py-2 rounded hover:bg-red-700">
                                                                                <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i>
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                            @else
                                                <p class="font-bold text-red-500 mb-4 text-center">No holiday dates confirmed yet.</p>
                                            @endif
                                        </div>
                                        <div class="flex justify-center">
                                            <p class="font-semibold text-center">
                                                Dates that are added/listed here is not included in calculations of working hour / attendances of all employees.
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-content-design>
    @else

    @endif
    
</x-app-layout>

<x-show-hide-sidebar
    toggleButtonId="toggleButton"
    sidebarContainerId="sidebarContainer"
    dashboardContentId="dashboardContent"
    toggleIconId="toggleIcon"
    toggleIconIdFullscreen="toggleIcon2"
/>

