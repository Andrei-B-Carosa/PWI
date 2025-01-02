<div class="page-employee-profile">
    <div class="row g-7">
        <div class="d-flex flex-column flex-lg-row">
            <div class="d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px mb-7 me-3">
                <div class="card card-flush mb-0">
                    <div class="card-body pb-5">
                        <ul class="nav nav-tabs nav-pills d-inline-block border-0 me-5 mb-3 mb-md-0 fs-6">
                            <li>
                                <div class="menu-item">
                                    <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">
                                        Menu Options
                                    </h2>
                                </div>
                            </li>
                            <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold profile-tab" tab-loaded="false"
                                    data-tab="1" data-bs-toggle="tab" data-tab-title="Personal Information" href="#tab_content1">
                                    Personal Information
                                </a>
                            </li>
                            <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold profile-tab" tab-loaded="true"
                                    data-tab="2" data-bs-toggle="tab" data-tab-title="Employment Details" href="#tab_content2">
                                    Employment Details
                                </a>
                            </li>
                            {{-- <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                    data-tab="classification" data-bs-toggle="tab" href="#tab_content3">
                                    Address
                                </a>
                            </li>
                            <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                    data-tab="employment_type" data-bs-toggle="tab" href="#tab_content4">
                                    Family Background
                                </a>
                            </li>
                            <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                    data-tab="section" data-bs-toggle="tab" href="#tab_content5">
                                    Educational Background
                                </a>
                            </li>
                            <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                    data-tab="company" data-bs-toggle="tab" href="#tab_content6">
                                    Work Experience
                                </a>
                            </li>
                            <li class="nav-item w-md-200px me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                    data-tab="company_location" data-bs-toggle="tab" href="#tab_content7">
                                    References
                                </a>
                            </li> --}}
                            <li class="nav-item  me-0 mb-2">
                                <a class="nav-link text-dark text-active-light fw-bold profile-tab" tab-loaded="false"
                                    data-tab="6" data-tab-title="Document Attachments" data-bs-toggle="tab" href="#tab_content6">
                                    Document Attachments
                                </a>
                            </li>
                            <li class="nav-item  me-0 mb-2">
                                <a class="nav-link text-danger text-active-light fw-bold profile-tab" tab-loaded="false"
                                    data-tab="8" data-bs-toggle="tab" data-tab-title="Account Security" href="#tab_content8">
                                    Account Security
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid mb-7">
                <div class="flex-lg-row-fluid">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade tab1" id="tab_content1" role="tabpanel">
                            <div class="card card-flush h-lg-100" id="card1">
                                <div class="card-header py-7">
                                    <div class="card-title">
                                        <h2 class="tab_title"></h2>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-3 cancel d-none">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary edit d-none">
                                            <span class="indicator-label">
                                                Edit Details
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-success save d-none">
                                            <span class="indicator-label">
                                                Save Details
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                    <form id="form1" card-id="#card1" action="/hris/admin/201_employee/employee_details/update"></form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade tab2" id="tab_content2" role="tabpanel">
                            <div class="card card-flush h-lg-100" id="card2">
                                <div class="card-header py-7">
                                    <div class="card-title">
                                        <h2 class="tab_title"></h2>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-3 cancel d-none">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary edit d-none">
                                            <span class="indicator-label">
                                                Edit Details
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-success save d-none">
                                            <span class="indicator-label">
                                                Save Details
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade tab6" id="tab_content6" role="tabpanel">
                            <div class="card card-flush h-lg-100" id="card6">
                                <div class="card-header py-7">
                                    <div class="card-title">
                                        <h2 class="tab_title"></h2>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary me-3 upload-document d-none" data-bs-toggle="modal" data-bs-target="#modal_upload_document">
                                            Upload Document
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade tab8" id="tab_content8" role="tabpanel">
                            <div class="card card-flush h-lg-100" id="card8">
                                <div class="card-header py-7">
                                    <div class="card-title">
                                        <h2 class="tab_title"></h2>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



