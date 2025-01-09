<x-app-layout>
    <x-user-route-page-name :routeName="'employee.dashboard'" />
    <div class="transition-all duration-300 min-h-screen flex flex-col flex-auto flex-shrink-0 antialiased bg-gradient-to-r from-yellow-400 to-red-500 text-black dark:text-white">
        <div id="dashboardContent" class="h-full ml-14  md:ml-48 transition-all duration-300">
            <div class="max-w-full mx-auto">
                <div x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                        window.addEventListener('resize', () => {
                            isFullScreen = (window.innerHeight === screen.height);
                        });
                    " x-show="!isFullScreen" class="flex w-full p-2 bg-gradient-to-r from-yellow-800 to-orange-800 justify-between">
                    <div class="ml-2 mt-0.5 font-semibold text-xs tracking-wide text-white uppercase sm:text-sm md:text-md lg:text-md xl:text-md">
                        <button id="toggleButton" class="text-white mr-0 px-3 py-1 rounded-md border border-transparent hover:border-blue-500">
                            <i id="toggleIcon" class="fa-solid fa-bars" style="color: #ffffff;"></i>
                        </button>
                        <span>{{ Auth::guard('employee')->user()->school->school_name }}</span>
                    </div>
                    <div x-cloak class="relative" x-data="{ open: false }">
                        <div @click="open = !open" class="mr-5 cursor-pointer">
                            <i class="fa-solid fa-user-gear px-3 py-2 rounded-md border border-transparent hover:border-blue-500" style="color: #ffffff;"></i>
                        </div>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
                            
                            <form method="POST" action="{{ route('logout.employee') }}">
                                @csrf
                                <a href="{{ route('logout.employee') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <div x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                    window.addEventListener('resize', () => {
                        isFullScreen = (window.innerHeight === screen.height);
                    });
                    " class="rounded-md p-2 sm:p-2 md:p-2 lg:p-2 text-black font-medium">
                    <div class="relative">
                        <div class="container shadow-lg p-5 sm:p-6 md:p-7 lg:p-8 bg-white rounded-md text-black font-medium"
                            :style="{ 'width': isFullScreen ? 'calc(100vw - 16px)' : 'auto', 'margin-left': isFullScreen ? '-192px' : '0' }">
                            <livewire:admin.employee-attendance-report />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-show-hide-sidebar
    toggleButtonId="toggleButton"
    sidebarContainerId="sidebarContainer"
    dashboardContentId="dashboardContent"
    toggleIconId="toggleIcon"
    toggleIconIdFullscreen="toggleIcon2"
/>