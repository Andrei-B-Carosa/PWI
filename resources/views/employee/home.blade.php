<div class="page-home">

    <div class="row gy-5 gx-xl-10">
        <div class="col-xl-12 mb-5 mb-xl-10">
            <div class="card h-175px bgi-no-repeat bgi-size-contain"
                style="background-color: #1B283F; background-position: right; background-image:url('{{ asset('assets/media/misc/taieri.svg') }}')">
                <div class="card-body">
                    <h2 class="text-white fw-bold mb-5"><span class="lh-lg">
                            Hello {{ $emp_id = Auth::user()->employee->fname }} !
                            <br>Welcome to {{config('company.company_name')}} - Employee
                            Portal</span></h2>

                    <button modal-id="#modal_view_group" class="btn btn-danger gw-bold px-6 py-3 view-group">View My
                        Group</button>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-5 g-xl-10">
        <div class="col-xxl-4 col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="px-9 pt-7 card-rounded h-275px w-100" style="background-color: #1B283F;">
                        <div class="d-flex flex-stack">
                            <h3 class="m-0 text-white fw-bold fs-3">Leave Credit</h3>
                        </div>
                        <div class="d-flex text-center flex-column text-white pt-8">
                            <span class="fw-semibold fs-7">Your Total Leave</span>
                            <span class="fw-bold fs-2x pt-1 total-leave">0</span>
                        </div>
                    </div>

                    <div class="bg-body shadow-sm card-rounded mx-9 mb-9 px-6 py-9 position-relative z-index-1 leave-breakdown"
                        style="margin-top: -100px">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8 col-12">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h4 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Approval History</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-7"></span>
                    </h4>
                </div>
                <div class="card-body py-0">
                    <div class="d-flex flex-column flex-xl-row p-5">
                        <div class="flex-lg-row-fluid">
                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div id="approval_history_wrapper">
                                    <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                            id="approval_history_table">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_view_group" tabindex="-1" aria-hidden="false"  data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered mw-700px">
            <div class="modal-content">
                <div class="modal-header border-0 mx-5 mx-xl-18" id="">
                    <h2>My Group</h2>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body  mx-5 mx-xl-18 pt-0 pb-15 group-details">
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                        <div class="d-flex flex-stack flex-grow-1 ">
                            <div class=" fw-semibold">
                                <h4 class="text-gray-900 fw-bold">You are a member of <span class="group-name"></span></h4>
                                <div class="fs-6 text-muted">You can file a OB, Leave and Overtime to your approver.</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-10 div_approver">
                        <div class="fs-5 fw-bold ">Approvers</div>
                        <div class="mh-375px me-n7 pe-7 approver-list">
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="fs-5 fw-bold mb-2">Members</div>
                        <div class="member-list mh-300px scroll-y me-n7 pe-7">
                            <div class="mh-375px me-n7 pe-7 member-lists">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
