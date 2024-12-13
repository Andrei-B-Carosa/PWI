<div class="page-permissions">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
                <div class="card mb-5 mb-xl-8 card-employee-masterlist">
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
                        {{-- <div class="card-toolbar">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-flex me-2 new">
                                    New Permission
                                </button>
                            </div>
                        </div> --}}
                    </div>
                    <div class="card-body py-0">
                        <x-table
                            id="permissions"
                            class="table-striped table-sm align-middle table-row-dashed dataTable">
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
