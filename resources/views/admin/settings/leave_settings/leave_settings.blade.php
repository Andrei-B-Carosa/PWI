<div class="page-leave-settings">
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
                                            data-tab="leave-type" data-bs-toggle="tab" href="#tab_content1">
                                            Leave Type
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="leave-management" data-bs-toggle="tab" href="#tab_content2">
                                            Leave Management
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex-lg-row-fluid ms-3">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade leave-type" id="tab_content1" role="tabpanel">
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
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_leave_type">
                                                    New Type of Leave
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="leave_type_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="leave_type_table">
                                                    </table>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade leave-management" id="tab_content2" role="tabpanel">
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
                                                {{-- <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    <i class="ki-duotone ki-filter fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    Filter
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                                                    <div class="px-7 py-5">
                                                        <div class="fs-4 text-dark fw-bold">Filter Options</div>
                                                    </div>
                                                    <div class="separator border-gray-200"></div>
                                                    <div class="px-7 py-5">
                                                        <div class="mb-10">
                                                            <label class="form-label fs-5 fw-semibold mb-3">Year:</label>
                                                            <select class="form-select form-select-solid fw-bold" name="filter_year" data-kt-select2="true" data-minimum-results-for-search="Infinity" data-placeholder="Select option" data-allow-clear="true">
                                                                <option></option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-10">
                                                            <label class="form-label fs-5 fw-semibold mb-3">Month:</label>
                                                            <select class="form-select form-select-solid fw-bold" name="filter_month" data-kt-select2="true" data-minimum-results-for-search="Infinity"  data-placeholder="Select option" data-allow-clear="true">
                                                                <option></option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-10">
                                                            <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                                                            <div class="d-flex flex-column flex-wrap fw-semibold">
                                                                <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                                    <input class="form-check-input" type="radio" name="filter_status" value="all" checked="checked" />
                                                                    <span class="form-check-label text-gray-600">
                                                                        View All
                                                                    </span>
                                                                </label>
                                                                <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                                    <input class="form-check-input" type="radio" name="filter_status" value="approved" />
                                                                    <span class="form-check-label text-gray-600">
                                                                        Approved
                                                                    </span>
                                                                </label>
                                                                <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                                    <input class="form-check-input" type="radio" name="filter_status" value="disapproved" />
                                                                    <span class="form-check-label text-gray-600">
                                                                        Disapproved
                                                                    </span>
                                                                </label>
                                                                <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                                    <input class="form-check-input" type="radio" name="filter_status" value="pending" />
                                                                    <span class="form-check-label text-gray-600">
                                                                        Pending
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex justify-content-end">
                                                            <button type="button" class="btn btn-light btn-active-light-primary me-2 reset" data-kt-menu-dismiss="true" >Reset</button>

                                                            <button type="button" class="btn btn-primary filter" data-kt-menu-dismiss="true">Apply</button>
                                                        </div>
                                                        <!--end::Actions-->
                                                    </div>
                                                    <!--end::Content-->
                                                </div> --}}
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_leave_management">
                                                    Add Leave
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <div class="d-flex flex-column flex-xl-row p-7">
                                            <div class="flex-lg-row-fluid">
                                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div id="leave_management_wrapper">
                                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                            id="leave_management_table">
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

    <div class="modal fade" id="modal_add_leave_type" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                    <div class="text-center">
                        <h1 class="mb-3 modal_title">New Type of Leave</h1>
                        <div class="text-muted fs-5">Fill-up the form and click
                            <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-body px-5">
                    <form id="form_add_leave_type" modal-id="#modal_add_leave_type" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/hris/admin/settings/leave_settings/leave_type/update">
                        <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="fv-row mb-7 fv-plugins-icon-container">
                                        <label class="required fw-semibold fs-6 mb-2">Type of Leave</label>
                                        <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Type of Leave">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fv-row mb-7 fv-plugins-icon-container">
                                        <label class="required fw-semibold fs-6 mb-2">Code</label>
                                        <input type="text" name="code" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Code">
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mb-8 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Company:</label>
                                <select name="company_id" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity"
                                        class="form-select form-select-solid fw-bold">
                                    <option></option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-8 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Gender Type:</label>
                                <select name="gender_type" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity"
                                        class="form-select form-select-solid fw-bold">
                                    <option></option>
                                    <option value="3" selected>All</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-8 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                                <select name="is_active" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity"
                                        class="form-select form-select-solid fw-bold">
                                    <option></option>
                                    <option value="1" selected>Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="d-flex  fv-row flex-column mb-8">
                                <label class="fs-6 fw-semibold mb-2">Description</label>
                                <textarea class="form-control form-control-solid" rows="5" name="description" placeholder="Description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" modal-id="#modal_add_leave_type" data-id="" id="" class="btn btn-primary me-4 submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" modal-id="#modal_add_leave_type" id="" class="btn btn-light me-3 cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_add_leave_management" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                    <div class="text-center">
                        <h1 class="mb-3 modal_title">Manage Leave</h1>
                        <div class="text-muted fs-5">Fill-up the form and click
                            <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-body px-5">
                    <form id="form_add_leave_management" modal-id="#modal_add_leave_management" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/hris/admin/settings/leave_settings/leave_management/update">
                        <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                            <div class="fv-row mb-8 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Leave Type:</label>
                                <select name="leave_type_id" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity"
                                        class="form-select form-select-solid fw-bold">
                                    <option></option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-8 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Credit Type:</label>
                                <select name="credit_type" data-control="select2" data-allow-clear="true"  data-placeholder="Select an option"
                                data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold">
                                    <option></option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-8 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                                <select name="status" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity"
                                        class="form-select form-select-solid fw-bold">
                                    <option></option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" modal-id="#modal_add_leave_management" data-id="" id="" class="btn btn-primary me-4 submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" modal-id="#modal_add_leave_management" id="" class="btn btn-light me-3 cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
