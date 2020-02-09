{include file="header.tpl" section="dashboard"}
<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
    {include file="menu-aside.tpl" section="dashboard"}
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator">InnyCMS :: {InnyCMS::getSiteName()}</h3>
                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                        <li class="m-nav__item m-nav__item--home"><a href="./" class="m-nav__link m-nav__link--icon"><i class="m-nav__link-icon la la-home"></i></a></li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="./home" class="m-nav__link"><span class="m-nav__link-text">{'Dashboard'|_t}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--brand m-portlet--head-solid-bg m-portlet--head-sm" m-portlet="true" id="m_portlet_tools_1">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon"><i class="m-menu__link-icon flaticon-settings-1"></i></span>
                            <h3 class="m-portlet__head-text">{$smarty.const.SCRIPT_NAME}</h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item"><a href="#"  m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon"><i class="la la-expand"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body m-portlet__body--no-padding">
                    {$html}
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
</div>
<!-- end::Body -->
{include file="footer.tpl"}
