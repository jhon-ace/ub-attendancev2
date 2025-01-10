<div class="mb-4">
    @if(Auth::guard('employee')->check())

            @php
                session(['selectedSchool' => $selectedSchool]);
                session(['selectedDepartment4' => $selectedDepartment4]);
                session(['selectedEmployee' => $selectedEmployee])
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
            <div class="flex justify-between sm:-mt-4">
                <div class="font-bold text-md  text-md text-black mt-2 uppercase tracking-widest">WELCOME! <span class="text-red-500">{{ Auth::guard('employee')->user()->employee_lastname }}, {{ Auth::guard('employee')->user()->employee_firstname }}</span></div>
                <div class="font-bold text-md  text-md text-black mt-2 uppercase tracking-widest">EMPLOYEE ID: <span class="text-red-500">{{ Auth::guard('employee')->user()->employee_id }}</span></div>
            </div>
            <hr class="border-gray-200 my-4">
            <div>
                <div><span class="text-red-500">Note: </span>Change first your login credentials for security purposes for viewing your DTR logs</div>
                <span class="mt-5">Current Credentials used for login (For first time log in only):</span>
                <div class="flex-col ml-10">
                    <div>
                        <span>Employee ID: {{ Auth::guard('employee')->user()->employee_id}}</span>
                    </div>
                    <div>
                        <span class="ml-5">Lastname: {{ Auth::guard('employee')->user()->employee_lastname}}</span>
                    </div>
                </div>
                <div x-data="{ open: false }" @keydown.window.escape="open = false" x-cloak>
                    <!-- Modal Trigger Button -->
                    <button @click="open = true" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded mt-10">
                        Click this button to add new credentials (username & password)
                    </button>

                    <!-- Modal Background -->
                    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 z-50" @click="open = false"></div>

                    <!-- Modal Content -->
                    <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50 mt-4">
                        <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full ">
                            <div class="mt-2 flex justify-between">
                                <h2 class="text-lg font-semibold mb-4">My Account</h2>
                                <button @click="open = false" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2 "><i class="fa-solid fa-times fa-xs"></i> Close</button>
                            </div>
                            <!-- Modal Body -->
                            <div class="space-y-4">

                                <form method="POST" action="{{ route('employee.change.credentials.submit', ['id' => Auth::guard('employee')->user()->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <h4 class="text-center mb-10 text-lg uppercase tracking-widest">Update Credentials</h4>

                                    <div class="mb-4">
                                        <x-input-label for="username" :value="__('Enter Username')" />
                                        <x-text-input id="username" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                    type="text"
                                                    name="username"
                                                    required
                                                    autofocus
                                                    autocomplete="username" />
                                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>
                        
                                    <div class="mb-4">
                                        <x-input-label for="password" :value="__('Enter password')" />
                                        <x-text-input id="password" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                    type="text"
                                                    name="password"
                                                    required
                                                    autocomplete="password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                        
                                    <div class="flex justify-center">
                                        <x-primary-button class="">
                                            {{ __('Save') }}
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
            </div>
    @endif
</div>