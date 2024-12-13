<div class="d-flex flex-wrap align-items-center">
    <!--begin::Label-->
    <div id="">
        <div class="fs-6 fw-bold mb-1">Username</div>
        <div class="fw-semibold text-gray-600">{{ $emp_account->username }}</div>
    </div>
</div>
<div class="separator separator-dashed my-6"></div>
<!--begin::Email Address-->
<div class="d-flex flex-wrap align-items-center">
    <!--begin::Label-->
    <div id="kt_signin_email">
        <div class="fs-6 fw-bold mb-1">Email Address</div>
        <div class="fw-semibold text-gray-600">{{ $emp_account->c_email }}</div>
    </div>
    <!--end::Label-->

    <!--begin::Edit-->
    <div id="kt_signin_email_edit" class="flex-row-fluid d-none">
        <!--begin::Form-->
        <form id="kt_signin_change_email" class="form" action="" novalidate="novalidate">
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="fv-row mb-0">
                        <label for="emailaddress" class="form-label fs-6 fw-bold mb-3">Enter New Email
                            Address</label>
                        <input type="email" class="form-control form-control-lg form-control-solid" id="emailaddress"
                            placeholder="Email Address" name="emailaddress" value="{{ $emp_account->c_email }}" />
                    </div>
                </div>
                @if($isSystemAdmin == false)
                    <div class="col-lg-6">
                        <div class="fv-row mb-0">
                            <label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3">Confirm
                                Password</label>
                            <input type="password" class="form-control form-control-lg form-control-solid"
                                name="confirmemailpassword" id="confirmemailpassword" />
                        </div>
                    </div>
                @endif
            </div>
            <div class="d-flex">
                <button id="kt_signin_submit" type="button" class="btn btn-primary me-2 px-6 submit">Update
                    Email</button>
                <button id="kt_signin_cancel" type="button"
                    class="btn btn-color-gray-400 btn-active-light-primary px-6 cancel">Cancel</button>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Edit-->

    <!--begin::Action-->
    <div id="kt_signin_email_button" class="ms-auto">
        <button class="btn btn-light btn-active-light-primary">Change Email</button>
    </div>
    <!--end::Action-->
</div>
<!--end::Email Address-->

<!--begin::Separator-->
<div class="separator separator-dashed my-6"></div>
<!--end::Separator-->

<!--begin::Password-->
<div class="d-flex flex-wrap align-items-center mb-10">
    <!--begin::Label-->
    <div id="kt_signin_password">
        <div class="fs-6 fw-bold mb-1">Password</div>
        <div class="fw-semibold text-gray-600">************</div>
    </div>
    <!--end::Label-->

    <!--begin::Edit-->
    <div id="kt_signin_password_edit" class="flex-row-fluid d-none">
        <!--begin::Form-->
        <form id="kt_signin_change_password" class="form" action="" novalidate="novalidate">
            <div class="row mb-1">
                @if($isSystemAdmin == false)
                    <div class="col-lg-4">
                        <div class="fv-row mb-0">
                            <label for="currentpassword" class="form-label fs-6 fw-bold mb-3">Current
                                Password</label>
                            <input type="password" class="form-control form-control-lg form-control-solid "
                                name="currentpassword" id="currentpassword" />
                        </div>
                    </div>
                @endif

                <div class="col-lg-4">
                    <div class="fv-row mb-0">
                        <label for="newpassword" class="form-label fs-6 fw-bold mb-3">New Password</label>
                        <input type="password" class="form-control form-control-lg form-control-solid "
                            name="newpassword" id="newpassword" />
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="fv-row mb-0">
                        <label for="confirmpassword" class="form-label fs-6 fw-bold mb-3">Confirm New
                            Password</label>
                        <input type="password" class="form-control form-control-lg form-control-solid "
                            name="confirmpassword" id="confirmpassword" />
                    </div>
                </div>
            </div>

            <div class="form-text mb-5">Password must be at least 8 character and contain symbols</div>

            <div class="d-flex">
                <button id="kt_password_submit" type="button" class="btn btn-primary me-2 px-6 submit">Update
                    Password</button>
                <button id="kt_password_cancel" type="button"
                    class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Edit-->

    <!--begin::Action-->
    <div id="kt_signin_password_button" class="ms-auto">
        <button class="btn btn-light btn-active-light-primary">Reset Password</button>
    </div>
    <!--end::Action-->
</div>
<!--end::Password-->

<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed  p-6">
    <i class="ki-duotone ki-shield-tick fs-2tx text-primary me-4"><span class="path1"></span><span
            class="path2"></span></i>
    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
        <div class="mb-3 mb-md-0 fw-semibold">
            <h4 class="text-gray-900 fw-bold">Secure Your Account</h4>

            <div class="fs-6 text-gray-700 pe-7"> Use a mix of uppercase letters, numbers, and special characters to enhance your account security.</div>
        </div>
    </div>
</div>
