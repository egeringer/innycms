{include file="header.tpl"}
    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        {include file="menu-aside.tpl" section="choose"}
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <!-- BEGIN: Subheader -->
            <div class="m-subheader">
                <div class="d-flex align-items-center">
                    <div class="mr-auto">
                        <h3 class="m-subheader__title m-subheader__title--separator">InnyCMS</h3>
                        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                            <li class="m-nav__item m-nav__item--home"><a href="./" class="m-nav__link m-nav__link--icon"><i class="m-nav__link-icon la la-home"></i></a></li>
                            <li class="m-nav__separator">-</li>
                            <li class="m-nav__item"><a href="./choose" class="m-nav__link"><span class="m-nav__link-text">{'Choose a Site'|_t}</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- END: Subheader -->
            <!-- BEGIN: Content -->
            <div class="m-content">
                <div class="row">
                    {foreach from=$sites key=$key item=$site}
                        <div class="col-sm-6 col-xl-4">
                            <!--begin::Portlet-->
                            <div class="m-portlet">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon">
                                            {if $site.status == "1"}
                                                <i class="fa fa-check-circle text-success"></i>
                                            {elseif $site.status == "2"}
                                                <i class="fa fa-times-circle text-danger"></i>
                                            {elseif $site.status == "3"}
                                                <i class="fa fa-check-circle text-warning"></i>
                                            {/if}
                                        </span>
                                            <h3 class="m-portlet__head-text">{$site.name}<br/><small>{$site.url}</small></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__foot">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            {if $site.status == "1" || $site.status == "3"}
                                                <a href="./choose!{$site.public_id}" class="btn btn-success">
                                                    <span><i class="fa fa-edit"></i> <span>{'Edit Site'|_t}</span></span>
                                                </a>
                                            {else}
                                                <a href="" class="btn btn-danger disabled">
                                                    <span><i class="fa fa-edit"></i> <span>{'Edit Site'|_t}</span></span>
                                                </a>
                                            {/if}
                                            <a href="{$site.url}" class="btn btn-info" target="_blank">
                                                <span><i class="fa fa-external-link"></i> <span>{'Visit Site'|_t}</span></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Portlet-->
                        </div>
                    {/foreach}
                </div>
            </div>
            <!-- END: Content -->
        </div>
    </div>
    <!-- end:: Body -->
{include file="footer.tpl"}