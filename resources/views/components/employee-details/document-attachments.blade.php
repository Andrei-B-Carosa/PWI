<div>
    @if($documents->isNotEmpty())
        <table id="document_attachments_table" data-kt-filemanager-table="files"
        class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="">Name</th>
                    <th class="">Document Type</th>
                    <th class="">Last Modified</th>
                    <th class="">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach ($documents as $data)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="#" class="text-gray-800 text-hover-primary">{{ $data->filename }}</a>
                            </div>
                        </td>
                        <td> {{ config('document_values.document_type.'.$data->file_id) }}</td>

                        <td> {{ date('m-d-Y H:i a',strtotime($data->created_at)) }} </td>
                        <td class="">
                            <div class="d-flex ">
                                <div class="ms-2">
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-light-primary btn-active-primary"
                                        data-kt-menu-trigger="click"
                                        data-kt-menu-placement="bottom-end">
                                        <i class="ki-duotone ki-dots-square fs-5 m-0"><span
                                                class="path1"></span><span
                                                class="path2"></span><span
                                                class="path3"></span><span
                                                class="path4"></span></i> </button>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="{{ asset('storage/employee/documents/' . $data->filename) }}" target ="_blank" class="menu-link px-3 view">
                                                View File
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="{{ asset('storage/employee/documents/' . $data->filename) }}" download="{{ $data->filename }}" class="menu-link px-3 download">
                                                Download File
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" class="menu-link text-danger px-3 delete" data-id="{{ Crypt::encrypt($data->id) }}">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div id="empty_state_wrapper" >
            <div class="card-px text-center pt-15 pb-15">
                <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">Nothing in here</h2>
                <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                    No results found
                </p>

            </div>
            <div class="text-center pb-15 px-5">
                <img src="{{ asset('assets/media/illustrations/sketchy-1/16.png') }}" alt="" class="mw-100 h-200px h-sm-325px">
            </div>
        </div>
    @endif
    <div class="modal fade" id="modal_upload_document" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                    <div class="text-center">
                        <h1 class="mb-3 modal_title">Upload Documents</h1>
                        <div class="text-muted fs-5">Fill-up the form and click
                            <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="form_upload_document" modal-id="#modal_upload_document" class="form fv-plugins-bootstrap5 fv-plugins-framework ms-xl-7"
                        action="" enctype="multipart/form-data">
                        <div class="form-group fv-row row">
                            <label class="col-lg-3 col-form-label text-lg-right required">Document Type:</label>
                            <div class="col-lg-8 mb-8">
                                <div class="fv-plugins-icon-container">
                                    <select name="file_type" data-control="select2" data-placeholder="Select an option"
                                            class="form-select form-select-solid fw-bold" data-required="true" remote-validation="false">
                                            {!! $options['document_type'] !!}
                                    </select>
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group fv-row row">
                            <label class="col-lg-3 col-form-label text-lg-right required">Upload File:</label>
                            <div class="col-lg-8">
                                <input class="form-control" type="file" name="files" data-required="true" remote-validation="false">
                                <span class="form-text text-muted">Max file size is 1MB</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" modal-id="#modal_upload_document" data-id="" id="" class="btn btn-primary me-4 submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" modal-id="#modal_upload_document" id="" class="btn btn-light me-3 cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
