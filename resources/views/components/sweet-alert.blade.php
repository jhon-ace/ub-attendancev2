<!-- resources/views/components/sweetalert.blade.php -->
@props(['type', 'message'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '{{ $type }}',
            title: '{{ ucfirst($type) }}',
            text: '{{ $message }}',
        });
    });
</script>
