<div class="page-file-maintenance-settings">

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
                {{-- PUT CONTENT HERE --}}

                <div class="d-flex flex-column flex-lg-row">
                    <div class="d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px">
                        <div class="card card-flush mb-0">
                            <div class="card-body pt-5">
                                <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
                                    <li>
                                        <div class="menu-item">
                                            <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">
                                                Menu Options
                                            </h4>
                                        </div>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="department" data-bs-toggle="tab" href="#tab_content1">
                                            Department
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="position" data-bs-toggle="tab" href="#tab_content2">
                                            Position
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="classification" data-bs-toggle="tab" href="#tab_content3">
                                            Classification
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="employment_type" data-bs-toggle="tab" href="#tab_content4">
                                            Employment Type
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="section" data-bs-toggle="tab" href="#tab_content5">
                                            Section
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="company" data-bs-toggle="tab" href="#tab_content6">
                                            Company
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="company_location" data-bs-toggle="tab" href="#tab_content7">
                                            Company Location
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex-lg-row-fluid ms-3">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade department" id="tab_content1" role="tabpanel">
                                <div class="card mb-5 mb-xl-8">
                                    <div class="card-header border-0 pb-6 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                            </div>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_department">
                                                    Add Department
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="department_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="department_table">
                                                    </table>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade position" id="tab_content2" role="tabpanel">
                                <div class="card mb-5 mb-xl-8">
                                    <div class="card-header border-0 pb-6 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                            </div>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_position">
                                                    Add Position
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="position_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="position_table">
                                                    </table>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade classification" id="tab_content3" role="tabpanel">
                                <div class="card mb-5 mb-xl-8">
                                    <div class="card-header border-0 pb-6 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                            </div>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_classification">
                                                    Add Classification
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="classification_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="classification_table">
                                                    </table>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade employment_type" id="tab_content4" role="tabpanel">
                                <div class="card mb-5 mb-xl-8">
                                    <div class="card-header border-0 pb-6 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                            </div>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_employment_type">
                                                    Add Employment Type
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="employment_type_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="employment_type_table">
                                                    </table>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade section" id="tab_content5" role="tabpanel">
                                <div class="card mb-5 mb-xl-8">
                                    <div class="card-header border-0 pb-6 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                            </div>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_section">
                                                    Add Section
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="section_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="section_table">
                                                    </table>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade company" id="tab_content6" role="tabpanel">
                                <div class="card mb-5 mb-xl-8">
                                    <div class="card-header border-0 pb-6 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                            </div>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_company">
                                                    Add Company
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="company_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="company_table">
                                                    </table>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade company_location" id="tab_content7" role="tabpanel">
                                <div class="card mb-5 mb-xl-8">
                                    <div class="card-header border-0 pb-6 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                            </div>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_company_location">
                                                    Add Company Location
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="company_location_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="company_location_table">
                                                    </table>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PUT CONTENT HERE --}}
            </div>
        </div>
    </div>
    @include('admin.settings.file_maintenance.form_file_maintenance')
</div>
