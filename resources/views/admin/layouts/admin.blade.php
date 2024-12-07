<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>


    <link rel="stylesheet" href="{{asset('css/style.css')}}">
     <link rel="stylesheet" href="{{asset('css/admin/admin.css')}}">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

     <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Include Bootstrap CSS in your layout file -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.tiny.cloud/1/5meve3tc4og8v2gfuv6p5963iz262tkufjfpmhxgyvleg1v6/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- Buttons Extension CSS -->

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Buttons Extension JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<!-- JSZip (required for Excel export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDFMake (required for PDF export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Buttons for CSV, Excel, PDF, and Print -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

   @include('admin.components.sidebar')


        <!-- Page Content -->
        <main class="home-section">
<div>
    {{ $slot }}

</div>

        </main>

        <!-- Global Components -->
        @include('components.message')

        @include('admin.components.modal')

        <script src="{{ asset('js/form.js') }}"></script>
        <script src="{{ asset('js/admin/dashboard.js') }}"></script>
        <script src="{{ asset('js/admin/sidebar.js') }}"></script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

      <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

    <script>
        // Execute SweetAlert script if it exists
        @if(isset($script))
            {!! $script !!}
        @endif

        // Handle AJAX errors
        $(document).ajaxError(function(event, xhr) {
            if (xhr.status === 403 && xhr.responseJSON && xhr.responseJSON.script) {
                eval(xhr.responseJSON.script);
            }
        });

        $(document).ajaxError(function(event, xhr) {
            if (xhr.status === 403 && xhr.responseJSON && xhr.responseJSON.html) {
                $('body').append(xhr.responseJSON.html);
            }
        });

        // Check if current user is not allowed
        @if(auth()->check() && Session::get('role') === 'user')
            Swal.fire({
                title: "Access Denied!",
                text: "You do not have permission to access the admin area.",
                icon: "error",
                showConfirmButton: true,
                confirmButtonText: "Go Back",
                confirmButtonColor: "#d33",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/";
                }
            });
        @endif
    </script>
</body>
</html>
