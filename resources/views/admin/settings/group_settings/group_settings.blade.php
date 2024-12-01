<div class="page-approver-settings">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">


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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_group">
                                    Add New Group
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <x-table id="group" class="table-striped table-sm align-middle table-row-dashed dataTable">
                        </x-table>
                    </div>
                </div>

            </div>
        </div>
    </div>

  {{--
<x-modal
    id="add_approver"
    title="Approver Details"
    action="/hris/admin/settings/approver_settings/create" >
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
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
        </div>
        <div class="fv-row mb-8 fv-plugins-icon-container">
            <x-select
                id="company_id"
                name="company_id"
                label="Company"
                :options="[]"
                placeholder="Select an option"
                selected=""
                class="fw-bold form-select-solid"
                data-control="select2"
                data-placeholder="Select an option"
                data-minimum-results-for-search="Infinity"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="fv-row mb-8 fv-plugins-icon-container">
            <x-select
                id="company_location_id"
                name="company_location_id"
                label="Location"
                :options="[]"
                placeholder="Select an option"
                selected=""
                class="fw-bold form-select-solid"
                data-control="select2"
                data-placeholder="Select an option"
                data-minimum-results-for-search="Infinity"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="fv-row mb-8 fv-plugins-icon-container">
            <x-select
                id="department_id"
                name="department_id"
                label="Department"
                :options="[]"
                placeholder="Select an option"
                selected=""
                class="fw-bold form-select-solid"
                data-control="select2"
                data-placeholder="Select an option"
                data-minimum-results-for-search="Infinity"
                data-allow-clear="true"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
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
                <input class="form-check-input" type="checkbox" value="1" id="is_final_approver" name="is_final_approver">
                <label class="form-check-label" for="is_final_approver">
                    No/Yes
                </label>
            </div>
        </div>
        <div class="fv-row mb-8 pb-5">
            <label class="form-label">Is Approval Required ?</label>
            <div class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="1" id="is_required" name="is_required">
                <label class="form-check-label" for="is_required">
                    No/Yes
                </label>
            </div>
        </div>
    </div>
</x-modal>--}}



<x-modal
    id="add_group"
    title="Group Details"
    action="/hris/admin/settings/group_settings/update">
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-input
                name="name"
                id=""
                label="Group Name"
                value=""
                placeholder="Group Name"
                class="form-control form-control-solid"
                disabled="false"
                remote-validation="true"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
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
        <div class="d-flex  fv-row flex-column mb-7">
            <x-textarea
                id="description"
                name="description"
                label="Description"
                class="form-control-solid"
                data-required="false"
            >
            </x-textarea>
        </div>
    </div>
</x-modal>
</div>
