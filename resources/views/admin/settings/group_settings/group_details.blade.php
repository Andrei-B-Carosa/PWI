<div class="page-group-details">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid">
            <div id="kt_app_content_container" class="app-container  container-xxl">
                <div class="d-flex flex-column flex-lg-row">
                    <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
                        <div class="card card-flush">
                            <div class="card-header pt-5">
                                <div class="card-title">
                                    <h2 class="d-flex align-items-center">
                                        Approvers
                                        <span class="text-gray-600 fs-6 ms-1 approvers_count d-none"></span>
                                    </h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column text-gray-600 approver-list">

                                </div>
                            </div>
                            <div class="card-footer pt-0">
                                <button type="button" class="btn btn-light-primary btn-active-primary"
                                        data-bs-toggle="modal" data-bs-target="#modal_add_approver">
                                    Add Approver
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex-lg-row-fluid ms-lg-10">
                        <div class="card card-flush mb-6 mb-xl-9">
                            <div class="card-header pt-5">
                                <div class="card-title">
                                    <h2 class="d-flex align-items-center">Members
                                        <span class="text-gray-600 fs-6 ms-1 groups_count">(14)</span>
                                    </h2>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Search-->
                                    <button type="button" class="ms-2 btn btn-light-primary open">
                                        Add Member
                                    </button>

                                    {{-- <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="text"
                                            class="form-control form-control-solid w-250px ps-15 search"
                                            placeholder="Search here . . ." />
                                    </div> --}}
                                    <!--end::Search-->
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                {{-- <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0"
                                    id="kt_roles_view_table">
                                    <thead>
                                        <tr
                                            class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-10px pe-2">
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        data-kt-check="true"
                                                        data-kt-check-target="#kt_roles_view_table .form-check-input"
                                                        value="1" />
                                                </div>
                                            </th>
                                            <th class="min-w-50px">ID</th>
                                            <th class="min-w-150px">User</th>
                                            <th class="min-w-125px">Joined Date</th>
                                            <th class="text-end min-w-100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID9780</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div class="symbol-label">
                                                            <img src="../../../assets/media/avatars/300-6.jpg"
                                                                alt="Emma Smith" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Emma
                                                        Smith</a>
                                                    <span>smith@kpmg.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                19 Aug 2023, 10:30 am </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID8065</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div
                                                            class="symbol-label fs-3 bg-light-danger text-danger">
                                                            M </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Melody
                                                        Macy</a>
                                                    <span>melody@altbox.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                20 Dec 2023, 6:05 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID1869</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div class="symbol-label">
                                                            <img src="../../../assets/media/avatars/300-1.jpg"
                                                                alt="Max Smith" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Max
                                                        Smith</a>
                                                    <span>max@kt.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                20 Jun 2023, 10:10 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID3561</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div class="symbol-label">
                                                            <img src="../../../assets/media/avatars/300-5.jpg"
                                                                alt="Sean Bean" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Sean
                                                        Bean</a>
                                                    <span>sean@dellito.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                24 Jun 2023, 5:30 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID9284</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div class="symbol-label">
                                                            <img src="../../../assets/media/avatars/300-25.jpg"
                                                                alt="Brian Cox" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Brian
                                                        Cox</a>
                                                    <span>brian@exchange.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                20 Jun 2023, 2:40 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID5225</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div
                                                            class="symbol-label fs-3 bg-light-warning text-warning">
                                                            C </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Mikaela
                                                        Collins</a>
                                                    <span>mik@pex.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                21 Feb 2023, 9:23 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID1343</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div class="symbol-label">
                                                            <img src="../../../assets/media/avatars/300-9.jpg"
                                                                alt="Francis Mitcham" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Francis
                                                        Mitcham</a>
                                                    <span>f.mit@kpmg.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                15 Apr 2023, 6:43 am </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID3561</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div
                                                            class="symbol-label fs-3 bg-light-danger text-danger">
                                                            O </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Olivia
                                                        Wild</a>
                                                    <span>olivia@corpmail.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                20 Dec 2023, 10:10 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID6124</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div
                                                            class="symbol-label fs-3 bg-light-primary text-primary">
                                                            N </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Neil
                                                        Owen</a>
                                                    <span>owen.neil@gmail.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                20 Jun 2023, 6:05 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID1835</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div class="symbol-label">
                                                            <img src="../../../assets/media/avatars/300-23.jpg"
                                                                alt="Dan Wilson" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Dan
                                                        Wilson</a>
                                                    <span>dam@consilting.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                05 May 2023, 8:43 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID8761</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div
                                                            class="symbol-label fs-3 bg-light-danger text-danger">
                                                            E </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Emma
                                                        Bold</a>
                                                    <span>emma@intenso.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                19 Aug 2023, 11:05 am </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID3791</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div class="symbol-label">
                                                            <img src="../../../assets/media/avatars/300-12.jpg"
                                                                alt="Ana Crown" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Ana
                                                        Crown</a>
                                                    <span>ana.cf@limtel.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                15 Apr 2023, 11:05 am </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID7998</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div
                                                            class="symbol-label fs-3 bg-light-info text-info">
                                                            A </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Robert
                                                        Doe</a>
                                                    <span>robert@benko.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                22 Sep 2023, 6:05 pm </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="1" />
                                                </div>
                                            </td>
                                            <td>ID2702</td>
                                            <td class="d-flex align-items-center">
                                                <!--begin:: Avatar -->
                                                <div
                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="../users/view.html">
                                                        <div class="symbol-label">
                                                            <img src="../../../assets/media/avatars/300-13.jpg"
                                                                alt="John Miller" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->

                                                <!--begin::User details-->
                                                <div class="d-flex flex-column">
                                                    <a href="../users/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">John
                                                        Miller</a>
                                                    <span>miller@mapple.com</span>
                                                </div>
                                                <!--begin::User details-->
                                            </td>
                                            <td>
                                                20 Dec 2023, 10:30 am </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 m-0"></i> </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="../users/view.html"
                                                            class="menu-link px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-kt-roles-table-filter="delete_row">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> --}}
                                <x-table id="group_members" class="table-striped table-sm align-middle table-row-dashed dataTable">
                                </x-table>
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
    action="/hris/admin/settings/group_details/update_approver" >
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


