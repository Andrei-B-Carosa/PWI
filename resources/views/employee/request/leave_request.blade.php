<div class="page-leave-request">
    <div class="row">
        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-primary pb-1 px-2">Pending Request</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span data-kt-countup="true" id="kt_countup_1" data-kt-countup-value="0" class="counted pending" data-kt-initialized="1"></span>
                </span>
            </div>
        </div>
        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-success pb-1 px-2">Approved Leave</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span data-kt-countup="true" id="kt_countup_2" data-kt-countup-value="0" class="counted approved" data-kt-initialized="1"></span>
                </span>
            </div>
        </div>
        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-danger pb-1 px-2">Rejected Leave</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span data-kt-countup="true" id="kt_countup_3" data-kt-countup-value="0" class="counted rejected" data-kt-initialized="1"></span>
                </span>
            </div>
        </div>
        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-info pb-1 px-2">Total Request</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span data-kt-countup="true" id="kt_countup_4" data-kt-countup-value="0" class="counted total-rq" data-kt-initialized="1"></span>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
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
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
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
                        <div class="mb-7">
                            <label class="form-label fs-5 fw-semibold mb-3">Year:</label>
                            <select class="form-select form-select-solid fw-bold" name="filter_year" data-kt-select2="true" data-minimum-results-for-search="Infinity" data-placeholder="Select option" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>
                        <div class="mb-7">
                            <label class="form-label fs-5 fw-semibold mb-3">Month:</label>
                            <select class="form-select form-select-solid fw-bold" name="filter_month" data-kt-select2="true" data-minimum-results-for-search="Infinity"  data-placeholder="Select option" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>
                        <div class="mb-7">
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
                            {{-- <button type="button" class="btn btn-light btn-active-light-primary me-2 reset" data-kt-menu-dismiss="true" >Reset</button> --}}

                            <button type="button" class="btn btn-primary filter" data-kt-menu-dismiss="true">Apply</button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Content-->
                </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_request_leave">
                        Request a Leave
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body py-0">
            <div class="d-flex flex-column flex-xl-row p-5">
                <div class="flex-lg-row-fluid">
                    <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div id="leave_request_wrapper">
                            <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                    id="leave_request_table">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_request_leave" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                    <div class="text-center">
                        <h1 class="mb-3 modal_title">Application for Leave</h1>
                        <div class="text-muted fs-5">Fill-up the form and click
                            <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-body px-5">
                    <form id="form_request_overtime" modal-id="#modal_request_leave" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/hris/employee/request/leave/update">
                        <div class="px-5 px-lg-10">
                            <div class="row">
                                <div class="fv-row mb-7 col-12 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">Type of leave</label>
                                    <select name="leave_type_id" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity" class="form-select modal-select form-select-solid fw-bold">
                                        <option></option>
                                    </select>
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">Date of filing</label>
                                    <input type="text" name="leave_filing_date" input-control="date-picker" default-date="current" class="form-control form-control-solid mb-3 mb-lg-0 flatpickr">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                           <div class="row">
                                <div class="d-flex flex-column col-6 fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">From</label>
                                    <input type="text" name="leave_date_from" input-control="date-picker" default-format="24" default-date="current" class="form-control form-control-solid mb-3 mb-lg-0">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="d-flex flex-column col-6 fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">To</label>
                                    <input type="text" name="leave_date_to" input-control="date-picker" default-format="24"  default-date="current" class="form-control form-control-solid mb-3 mb-lg-0">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                           </div>
                           <div class="fv-row mb-7 col-12 fv-plugins-icon-container">
                                <label class="required fw-semibold fs-6 mb-2">Is Excused ?</label>
                                <select name="is_excused" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity" class="form-select modal-select form-select-solid fw-bold">
                                    <option></option>
                                    <option class="Yes">Yes</option>
                                    <option class="No">No</option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="d-flex fv-row flex-column mb-7" id="kt_modal_add_user_scroll">
                                <label class="fs-6 required fw-semibold mb-2">Reason for filing </label>
                                <textarea class="form-control form-control-solid" rows="5" name="reason" placeholder="Reason for filing"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" modal-id="#modal_request_leave" data-id="" class="btn btn-primary me-4 submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" modal-id="#modal_request_leave" class="btn btn-light me-3 cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_activities_request" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities"
        data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '800px'}"
        data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_activities_request_toggle"
        data-kt-drawer-close="#kt_activities_request_close">

        <div class="card shadow-none border-0 rounded-0">
            <div class="card-header" id="kt_activities_request_header">
                <h3 class="card-title fw-bold text-dark">Approval History <span class="request"></span></h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5"
                        id="kt_activities_request_close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
            </div>

            <div class="card-body position-relative" id="kt_activities_request_body">
                <div id="kt_activities_request_scroll" class="position-relative" data-kt-scroll="true"
                    data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_request_body"
                    data-kt-scroll-dependencies="#kt_activities_request_header, #kt_activities_request_footer" data-kt-scroll-offset="5px">
                    <div class="timeline timeline-border-dashed approval-timeline">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

