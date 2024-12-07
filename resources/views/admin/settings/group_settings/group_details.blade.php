<div class="page-group-details">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
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
                                            data-tab="approver_list" data-bs-toggle="tab" href="#tab_content1">
                                            Approver List
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0 mb-2">
                                        <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                            data-tab="group_member" data-bs-toggle="tab" href="#tab_content2">
                                            Group Member
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex-lg-row-fluid ms-3">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade approver_list" id="tab_content1" role="tabpanel">
                                <div class="card card-flush mb-6 mb-xl-9">
                                    <div class="card-header pt-5">
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
                                            <button type="button" class="ms-2 btn btn-primary add-approver" data-bs-toggle="modal" data-bs-target="#modal_add_approver">
                                                Add New Approver
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <x-table id="approver_list" class="table-striped table-sm align-middle table-row-dashed dataTable">
                                        </x-table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade group_member" id="tab_content2" role="tabpanel">
                                <div class="card card-flush mb-6 mb-xl-9">
                                    <div class="card-header pt-5">
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
                                            <button type="button" class="ms-2 btn btn-primary add-member">
                                                Add New Member
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <x-table id="group_member" class="table-striped table-sm align-middle table-row-dashed dataTable">
                                        </x-table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>


    <x-modal
        id="add_approver"
        title="Approver Details"
        action="/hris/admin/settings/group_details/approver/update">
        <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
            <div class="row">
                <div class="col-7">
                    <div class="fv-row mb-8 fv-plugins-icon-container">
                        <x-select
                            id="approver_id"
                            name="approver_id"
                            label="Approver"
                            :options="[]"
                            placeholder="Select an option"
                            selected=""
                            class="fw-bold form-select-solid"
                            data-control="select2"
                            data-placeholder="Select an option"
                            data-minimum-results-for-search="Infinity"
                            remote-validation="true"
                            data-validate="check_approver"
                        />
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="fv-row mb-8 fv-plugins-icon-container">
                        <x-select
                            id="approver_level"
                            name="approver_level"
                            label="Approver Level"
                            :options="['1'=>1, '2'=>2, '3'=>3]"
                            placeholder="Select an option"
                            selected=""
                            class="fw-bold form-select-solid"
                            data-control="select2"
                            data-placeholder="Select an option"
                            data-minimum-results-for-search="Infinity"
                            data-allow-clear="true"
                            remote-validation="true"
                            data-validate="check_approver_level"
                        />
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="fv-row mb-8 fv-plugins-icon-container">
                    <x-select
                        id="is_active"
                        name="is_active"
                        label="Status"
                        :options="['1' => 'Active', '2' => 'Inactive']"
                        placeholder="Select an option"
                        selected=""
                        class="fw-bold form-select-solid"
                        data-control="select2"
                        data-placeholder="Select an option"
                        data-minimum-results-for-search="Infinity"
                    />
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <div class="fv-row mb-8">
                <label class="form-label">Is Final Approver ?</label>
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" id="is_final_approver" name="is_final_approver"
                        remote-validation="true"
                        data-validate="check_final_approver" data-required="false">
                    <label class="form-check-label" for="is_final_approver">
                        No/Yes
                    </label>
                </div>
            </div>
            <div class="fv-row mb-8 pb-5">
                <label class="form-label">Is Approval Required ?</label>
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" id="is_required" name="is_required" data-required="false">
                    <label class="form-check-label" for="is_required">
                        No/Yes
                    </label>
                </div>
            </div>
        </div>
    </x-modal>

    <div class="modal fade" id="modal_add_member" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="">
                    <h3 class="text-capitalize">Add New Member</h3>
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
                    <x-table id="employee_list" class="table-striped table-sm align-middle table-row-dashed dataTable">
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>


