<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>@yield('title')</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}"
        type="image/png" />
    <!--plugins-->
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}"
        rel="stylesheet" />
    <!--Data Tables -->
    <link
        href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css">
    <link
        href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css">
    <!--plugins-->
    <link
        href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}"
        rel="stylesheet" />
    <link
        href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}"
        rel="stylesheet" />
    <link
        href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}"
        rel="stylesheet" />
    <link
        href="{{ asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css') }}"
        rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Vector CSS -->
    <link
        href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}"
        rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
        href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Roboto&display=swap" />
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/icons.css') }}" />
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dark-sidebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('modules/employee/css/validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pace.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/smart-wizard/css/smart_wizard_all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        @include('core::partials.sidebar')
        @include('core::partials.header')

        <!--page-wrapper-->
        <div class="page-wrapper">
            <!--page-content-wrapper-->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <!--breadcrumb-->
                           @yield('content')
                    <!--end breadcrumb-->

                </div>
             
            </div>
            <!--end page-content-wrapper-->
        </div>
        <!--end page-wrapper-->

        <!--start overlay-->
        <div class="overlay toggle-btn-mobile"></div>
        <!--end overlay-->



        @include('core::partials.footer')
    </div>
    <!-- end wrapper -->

    <!-- JavaScript -->
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}">
    </script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}">
    </script>
    <script
        src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}">
    </script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}">
    </script>
    <script
        src="{{ asset('assets/plugins/notifications/js/notifications.min.js') }}">
    </script>
    <script
        src="{{ asset('assets/plugins/fancy-file-uploader/jquery.ui.widget.js') }}">
    </script>
    <script
        src="{{ asset('assets/plugins/fancy-file-uploader/jquery.fileupload.js') }}">
    </script>
    <script
        src="{{ asset('assets/plugins/fancy-file-uploader/jquery.iframe-transport.js') }}">
    </script>
    <script
        src="{{ asset('assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js') }}">
    </script>
    <script
        src="{{ asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js') }}">
    </script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Data Tables js-->
    <script
        src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}">
    </script>

    <!-- Pace JS -->
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Smart Wizard JS -->
    <script src="{{ asset('assets/plugins/smart-wizard/js/jquery.smartWizard.min.js') }}"></script>
    <!-- App JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @include('core::partials.toast')
    @include('core::partials.confirm-modal')
    @stack('scripts')
</body>

</html>
