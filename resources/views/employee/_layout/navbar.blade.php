<div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
    <div class=" container-xxl  d-flex flex-grow-1 flex-stack">
        <div class="d-flex align-items-center me-5">
            <div class="d-lg-none btn btn-icon btn-active-color-primary w-30px h-30px ms-n2 me-3"
                id="kt_header_menu_toggle">
                <i class="ki-duotone ki-abstract-14 fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <span class="d-none d-lg-inline text-white fw-bold fs-4">
                HR Information System
            </span>
        </div>
        <div class="topbar d-flex align-items-stretch flex-shrink-0" id="kt_topbar">
            <div class="d-flex align-items-center ms-2 ms-lg-4" id="kt_header_user_menu_toggle">
                <div class="symbol symbol-circle symbol-40px overflow-hidden text-center rounded-1"
                data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end">
                    <a href="javascript:;">
                        <div class="symbol-label fs-3 bg-primary text-white rounded-0">
                            {{ Auth::user()->employee->fname[0] }}
                        </div>
                     </a>
                </div>

                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    {{ Auth::user()->employee->fullname() }}
                                    <span class="badge badge-success fw-bold fs-8 px-2 py-1 ms-2">
                                        Employee
                                    </span>
                                </div>

                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ $user = Auth::user()->username }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="separator my-2"></div>

                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">
                                Mode

                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                    <i class="ki-duotone ki-night-day theme-light-show fs-2"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span><span class="path8"></span><span
                                            class="path9"></span><span class="path10"></span></i> <i
                                        class="ki-duotone ki-moon theme-dark-show fs-2"><span
                                            class="path1"></span><span class="path2"></span></i> </span>
                            </span>
                        </a>

                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                            data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-night-day fs-2"><span
                                                class="path1"></span><span class="path2"></span><span
                                                class="path3"></span><span class="path4"></span><span
                                                class="path5"></span><span class="path6"></span><span
                                                class="path7"></span><span class="path8"></span><span
                                                class="path9"></span><span class="path10"></span></i>
                                    </span>
                                    <span class="menu-title">
                                        Light
                                    </span>
                                </a>
                            </div>
                            <!--end::Menu item-->

                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-moon fs-2"><span
                                                class="path1"></span><span class="path2"></span></i>
                                    </span>
                                    <span class="menu-title">
                                        Dark
                                    </span>
                                </a>
                            </div>
                            <!--end::Menu item-->

                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-screen fs-2"><span
                                                class="path1"></span><span class="path2"></span><span
                                                class="path3"></span><span class="path4"></span></i>
                                    </span>
                                    <span class="menu-title">
                                        System
                                    </span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->

                    </div>

                    <div class="menu-item px-5">
                        <a target="_blank" class="menu-link px-5" onclick="event.preventDefault();
                                    document.getElementById('logout').submit();">
                                        {{ __('Sign Out') }}
                        </a>
                        <form id="logout" action="{{ route('employee.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-menu-container d-flex align-items-stretch flex-stack h-lg-75px w-100" id="kt_header_nav">

        <div class="header-menu  container-xxl  flex-column align-items-stretch flex-lg-row"
            data-kt-drawer="true" data-kt-drawer-name="header-menu"
            data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
            data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#kt_header_menu_toggle" data-kt-swapper="true"
            data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">

            <div class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-700 menu-state-primary menu-arrow-gray-400 fw-semibold my-5 my-lg-0 align-items-stretch flex-grow-1 px-2 px-lg-0"
                id="#kt_header_menu" data-kt-menu="true">

                @foreach ($result as $data)
                    @if(count($data['file_layer']) == 0)
                        <div class="menu-item navbar me-0 me-lg-3" id="{{$data['href']}}" data-page="{{$data['href']}}" data-link="employee/{{$data['href']}}">
                            <span class="menu-link py-3">
                                <span class="menu-title">{{ $data['name'] }}</span>
                            </span>
                        </div>
                    @else
                        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                            data-kt-menu-placement="bottom-start"
                            class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                            <span class="menu-link py-3">
                                <span class="menu-title">{{ $data['name'] }}</span>
                                <span class="menu-arrow d-lg-none"></span>
                            </span>

                            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-0 py-lg-4 w-lg-200px">
                                @foreach ($data['file_layer'] as $layer)
                                    <div class="menu-item">
                                        <a class="menu-link navbar py-3 sub-menu" id="{{$layer['href']}}" data-page="{{$layer['href']}}" data-link="employee/{{$layer['href']}}" href="javascript:;">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ $layer['name'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="d-flex align-items-stretch flex-shrink-0 p-4 p-lg-0"id="">

            </div>
        </div>
    </div>
</div>
