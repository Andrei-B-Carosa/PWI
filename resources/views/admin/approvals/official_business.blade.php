<div class="page-overtime-requisition">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
                {{-- PUT CONTENT HERE --}}

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
                            </div>
                                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_request_overtime">
                                    Request Overtime
                                </button> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div class="d-flex flex-column flex-xl-row p-7">
                            <div class="flex-lg-row-fluid">
                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div id="official_business_wrapper">
                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                            id="official_business_table">
                                    </table>
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
</div>

<div id="kt_activities_request" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities"
    data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'0px', 'lg': '800px'}"
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
