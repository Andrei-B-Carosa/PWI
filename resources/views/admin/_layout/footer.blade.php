<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up"><span class="path1"></span><span class="path2"></span></i>
</div>

<script src="{{ asset('assets/admin/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

<script type="text/javascript">
    var hostUrl = "assets/index.html";
    var asset_url = $('meta[name="asset-url"]').attr("content");
    var csrf_token = $('meta[name="csrf-token"]').attr("content");
    var app = $("#kt_app_main");
    var BASE_URL = window.location.host;
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
    var page_block = new KTBlockUI(document.querySelector('.app-page'), {
        message: `<div class="blockui-message"><span class="spinner-border text-primary"></span>Loading. . .</div>`,
    });
</script>

<script src="{{ asset('js/admin/sidebar.js') }}" type="module"></script>
