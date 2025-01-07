<x-app-layout>
    @if(Auth::user()->hasRole('admin'))
        <x-user-route-page-name :routeName="'admin.attendance.employee_attendance.search'" />
    @else
        <x-user-route-page-name :routeName="'admin_staff.attendance.employee_attendance.search'" />
    @endif
    <x-content-design>
        <!-- Content Area -->
        <div x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
            window.addEventListener('resize', () => {
                isFullScreen = (window.innerHeight === screen.height);
            });
            " class="rounded-md p-2 sm:p-2 md:p-2 lg:p-2 text-black font-medium">
            <div class="relative">
                <div class="container shadow-lg p-5 sm:p-6 md:p-7 lg:p-8 bg-white rounded-md text-black font-medium"
                    :style="{ 'width': isFullScreen ? 'calc(100vw - 16px)' : 'auto', 'margin-left': isFullScreen ? '-192px' : '0' }">
                    <livewire:admin.search-employee-attendance />
                </div>
            </div>
        </div>
    </x-content-design>
</x-app-layout>

<x-show-hide-sidebar
    toggleButtonId="toggleButton"
    sidebarContainerId="sidebarContainer"
    dashboardContentId="dashboardContent"
    toggleIconId="toggleIcon"
    toggleIconIdFullscreen="toggleIcon2"
/>