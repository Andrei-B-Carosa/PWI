<x-modal
    id="add_department"
    title="Department Details"
    action="/hris/admin/settings/file_maintenance/department/update" >
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="row">
            <div class="col-8">
                <div class="fv-row mb-7 fv-plugins-icon-container">
                    <x-input
                        name="name"
                        id=""
                        label="Department"
                        value=""
                        placeholder="Department"
                        class="form-control form-control-solid mb-3 mb-lg-0"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
            <div class="col-4">
                <div class="fv-row mb-7 fv-plugins-icon-container">
                        <x-input
                        name="code"
                        id=""
                        label="Code"
                        value=""
                        placeholder="Code"
                        class="form-control form-control-solid mb-3 mb-lg-0"
                        disabled="false"
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
        <div class="d-flex  fv-row flex-column mb-8">
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

<x-modal
    id="add_position"
    title="Position Details"
    action="/hris/admin/settings/file_maintenance/position/update" >
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-input
                name="name"
                id=""
                label="Position"
                value=""
                placeholder="Position"
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

<x-modal
    id="add_classification"
    title="Classification Details"
    action="/hris/admin/settings/file_maintenance/classification/update">
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-input
                name="name"
                id=""
                label="Classification"
                value=""
                placeholder="Classification"
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

<x-modal
    id="add_employment_type"
    title="Employment Details"
    action="/hris/admin/settings/file_maintenance/employment_type/update" >
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-input
                name="name"
                id=""
                label="Employment Type"
                value=""
                placeholder="employment_type"
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
    </div>
</x-modal>

<x-modal
    id="add_section"
    title="section"
  Details   action="/hris/admin/settings/file_maintenance/section/update" >
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="row">
            <div class="col-8">
                <div class="fv-row mb-7 fv-plugins-icon-container">
                    <x-input
                        name="name"
                        id=""
                        label="Section"
                        value=""
                        placeholder="Section"
                        class="form-control form-control-solid mb-3 mb-lg-0"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
            <div class="col-4">
                <div class="fv-row mb-7 fv-plugins-icon-container">
                        <x-input
                        name="code"
                        id=""
                        label="Code"
                        value=""
                        placeholder="Code"
                        class="form-control form-control-solid mb-3 mb-lg-0"
                        disabled="false"
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
        <div class="d-flex  fv-row flex-column mb-8">
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

<x-modal
    id="add_company"
    title="Company Details"
    action="/hris/admin/settings/file_maintenance/company/update" >
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-input
                name="name"
                id=""
                label="Company"
                value=""
                placeholder="Company"
                class="form-control form-control-solid mb-3 mb-lg-0"
                disabled="false"
                remote-validation="true"
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
        <div class="d-flex  fv-row flex-column mb-8">
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

<x-modal
    id="add_company_location"
    title="Company Location Details"
    action="/hris/admin/settings/file_maintenance/company_location/update" >
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
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
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-input
                name="name"
                id=""
                label="Company Location"
                value=""
                placeholder="Company Location"
                class="form-control form-control-solid mb-3 mb-lg-0"
                disabled="false"
                remote-validation="true"
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
        <div class="d-flex  fv-row flex-column mb-8">
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
