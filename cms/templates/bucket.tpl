{include file="header.tpl"}
<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
    {include file="menu-aside.tpl"}
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator">InnyCMS :: {InnyCMS::getSiteName()}</h3>
                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                        <li class="m-nav__item m-nav__item--home"><a href="./" class="m-nav__link m-nav__link--icon"><i class="m-nav__link-icon la la-home"></i></a></li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="./bucket" class="m-nav__link"><span class="m-nav__link-text">Bucket</span></a></li>
                        {if isset($smarty.get.q)}
                            <li class="m-nav__separator">-</li>
                            <li class="m-nav__item"><a href="./bucket" class="m-nav__link"><span class="m-nav__link-text">{"Searching <b>%s</b> in %s"|_t:$smarty.get.q:$smarty.get.type}</span></a></li>
                        {/if}
                    </ul>
                </div>
                <div>
                    <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                        <a class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                            <i class="la la-plus m--hide"></i>
                            <i class="la la-ellipsis-h"></i>
                        </a>
                        <div class="m-dropdown__wrapper">
                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                            <div class="m-dropdown__inner">
                                <div class="m-dropdown__body">
                                    <div class="m-dropdown__content">
                                        <ul class="m-nav">
                                            <li class="m-nav__section m-nav__section--first m--hide">
                                                <span class="m-nav__section-text">
                                                    {"Bucket Quick Actions"|_t}
                                                </span>
                                            </li>
                                            {*
                                            <li class="m-nav__item">
                                                <a href="./statistics-bucket" class="m-nav__link">
                                                    <i class="m-nav__link-icon fa fa-pie-chart"></i>
                                                    <span class="m-nav__link-text">
                                                        {"Bucket Statistics"|_t}
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="m-nav__separator m-nav__separator--fit"></li>
                                            *}
                                            <li class="m-nav__item">
                                                <a role="button" href="./bucket" data-toggle="modal" data-target="#cleanBucket" class="m-nav__link">
                                                    <i class="m-nav__link-icon fa fa-magic"></i>
                                                    <span class="m-nav__link-text">
                                                        {"Clean Bucket"|_t}
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="m-nav__item">
                                                <a role="button" href="./bucket" data-toggle="modal" data-target="#emptyBucket" class="m-nav__link">
                                                    <i class="m-nav__link-icon fa fa-trash"></i>
                                                    <span class="m-nav__link-text">
                                                        {"Empty Bucket"|_t}
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">

            {if !isset($smarty.get.q)}
                <!--begin::Portlet-->
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="flaticon-plus"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    {"Upload new files to your bucket"|_t}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group row">
                                <div class="col-12">
                                    <div class="m-dropzone dropzone m-dropzone--danger" action="./bucket" id="bucket-zone">
                                        <div class="m-dropzone__msg dz-message needsclick">
                                            <h3 class="m-dropzone__msg-title">{"Drop files here or click to upload."|_t}</h3>
                                            <span class="m-dropzone__msg-desc">{"Repeated files will be skipped."|_t}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            {/if}

            <!--begin::Portlet-->
            <div class="m-portlet {if !isset($smarty.get.q)}m-portlet--collapsed{/if} m-portlet--head-sm" m-portlet="true">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-search"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                {"Search files you have already uploaded"|_t}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="#"  m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                                    <i class="la la-angle-down"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="m-form" method="get">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-8">
                                <label for="example_input_full_name">
                                    {"Filename or Tags"|_t}
                                </label>
                                <input type="text" class="form-control m-input" name="q" placeholder="{'Enter tags or filenames you want to look for...'|_t}" value="{$smarty.get.q|default:""}">
                                <input type="hidden" name="action" value="search" />
                            </div>
                            <div class="col-lg-4">
                                <label for="example_input_full_name">
                                    {"File Type"|_t}
                                </label>
                                <select class="form-control m-bootstrap-select m_selectpicker" name="type">
                                    <option value="all">{"All files"|_t}</option>
                                    <option value="file" {if isset($smarty.get.type) && $smarty.get.type == "binary"}selected="selected"{/if}>{"Binary"|_t}</option>
                                    <option value="image" {if isset($smarty.get.type) && $smarty.get.type == "image"}selected="selected"{/if}>{"Image"|_t}</option>
                                    <option value="pdf" {if isset($smarty.get.type) && $smarty.get.type == "pdf"}selected="selected"{/if}>{"PDF"|_t}</option>
                                    <option value="document" {if isset($smarty.get.type) && $smarty.get.type == "document"}selected="selected"{/if}>{"Document"|_t}</option>
                                    <option value="spreadsheet" {if isset($smarty.get.type) && $smarty.get.type == "spreadsheet"}selected="selected"{/if}>{"Spreadsheet"|_t}</option>
                                    <option value="presentation" {if isset($smarty.get.type) && $smarty.get.type == "Presentation"}selected="selected"{/if}>{"Presentation"|_t}</option>
                                    <option value="video" {if isset($smarty.get.type) && $smarty.get.type == "video"}selected="selected"{/if}>{"Video"|_t}</option>
                                    <option value="audio" {if isset($smarty.get.type) && $smarty.get.type == "audio"}selected="selected"{/if}>{"Audio"|_t}</option>
                                    <option value="flash" {if isset($smarty.get.type) && $smarty.get.type == "flash"}selected="selected"{/if}>{"Flash"|_t}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot">
                        <button type="submit" class="btn btn-brand">{"Search"|_t}</button>
                        <button type="reset" class="btn btn-secondary">{"Back"|_t}</button>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Portlet-->

            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--head-sm" m-portlet="true">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-multimedia"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                {"Explore recently uploaded files."|_t}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                                    <i class="la la-angle-down"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
                    <div class="m-portlet__body divheader">
                        {assign var="resultsCount" value=0}
                        {while $daoBucket->fetch()}
                            {include file="bucket-item.tpl"}
                            {assign var="resultsCount" value=$resultsCount+1}
                        {/while}
                        {if $resultsCount == 0}
                            <div class="form-group m-form__group row"><div class="col-xs-12 text-center">{if isset($smarty.get.q)}{"No results found. Try other keywords"|_t}{if !empty($smarty.get.type) && $smarty.get.type != "all"} {"or do not filter by file type"|_t}{/if}.{else}{"No files has been uploaded yet."|_t}{/if}</div></div>
                        {/if}
                    </div>
                </div>
            </div>
            <!--end::Portlet-->

            {include file="bucket-common-footer.tpl"}

        </div>
    </div>
</div>
<!-- end::Body -->
{include file="footer.tpl" script="bucket"}