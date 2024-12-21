<div class="modal fade" id="ob_request_modal" tabindex="-1" aria-hidden="false" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            @if(empty($data))
                <div id="empty_state_wrapper" >
                    <div class="card-px text-center pt-15 pb-15">
                        <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">No Request Found</h2>
                        <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                            You can <a href="{{ route('employee.form.login') }}">login</a> to view all requets
                        </p>

                    </div>
                    <div class="text-center pb-15 px-5">
                        <img src="{{ asset('assets/media/illustrations/sketchy-1/16.png') }}" alt="" class="mw-100 h-200px h-sm-325px">
                    </div>
                </div>
            @elseif(!$isLinkValid)
                <div id="empty_state_wrapper" >
                    <div class="card-px text-center pt-15 pb-15">
                        <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">Oops!</h2>
                        <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                            Link is Expired
                        </p>
                    </div>
                    <div class="text-center pb-15 px-5">
                        <img src="{{ asset('assets/media/illustrations/sketchy-1/16.png') }}" alt="" class="mw-100 h-200px h-sm-325px">
                    </div>
                </div>
            @else
                <div class="modal-header justify-content-center border-0 pb-0" id="kt_modal_add_user_header">
                    <div class="text-center">
                        <h1 class="mb-3 modal_title text-capitalize">OT Request Approval</h1>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="m-0 row">
                        <div class="d-print-none border border-dashed @if($data['is_approved'] == 1) border-success  @elseif($data['is_approved']==2)border-danger @else border-gray-300 @endif card-rounded h-lg-100 p-9 bg-lighten">
                            <div class="mb-6">
                                @if($data['is_approved'] == null)
                                    <span class="badge badge-secondary me-2">Pending</span>
                                @elseif($data['is_approved']== 1)
                                    <span class="badge badge-light-success me-2">Approved</span>
                                @elseif($data['is_approved'] == 2)
                                    <span class="badge badge-light-warning">Rejected</span>
                                @endif
                            </div>
                            <div class="mb-6">
                                <div class="fw-semibold text-gray-600 fs-5">OT Filing Date: </div>
                                <div class="fw-bold text-gray-800 fs-4">{{ $data['overtime_date'] }}</div>
                            </div>
                            <div class="mb-6">
                                <div class="fw-semibold text-gray-600 fs-5">Requestor:</div>
                                <div class="fw-bold text-gray-800 fs-4">{{ $data['requestor'] }}</div>
                            </div>
                            <div class="mb-6 col-12">
                                <div class="fw-semibold text-gray-600 fs-5">Reason:</div>
                                <div class="fw-bold text-gray-800 fs-4 text-capitalize">
                                    {{ $data['reason'] }}
                                </div>
                            </div>
                            <div class="m-0">
                                <div class="fw-semibold text-gray-600 fs-5">Overtime :</div>
                                <div class="fw-bold fs-4 text-gray-800 d-flex align-items-center">
                                    {{ $data['overtime_from'] }} to {{ $data['overtime_to'] }}
                                </div>
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--end::Invoice 2 sidebar-->
                    </div>
                </div>
                <div class="modal-footer flex-center border-0 pt-0">
                    @if($data['is_approved'] == null && $isLinkValid)
                        <button type="button" modal-id="#ob_request_modal" data-id="{{ $data['encrypted_id'] }}" id="" class="btn btn-success me-4 action" data-action="approve">
                            <span class="indicator-label">Approve</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <button type="button" modal-id="#ob_request_modal" data-id="{{ $data['encrypted_id'] }}"
                        class="btn btn-danger me-3 action" data-action="reject">Reject</button>
                    @endif
                    <button type="button" modal-id="#ob_request_modal" data-id="{{ $data['encrypted_id'] }}" id="" class="btn btn-info me-4 @if($data['is_approved'] == null) d-none @endif next-request">
                        <span class="indicator-label">Next Request</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
