<!DOCTYPE html>

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title>InnyCMS</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: { "families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"] },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->

    <!--begin::Global Theme Styles -->
    <link href="css/vendors.bundle.css" rel="stylesheet" type="text/css"/>
    <!--RTL version:<link href="css/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
    <link href="css/style.bundle.css" rel="stylesheet" type="text/css"/>
    <!--RTL version:<link href="css/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
    <!--end::Global Theme Styles -->
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-light m-aside-left--fixed m-aside-left--offcanvas m-aside-left--minimize m-brand--minimize m-footer--push m-aside--offcanvas-default">

<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">

    <!-- BEGIN: Header -->

    {include file="common-header.tpl"}

    <!-- END: Header -->

    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

        <!-- BEGIN: Left Aside -->
        <button class="m-aside-left-close  m-aside-left-close--skin-light " id="m_aside_left_close_btn"><i class="la la-close"></i></button>
        <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light ">

            <!-- BEGIN: Brand -->
            <div class="m-brand  m-brand--skin-light ">
                <a href="./dashboard" class="m-brand__logo">
                    <img alt="" src="images/inny-logo.svg"/>
                </a>
            </div>

            <!-- END: Brand -->

            <!-- BEGIN: Aside Menu -->

            {include file="common-aside.tpl"}

            <!-- END: Aside Menu -->
        </div>
        <div class="m-aside-menu-overlay"></div>

        <!-- END: Left Aside -->
        <div class="m-grid__item m-grid__item--fluid m-wrapper">

            {include file="common-subheader.tpl"}

            <div class="m-content">
                {include file=$templateFile}
            </div>
        </div>
    </div>

    <!-- end:: Body -->

    <!-- begin::Footer -->

    {include file="common-footer.tpl"}

    <!-- end::Footer -->
</div>

<!-- end:: Page -->

<!-- begin::Scroll Top -->
<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>

<!-- end::Scroll Top -->

<!--begin::Global Theme Bundle -->
<script src="js/vendors.bundle.js" type="text/javascript"></script>
<script src="js/scripts.bundle.js" type="text/javascript"></script>

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts -->
<script src="js/common.js"></script>
<script>
    $("#m_repeater_1").on("change",".select-type",function(){
        if($(this).find(":selected").val() === "collection"){
            $(this).parent().parent().parent().find(".group-collection").removeClass("d-none");
            $(this).parent().parent().parent().find(".group-url").addClass("d-none");
        }else{
            $(this).parent().parent().parent().find(".group-collection").addClass("d-none");
            $(this).parent().parent().parent().find(".group-url").removeClass("d-none");
        }
    });
    var BootstrapSelect = {
        init: function() {
            $(".m_selectpicker").selectpicker();
        }
    };
    var FormRepeater = {
        init: function() {
            $("#m_repeater_1").repeater({
                initEmpty: !1,
                show: function() {
                    $(this).slideDown();
                    $(".m_selectpicker").selectpicker();
                },
                hide: function (deleteElement) {
                    if(confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            })
        }
    };
    jQuery(document).ready(function() {
        FormRepeater.init()
    });
    jQuery(document).ready(function() {
        BootstrapSelect.init()
    });
</script>
<!--end::Page Scripts -->
</body>

<!-- end::Body -->
</html>