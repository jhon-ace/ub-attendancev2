@props(['toggleButtonId', 'sidebarContainerId', 'dashboardContentId', 'toggleIconId'])


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('{{ $toggleButtonId }}');
        const sidebarContainer = document.getElementById('{{ $sidebarContainerId }}');
        const dashboardContent = document.getElementById('{{ $dashboardContentId }}');
        const toggleIcon = document.getElementById('{{ $toggleIconId }}');

        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                sidebarContainer.classList.toggle('hidden');
                const sidebarShown = !sidebarContainer.classList.contains('hidden');

                if (sidebarShown) {
                    dashboardContent.classList.add('ml-14', 'md:ml-48');
                    toggleIcon.classList.remove('fa-solid', 'fa-bars');
                    toggleIcon.classList.add('fa-solid', 'fa-bars');

                } else {
                    dashboardContent.classList.remove('ml-14', 'md:ml-48');
                    toggleIcon.classList.remove('fa-solid', 'fa-bars');
                    toggleIcon.classList.add('fa-solid', 'fa-bars');

                }
            });
        }
    });
</script>