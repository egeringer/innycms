{include file="header.tpl" section="collection"}
{assign var="collectionMetadata" value=$daoCollection->metadata|json_decode:true}
<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
    {include file="menu-aside.tpl" section="collection"}
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator">InnyCMS :: {InnyCMS::getSiteName()}</h3>
                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                        <li class="m-nav__item m-nav__item--home"><a href="./" class="m-nav__link m-nav__link--icon"><i class="m-nav__link-icon la la-home"></i></a></li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="./collection" class="m-nav__link"><span class="m-nav__link-text">{'Collections'|_t}</span></a></li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="{collectionURL($daoCollection->name)}" class="m-nav__link"><span class="m-nav__link-text">{$collectionMetadata.name}</span></a></li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="{collectionURL($daoCollection->name,'add')}" class="m-nav__link"><span class="m-nav__link-text">{'Add'|_t} {$collectionMetadata.item}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">

            <div class="row">
                <div class="col-lg-12">
                    <form id="itemForm" class="m-form m-form--fit m-form--label-align-right">
                        <!--begin::Portlet-->
                        <div class="m-portlet m-portlet--last m-portlet--head-lg m-portlet--responsive-mobile" id="main_portlet">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-progress">

                                    <!-- here can place a progress bar-->
                                </div>
                                <div class="m-portlet__head-wrapper">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            {if isset($collectionMetadata.icon)}<span class="m-portlet__head-icon"><i class="{$collectionMetadata.icon}"></i></span>{/if}
                                            <h3 class="m-portlet__head-text">{$collectionMetadata.name}{if isset($collectionMetadata.help)}<small>{$collectionMetadata.help}</small>{/if}</h3>
                                        </div>
                                    </div>
                                    <div class="m-portlet__head-tools">

                                        {if InnyCMS::checkPermission("collection","list",$daoCollection->name)}
                                            <a href="{collectionURL($daoCollection->name)}" class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                                                <span><i class="la la-arrow-left"></i><span>{'Back'|_t}</span></span>
                                            </a>
                                        {/if}

                                        <div class="btn-group">
                                            {if InnyCMS::checkPermission("collection","create",$daoCollection->name)}
                                                <button type="submit" class="btn btn-success m-btn m-btn--icon m-btn--wide m-btn--md">
                                                    <span><i class="la la-check"></i><span>{'Publish'|_t}</span></span>
                                                </button>
                                            {/if}
                                            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split m-btn m-btn--md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                {if InnyCMS::checkPermission("collection","create",$daoCollection->name)}
                                                    <a class="dropdown-item" href="javascript:saveandnew();"><i class="la la-plus"></i> {'Publish & New'|_t}</a>
                                                {/if}
                                                {if InnyCMS::checkPermission("collection","view",$daoCollection->name)}
                                                    <a class="dropdown-item" href="javascript:saveandview();"><i class="la la-eye"></i> {'Publish & View'|_t}</a>
                                                {/if}
                                                {if InnyCMS::checkPermission("collection","create",$daoCollection->name)}
                                                    <a class="dropdown-item" href="javascript:saveandclone();"><i class="la la-copy"></i> {'Publish & Duplicate'|_t}</a>
                                                {/if}
                                                {if InnyCMS::checkPermission("collection","list",$daoCollection->name)}
                                                    <a class="dropdown-item" href="javascript:saveandclose();"><i class="la la-undo"></i> {'Publish & Close'|_t}</a>
                                                {/if}
                                                {if InnyCMS::checkPermission("collection","create",$daoCollection->name)}
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="javascript:saveunpublished();"><i class="la la-save"></i> {'Save Unpublished'|_t}</a>
                                                {/if}
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="javascript:cancel();"><i class="la la-close"></i> {'Cancel'|_t}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">

                                {if !empty($form)}
                                    {$form}
                                {else}
                                    {include file="collection-document-add-form.tpl"}
                                {/if}

                                {* INNYCMS INTERNAL FLAGS *}
                                <input type="hidden" id="innyaddeditflag" name="innyaddeditflag" value="saveandedit" />
                                <input type="hidden" id="innystatusflag" name="innystatusflag" value="published" />
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var requestMethod = "POST";
    var collectionRequestUrl = "{collectionURL($daoCollection->name,'add')}";
</script>

<!-- end::Body -->
{include file="footer.tpl" script="collection-addedit"}
