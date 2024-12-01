<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true"
data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
    <a href="javascript:;">
        <span class="d-none d-lg-inline text-white fw-bold fs-5">
            HR Information System
        </span>
    </a>
    <div id="kt_app_sidebar_toggle"
        class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate "
        data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
        data-kt-toggle-name="app-sidebar-minimize">
        <i class="ki-duotone ki-black-left-line fs-3 rotate-180"><span class="path1"></span><span
                class="path2"></span></i>
    </div>
</div>
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <div id="kt_app_sidebar_menu_scroll" class="my-5 mx-3">
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">MAIN MENU</span>
                    </div>
                </div>
                @foreach ($result as $data)
                    @if(count($data['file_layer']) == 0)
                        <div class="menu-item">
                            <a class="menu-link sidebar" id="{{$data['href']}}" data-page="{{$data['href']}}" data-link="dispatcher/{{$data['href']}}" href="javascript:;">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-address-book fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ $data['name'] }}</span>
                            </a>
                        </div>
                    @else
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-address-book fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                        <span class="path7"></span>
                                        <span class="path8"></span>
                                    </i>
                                </span>
                                    <span class="menu-title">{{ $data['name'] }}</span>
                                    <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                @foreach ($data['file_layer'] as $layer)
                                    <div class="menu-item">
                                        <a class="menu-link sidebar" id="{{$layer['href']}}" data-page="{{$layer['href']}}" data-link="dispatcher/{{$layer['href']}}" href="javascript:;">
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
        </div>
    </div>
</div>
</div>
