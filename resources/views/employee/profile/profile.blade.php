{{-- <div class="page-employee-profile">
    <div class="row g-7">
        <div class="d-flex flex-column flex-lg-row">
            <div class="d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px mb-7 me-3">
                <div class="card card-flush mb-0">
                    <div class="card-body pb-5">
                        <ul class="nav nav-tabs nav-pills d-inline-block border-0 me-5 mb-3 mb-md-0 fs-6">
                            <li>
                                <div class="menu-item">
                                    <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">
                                        Menu Options
                                    </h2>
                                </div>
                            </li>
                            <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold profile-tab" tab-loaded="false"
                                    data-tab="1" data-bs-toggle="tab" data-tab-title="Personal Information" href="#tab_content1">
                                    Personal Information
                                </a>
                            </li>
                            <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold profile-tab" tab-loaded="true"
                                    data-tab="2" data-bs-toggle="tab" data-tab-title="Employment Details" href="#tab_content2">
                                    Employment Details
                                </a>
                            </li>
                            <li class="nav-item  me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold profile-tab" tab-loaded="false"
                                    data-tab="6" data-tab-title="Document Attachments" data-bs-toggle="tab" href="#tab_content6">
                                    Document Attachments
                                </a>
                            </li>
                            <li class="nav-item  me-0 mb-2">
                                <a class="nav-link text-danger text-active-light fw-bold profile-tab" tab-loaded="false"
                                    data-tab="8" data-bs-toggle="tab" data-tab-title="Account Security" href="#tab_content8">
                                    Account Security
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid mb-7">
                <div class="flex-lg-row-fluid">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade tab1" id="tab_content1" role="tabpanel">
                            <div class="card card-flush h-lg-100" id="card1">
                                <div class="card-header py-7">
                                    <div class="card-title">
                                        <h2 class="tab_title"></h2>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-3 cancel d-none">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary edit d-none">
                                            <span class="indicator-label">
                                                Edit Details
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-success save d-none">
                                            <span class="indicator-label">
                                                Save Details
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                    <form id="form1" card-id="#card1" action="/hris/admin/201_employee/employee_details/update"></form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade tab2" id="tab_content2" role="tabpanel">
                            <div class="card card-flush h-lg-100" id="card2">
                                <div class="card-header py-7">
                                    <div class="card-title">
                                        <h2 class="tab_title"></h2>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-3 cancel d-none">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary edit d-none">
                                            <span class="indicator-label">
                                                Edit Details
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-success save d-none">
                                            <span class="indicator-label">
                                                Save Details
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade tab6" id="tab_content6" role="tabpanel">
                            <div class="card card-flush h-lg-100" id="card6">
                                <div class="card-header py-7">
                                    <div class="card-title">
                                        <h2 class="tab_title"></h2>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary me-3 upload-document d-none" data-bs-toggle="modal" data-bs-target="#modal_upload_document">
                                            Upload Document
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade tab8" id="tab_content8" role="tabpanel">
                            <div class="card card-flush h-lg-100" id="card8">
                                <div class="card-header py-7">
                                    <div class="card-title">
                                        <h2 class="tab_title"></h2>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}


<div class="page-profile">
    <div class="card mb-5">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <div class="symbol-label fs-5x bg-light-primary text-primary">
                            {{ $data['fullname'][0] }}
                        </div>
                        <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-{{ $data['work_status']==1 ?'success':'danger' }}
                                    rounded-circle border border-4 border-body h-20px w-20px">
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                    {{ $data['fullname'] }}
                                </a>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="#"
                                    class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-duotone ki-profile-circle fs-4 me-1"><span
                                            class="path1"></span><span
                                            class="path2"></span><span
                                            class="path3"></span></i> Employee
                                </a>
                                <a href="#"
                                    class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-duotone ki-geolocation fs-4 me-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span></i>
                                        {{ $data['dept_code'] }} ({{ $data['position'] }})
                                </a>
                                <a href="#"
                                    class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <i class="ki-duotone ki-sms fs-4"><span
                                            class="path1"></span><span
                                            class="path2"></span></i>  {{ $data['c_email'] }}
                                </a>
                            </div>
                        </div>
                        {{-- <div class="d-flex my-4">
                            <div class="me-0">
                                <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                    data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <div
                                            class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                            Payments
                                        </div>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">
                                            Create Invoice
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="d-flex flex-wrap flex-stack">
                        <div class="d-flex flex-column flex-grow-1 pe-8">
                            <div class="d-flex flex-wrap">
                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-2 fw-bold">{{ $data['date_employed'] }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Date Hired
                                    </div>
                                </div>
                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-2 fw-bold">{{ $data['tenure'] }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Tenure
                                    </div>
                                </div>
                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-3 fw-bold" >{{ $data['employment_type'] }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Employment
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 main-tab" data-tab="personal_data"
                        href="javascript:;">
                        Personal Data
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 main-tab" data-tab="employment_details"
                        href="javascript:;">
                        Employment Details
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 main-tab" data-tab="account_security"
                        href="javascript:;">
                        Account Security
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">

    </div>
</div>

