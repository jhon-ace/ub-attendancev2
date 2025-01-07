@if (Auth::user()->hasRole('admin'))
    <x-app-layout>
        <x-user-route-page-name :routeName="'admin.department.index'" />
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
                        <livewire:admin.show-department-table />
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
    />
@elseif (Auth::user()->hasRole('admin_staff'))
    <x-app-layout>
        <x-user-route-page-name :routeName="'staff.department.index'" />
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
                        <livewire:admin.show-department-table />
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
    />

    
@endif