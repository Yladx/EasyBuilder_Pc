@if(session('success') || session('error') || session('warning') || session('status'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Okay'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                title: 'Warning!',
                text: '{{ session('warning') }}',
                icon: 'warning',
                confirmButtonText: 'Understood'
            });
        @endif

        @if(session('status'))
            Swal.fire({
                title: 'Status',
                text: '{{ session('status') }}',
                icon: 'info',
                confirmButtonText: 'Close'
            });
        @endif
    });
</script>
@endif



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
