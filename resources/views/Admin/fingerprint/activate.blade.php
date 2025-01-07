<x-app-layout>
    @if (Auth::user()->hasRole('admin'))
        <x-user-route-page-name :routeName="'admin.attendance.gracePeriodSet'" />
    @else
        <x-user-route-page-name :routeName="'admin_staff.fingerprint'" />
    @endif
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
        @if (Auth::user()->hasRole('admin'))
            <div x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                window.addEventListener('resize', () => {
                    isFullScreen = (window.innerHeight === screen.height);
                });
                " class="rounded-md p-2 sm:p-2 md:p-2 lg:p-2 text-black font-medium">
                <div class="relative">
                    <div class="container shadow-lg p-5 sm:p-6 md:p-7 lg:p-8 bg-white rounded-md text-black font-medium"
                        :style="{ 'width': isFullScreen ? 'calc(100vw - 16px)' : 'auto', 'margin-left': isFullScreen ? '-192px' : '0' }">
                        <h1 class="font-bold uppercase">Admin / Manage Grace Period of Attendance</h1>
                        <div class="flex justify-center mt-8 w-full">
                            <div class="w-[50%] flex justify-center mb-4 mx-auto">
                                @if($gracePeriod->isNotEmpty())
                                    @foreach($gracePeriod as $period)
                                        <table class="border border-collapse border-1 border-black w-full mb-4">
                                            <caption><p>Note: The Grace Period is applied to all departments and employees for their time-ins.</p><br></caption>
                                            <thead>
                                                <tr class="border border-collapse border-1 border-black">
                                                    <th class="border border-collapse border-1 border-black">Grace Period</th>
                                                    <th>Action</th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                                <tr class="border border-collapse border-1 border-black text-center p-2">
                                                    <td class="border border-collapse border-1 border-black p-2">{{ round($period->grace_period * 60) }} minutes</td>
                                                    <td class="border border-collapse border-1 border-black p-2">
                                                        <div class="flex justify-center items-center space-x-2">
                                                            <div x-data="{ open: false }">
                                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Edit
                                                                </a>
                                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                            <p class="text-xl font-bold">Edit Grace Period</p>
                                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                        </div>
                                                                        <div class="mb-4">
                                                                            <form id="" action="{{ route('admin.attendance.gracePeriod.update', $period->id) }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to update?');">
                                                                                <x-caps-lock-detector />
                                                                                @csrf
                                                                                @method('PUT')
                                                                                    
                                                                                    <div class="mb-4">
                                                                                        @php
                                                                                            $minutes = $period->grace_period * 60;
                                                                                            $roundedMinutes = round($minutes);
                                                                                        @endphp
                                                                                        <label for="grace_period" class="block text-gray-700 text-md font-bold mb-2 text-left">New Grace Period</label>
                                                                                        <input type="float" name="grace_period" id="grace_period" min="0" max="60" value="{{ $roundedMinutes }}" class="shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('grace_period') is-invalid @enderror" autofocus required>
                                                                                        <x-input-error :messages="$errors->get('grace_period')" class="mt-2" />
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
                                                            <form action="{{ route('admin.attendance.gracePeriod.delete', $period->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this grace period?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="bg-red-500 text-white text-sm px-2 py-0.5 rounded hover:bg-red-700">
                                                                    <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endforeach
                                @else
                                    <p class="font-bold text-red-500 mb-4">No Grace Period registered</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <div>
                                <form action="{{ route('admin.attendance.gracePeriod') }}" method="POST" class="">
                                <x-caps-lock-detector />
                                @csrf
                                    <div class="mb-2">
                                        <label for="grace_period" class="block  mb-2 text-left">Enter New grace period:</label>
                                        <input type="number" id="grace_period" name="grace_period" min="0" max="60" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full max-w-md" required  autofocus >
                                        <p>Note: This is minutes only.</p>
                                    </div> 


                                    <div class="flex mb-4 mt-10 justify-center">
                                        <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                            Save Period
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        @elseif (Auth::user()->hasRole('admin_staff'))
            <div  class="rounded-md p-2 sm:p-2 md:p-2 lg:p-2 text-black font-medium">
                <div class="relative">
                    <div class="container shadow-lg p-5 sm:p-6 md:p-7 lg:p-8 bg-white rounded-md text-black font-medium"
                        :style="{ 'width': isFullScreen ? 'calc(100vw - 16px)' : 'auto', 'margin-left': isFullScreen ? '-192px' : '0' }">
                        <h1 class="font-bold uppercase">Admin Staff / Activation of Fingerprint</h1>

                        <div class="flex justify-center mt-8 w-full">
                            <div class="w-[50%] flex justify-center mb-4 mx-auto">
                                <!-- Live Fingerprint View -->
                                <div class="rounded-lg p-4  flex flex-col items-center">
                                    @foreach($fingerprints as $finger)
                                        <h1>
                                            Fingerprint: 

                                                @if($finger->fingerprint_status == 0)
                                                   <span class="text-black font-bold">OFF / DEACTIVATED</span> 
                                                @else
                                                    <span class="text-red-500 font-bold">ON / ACTIVATED</span> 
                                                @endif
                                        </h1>
                                        <div class="flex justify-center">
                                            <div>
                                                <form action="{{ route('admin_staff.enable_fingerprint', $finger->id)}}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    @if($finger->fingerprint_status == 0)

                                                        <input type="text" name="set_status" value="1" hidden>

                                                        <button type="submit" class="text-white py-2 px-4 rounded mt-4 bg-blue-500 hover:bg-blue-800">
                                                            Enable Fingerprint
                                                        </button>
                                                    @else
                                                        <input type="text" name="set_status" value="0" hidden>

                                                        <button type="submit" class="text-white py-2 px-4 rounded mt-4 bg-blue-500 hover:bg-blue-800">
                                                            Disable Fingerprint
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else

        @endif
    </x-content-design>
</x-app-layout>

<x-show-hide-sidebar
    toggleButtonId="toggleButton"
    sidebarContainerId="sidebarContainer"
    dashboardContentId="dashboardContent"
    toggleIconId="toggleIcon"
    toggleIconIdFullscreen="toggleIcon2"
/>
