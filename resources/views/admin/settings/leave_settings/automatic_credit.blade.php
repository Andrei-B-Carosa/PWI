<div class="page-automatic-credit-settings">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
                {{-- PUT CONTENT HERE --}}

                <div class="d-flex align-items-center gap-2 gap-lg-3 mb-3">
                    <a href="/hris/admin/leave_settings" class="btn btn-sm fw-bold btn-danger">
                        <i class="ki-duotone ki-black-left fs-2"></i>
                        Exit Leave Setup
                    </a>
                </div>

                <div class="card card-flush h-lg-100 mb-5">
                    <form action="/hris/admin/settings/leave_settings/automatic_credit/info" id="form_leave_setup" card-class=".card_leave_condition">
                        <div class="card-header pt-5 border-0">
                            <div class="card-title">
                                <h2> Setup {{ $data['leave_type'] }}</h2>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                    <button type="button" class="btn btn-primary submit">Setup Leave</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-8">
                                <div class="col-xxl-12 fv-row">
                                        <label class="fs-6 fw-bold form-label mt-3">Type of Leave</label>
                                        <input type="text" class="form-control" name="type_of_leave" value="{{ $data['leave_type'] }}" disabled/>
                                </div>
                                <div class="col-xxl-12">
                                    <div class="row g-8">
                                        <div class="col-lg-4 fv-row">
                                            <label class="fs-6 form-label fw-bold text-dark">
                                                <span class="required">
                                                    Classification
                                                </span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Employee classification for the leave condition to be applied">
                                                    <i class="ki-duotone ki-information fs-7">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </span>
                                            </label>
                                            <select class="form-select multi-select form-select-solid" name="classification_id[]"
                                                data-control="select2"
                                                data-placeholder="Select an Option"
                                                data-minimum-results-for-search="Infinity"
                                                multiple="multiple"
                                                data-hide-search="true">
                                                <option></option>
                                                {!! $data['classification'] !!}
                                            </select>
                                        </div>
                                        <div class="col-lg-4 fv-row">
                                            <label class="fs-6 form-label fw-bold text-dark">
                                                <span class="required">
                                                    Employment
                                                </span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Employment type for the leave condition to be applied">
                                                    <i class="ki-duotone ki-information fs-7">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </span>
                                            </label>
                                            <select class="form-select multi-select form-select-solid" name="employment_id[]"
                                                data-control="select2"
                                                data-placeholder="Select an Option"
                                                data-minimum-results-for-search="Infinity"
                                                multiple="multiple"
                                                data-hide-search="true">
                                                <option></option>
                                                {!! $data['employment'] !!}
                                            </select>
                                        </div>
                                        <div class="col-lg-4 fv-row">
                                            <label class="fs-6 form-label fw-bold text-dark">
                                                <span class="required">
                                                    Location
                                                </span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Employee location for the leave condition to be applied">
                                                    <i class="ki-duotone ki-information fs-7">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </span>
                                            </label>
                                            <select class="form-select multi-select form-select-solid" name="location_id[]"
                                                data-control="select2"
                                                data-placeholder="Select an Option"
                                                multiple="multiple"
                                                data-hide-search="true">
                                                <option></option>
                                                {!! $data['location'] !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card card-flush h-lg-100 mb-5 card_leave_condition">
                    <div class="card-header pt-7" id="kt_chat_contacts_header">
                        <div class="card-title">
                            <h2>Leave Condition</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <button type="button" class="btn btn-danger me-3 cancel d-none">
                                    Cancel
                                </button>
                                <button type="submit"  class="btn btn-primary me-3 edit d-none" disabled>
                                    <span class="indicator-label"> Edit Condition </span>
                                </button>
                                <button type="submit"  class="btn btn-success submit d-none">
                                    <span class="indicator-label"> Save Condition </span>
                                    <span class="indicator-progress">
                                        Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5">
                        <form id="form_leave_condition" class="form" card-class=".card_leave_condition" action="/hris/admin/settings/leave_settings/automatic_credit/update">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-3">
                                    <span class="required fw-bold">Start Credit</span>
                                    <span class="ms-1" data-bs-toggle="tooltip" title="This is a starting leave credit to be distributed to valid employee">
                                        <i class="ki-duotone ki-information fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </label>
                                <input type="text" class="form-control" name="start_credit" value="" disabled/>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fs-6 form-label fw-bold text-dark">
                                    <span class="required">
                                        Effectivity
                                    </span>
                                    <span class="ms-1" data-bs-toggle="tooltip" title="When to distributed the starting credit ?">
                                        <i class="ki-duotone ki-information fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </label>
                                <select class="form-select form-select-solid" name="fiscal_year" data-control="select2"data-placeholder="Select an Option"
                                    data-minimum-results-for-search="Infinity" disabled>
                                    <option></option>
                                    {!! $data['effectivity'] !!}
                                </select>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fs-6 form-label fw-bold text-dark">
                                    <span class="required">
                                    Is Carry Over ?
                                    </span>
                                    <span class="ms-1" data-bs-toggle="tooltip" title="Is the unused leave credit to be resetted or carry over next year ?">
                                        <i class="ki-duotone ki-information fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </label>
                                <select class="form-select form-select-solid" name="is_carry_over" data-control="select2"
                                        data-placeholder="Select an Option" data-minimum-results-for-search="Infinity" disabled>
                                    <option></option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                            <div class="row d-none carry_over">
                                <div class="col-6">
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 form-label required fw-bold text-dark">Month of Carry Over ?</label>
                                        <select class="form-select form-select-solid" name="carry_over_month" data-control="select2"
                                                data-placeholder="Select an Option" data-minimum-results-for-search="Infinity" disabled>
                                            <option></option>
                                            {!! $data['month'] !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Day of Carry Over</label>
                                        <select class="form-select form-select-solid" name="carry_over_day" data-control="select2"
                                                data-placeholder="Select an Option" data-minimum-results-for-search="Infinity" disabled>
                                            <option></option>
                                            {!! $data['day'] !!}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="separator mb-6"></div>
                            <div class="fv-row mb-7">
                                <label class="fs-6 form-label fw-bold text-dark required">Reset Leave Credit ?</label>
                                <select class="form-select form-select-solid" name="is_reset" data-control="select2"
                                        data-placeholder="Select an Option" data-allow-clear="true"  data-minimum-results-for-search="Infinity" disabled>
                                    <option></option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                            </div> --}}
                            <div class="row d-none reset_leave_credit">
                                <div class="col-6">
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 form-label fw-bold required text-dark">Month of Reset ?</label>
                                        <select class="form-select form-select-solid" name="reset_month" data-control="select2"
                                                data-placeholder="Select an Option" data-minimum-results-for-search="Infinity" disabled>
                                            <option></option>
                                            {!! $data['month'] !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Day of Reset</label>
                                        <select class="form-select form-select-solid" name="reset_day" data-control="select2"
                                                data-placeholder="Select an Option" data-minimum-results-for-search="Infinity" disabled>
                                            <option></option>
                                            {!! $data['day'] !!}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="separator mb-6"></div>
                            <div class="fv-row mb-7">
                                <label class="fs-6 form-label fw-bold text-dark">
                                    <span class="required">
                                        Succeeding Years
                                    </span>
                                    <span class="ms-1" data-bs-toggle="tooltip" title="Does the total leave credit increase or fixed on succeding years?">
                                        <i class="ki-duotone ki-information fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </label>
                                <select class="form-select form-select-solid" name="succeeding_year" data-control="select2"
                                        data-placeholder="Select an Option" data-allow-clear="true"  data-minimum-results-for-search="Infinity" disabled>
                                    <option></option>
                                    <option value="1">Increment</option>
                                    <option value="2">Fixed</option>
                                </select>
                            </div>
                            <div class="fv-row mb-7 d-none succeeding_year">
                                <div id="repeater">
                                    <div class="form-group row g-1 justify-content-center">
                                        <div class="col-9">
                                            <label class="fs-6 form-label fw-bold text-dark">
                                                <span class="required">
                                                    Increment Milestones (Year - Credits)
                                                </span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Years required for tenure and credits distributed upon achievement">
                                                    <i class="ki-duotone ki-information fs-7">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-3 text-center">
                                            <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary float-end">
                                                <i class="ki-duotone ki-plus fs-3"></i>
                                                Add Field
                                            </a>
                                        </div>
                                    </div>
                                    <div data-repeater-list="milestones">
                                        <div data-repeater-item>
                                            <div class="form-group row">
                                                <div class="col-md-8 fv-row mt-4 mb-3">
                                                    <input type="number" name="year" class="form-control form-control-sm me-2 milestone-year" placeholder="Year" min="1">
                                                </div>
                                                <div class="col-md-4 fv-row mt-4 mb-3">
                                                    <input type="number" name="credit" class="form-control form-control-sm milestone-credit" placeholder="Credit" min="0">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <a href="javascript:;" data-repeater-delete class="btn  btn-sm btn-light-danger float-end">
                                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- PUT CONTENT HERE --}}
            </div>
        </div>
    </div>
</div>

