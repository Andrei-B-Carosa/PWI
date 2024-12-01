<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>HRIS |</title>
    <meta charset="utf-8" />
    <meta content="{{ csrf_token() }}" name="csrf-token" id="csrf-token">
    <meta content="{{ url('assets') }}" name="asset-url">
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    {{-- <link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if ( document.documentElement ) {
            if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if ( localStorage.getItem("data-bs-theme") !== null ) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

                @include('employee._layout.navbar')

                <div class="toolbar py-3 py-lg-5" id="kt_toolbar">
                    <div id="kt_toolbar_container" class=" container-xxl  d-flex flex-stack flex-wrap">
                        <div class="page-title d-flex flex-column">
                            <h1 id="page-heading" class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-3 m-0">
                                
                                <span class="page-desc text-muted fs-7 fw-semibold"> </span>
                            </h1>
                        </div>
                    </div>
                </div>

                <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start  container-xxl ">
                    <div class="content flex-row-fluid" id="Page">

                    </div>
                </div>

                {{-- @include('employee._layout.footer') --}}

            </div>
        </div>
    </div>


    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up"><span class="path1"></span><span class="path2"></span></i>
    </div>

    <script>
        var hostUrl = "assets/index.html";
    </script>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

    <script type="text/javascript">
        var asset_url = $('meta[name="asset-url"]').attr("content");
        var csrf_token = $('meta[name="csrf-token"]').attr("content");
        var app = $("#Page");
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
        var page_block = new KTBlockUI(document.querySelector('.page'), {
            message: `<div class="blockui-message"><span class="spinner-border text-primary"></span>Loading. . .</div>`,
        });
    </script>

    <script src="{{ asset('js/employee/navbar.js') }}" type="module"></script>
</body>
</html>
