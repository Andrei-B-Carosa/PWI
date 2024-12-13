<div class="page-employee-masterlist-settings">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
                {{-- PUT CONTENT HERE --}}

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
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-info btn-flex me-2 view-archive">
                                    <i class="ki-duotone ki-save-2 fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Archive Record
                                </button>
                                <a href="{{ route('admin.register_employee') }}" target="_blank" type="button" class="btn btn-primary btn-flex">
                                    <i class="ki-duotone ki-plus fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Add New Employee
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <x-table
                            id="employee_masterlist"
                            class="table-striped table-sm align-middle table-row-dashed dataTable">
                        </x-table>
                    </div>
                </div>

                {{-- PUT CONTENT HERE --}}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_archive_employee" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="">
                    <h3 class="text-capitalize">User List</h3>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control form-control-solid ps-15 search form-sm" placeholder="Search here . . ." />
                    </div>
                    <x-table id="employee_archive_masterlist" class="table-striped table-sm align-middle table-row-dashed dataTable">
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>
