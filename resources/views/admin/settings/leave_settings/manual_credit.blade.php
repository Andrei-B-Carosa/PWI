<div class="page-manual-credit-settings">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
                {{-- PUT CONTENT HERE --}}
                <div class="d-flex align-items-center gap-2 gap-lg-3 mb-3">
                    <a href="/hris/admin/leave_settings" class="btn btn-sm fw-bold btn-danger">
                        <i class="ki-duotone ki-black-left fs-2"></i>
                        Back to Leave Settings
                    </a>
                </div>
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pb-6 pt-6">
                        <div class="card-title flex-column">
                            <h3 class="mb-1">{{ $data['leave_type'] }}</h3>
                            <div class="fs-6 fw-semibold text-muted"></div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center fw-bold">
                                <div class="text-gray-400 fs-7 me-2">Classification</div>
                                <select class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto filter_table"
                                    data-control="select2" name="filter_classification" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                    <option value="all">View All</option>
                                    {!! $data['classification'] !!}
                                </select>
                            </div>
                            <div class="d-flex align-items-center fw-bold">
                                <div class="text-gray-400 fs-7 me-2">Employment</div>
                                <select class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto filter_table"
                                    data-control="select2" name="filter_employment" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                    <option value="all">View All</option>
                                    {!! $data['employment'] !!}
                                </select>
                            </div>
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" class="form-control form-control-sm ps-12 search" placeholder="Search here ..." />
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div class="d-flex flex-column flex-xl-row p-7">
                            <div class="flex-lg-row-fluid">
                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div id="manual_credit_wrapper">
                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-3 dataTable no-footer table-sm"
                                            id="manual_credit_table">
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
