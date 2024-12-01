<div class="page-employee-masterlist-settings">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
                {{-- PUT CONTENT HERE --}}

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
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-info btn-flex me-2">
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
</div>
