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
                        <li class="m-nav__item"><a href="{collectionURL($daoCollection->name,'edit',$daoDocument->public_id)}" class="m-nav__link"><span class="m-nav__link-text">{'Edit'|_t} {$daoDocument->public_id}</span></a></li>
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
                                            <h3 class="m-portlet__head-text">{$collectionMetadata.name}{if isset($collectionMetadata.help)}<small>{$collectionMetadata.help}</small>{/if}<small>&nbsp;<span class="m-badge {if $daoDocument->status == "2"}m-badge--danger{else}m-badge--success{/if} m-badge--dot"></span> <i>{if $daoDocument->status == "2"}Unp{else}P{/if}ublished</i> {if !empty($daoDocument->draft)}<span class="m-badge m-badge--warning m-badge--dot"></span> <i>Viewing Draft</i>{/if}</small></h3>
                                        </div>
                                    </div>
                                    <div class="m-portlet__head-tools">

                                        {if InnyCMS::checkPermission("collection","list",$daoCollection->name)}
                                            <a href="{collectionURL($daoCollection->name)}" class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                                                <span><i class="la la-arrow-left"></i><span>{'Back'|_t}</span></span>
                                            </a>
                                        {/if}

                                        <div class="btn-group m--margin-right-10">
                                            {if InnyCMS::checkPermission("collection","update",$daoCollection->name,$daoDocument->public_id)}
                                                <button type="submit" class="btn btn-success m-btn m-btn--icon m-btn--wide m-btn--md">
                                                    <span><i class="la la-check"></i><span>{'Save'|_t}</span></span>
                                                </button>
                                            {/if}
                                            <button type="button" class="btn btn-success  dropdown-toggle dropdown-toggle-split m-btn m-btn--md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                {if InnyCMS::checkPermission("collection","create",$daoCollection->name,$daoDocument->public_id) && InnyCMS::checkPermission("collection","update",$daoCollection->name,$daoDocument->public_id)}
                                                    <a class="dropdown-item" href="javascript:saveandnew();"><i class="la la-plus"></i> {'Save & New'|_t}</a>
                                                {/if}
                                                {if InnyCMS::checkPermission("collection","view",$daoCollection->name,$daoDocument->public_id) && InnyCMS::checkPermission("collection","update",$daoCollection->name,$daoDocument->public_id)}
                                                    <a class="dropdown-item" href="javascript:saveandview();"><i class="la la-eye"></i> {'Save & View'|_t}</a>
                                                {/if}
                                                {if InnyCMS::checkPermission("collection","create",$daoCollection->name,$daoDocument->public_id) && InnyCMS::checkPermission("collection","update",$daoCollection->name,$daoDocument->public_id)}
                                                    <a class="dropdown-item" href="javascript:saveandclone();"><i class="la la-copy"></i> {'Save & Duplicate'|_t}</a>
                                                {/if}
                                                {if InnyCMS::checkPermission("collection","list",$daoCollection->name) && InnyCMS::checkPermission("collection","update",$daoCollection->name,$daoDocument->public_id)}
                                                    <a class="dropdown-item" href="javascript:saveandclose();"><i class="la la-arrow-left"></i> {'Save & Close'|_t}</a>
                                                {/if}
                                                <a class="dropdown-item" href="javascript:cancel();"><i class="la la-undo"></i> {'Undo Unsaved Changes'|_t}</a>

                                                {if InnyCMS::checkPermission("collection","update",$daoCollection->name,$daoDocument->public_id)}
                                                    {if $daoDocument->status == "2"}
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="javascript:savepublished();"><i class="la la-check"></i> {'Save & Publish'|_t}</a>
                                                    {else}
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="javascript:saveunpublished();"><i class="la la-check"></i> {'Save & Unpublish'|_t}</a>
                                                    {/if}
                                                {/if}

                                                {if InnyCMS::checkPermission("collection","draft",$daoCollection->name)}
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="javascript:savedraft();"><i class="la la-save"></i> {'Save as Draft'|_t}</a>
                                                    {if !empty($daoDocument->draft)}<a class="dropdown-item" href="javascript:return false;" data-id="{$daoDocument->public_id}" data-role="button" data-toggle="modal" data-target="#confirmDiscardDraft" title="{'Discard Draft'|_t} {$daoDocument->public_id}"><i class="la la-times"></i> {'Discard Draft'|_t}</a>{/if}
                                                {/if}

                                                {if InnyCMS::checkPermission("collection","delete",$daoCollection->name,$daoDocument->public_id)}
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="javascript:return false;" data-id="{$daoDocument->public_id}" data-role="button" data-toggle="modal" data-target="#confirmDeleteItem" title="{'Delete'|_t} {$daoDocument->public_id}"><i class="la la-trash"></i> {'Delete'|_t}</a>
                                                {/if}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                {$colCount=0}
                                {foreach $innyTypes as $innyType}
                                    {if $innyType->getParamValue("adminOnly",false) == false || InnyCMS::adminUserLogged()}
                                        {if $colCount%12 == 0}<div class="row">{/if}
                                        <div class="col-md-{$innyType->getParamValue('cols',12)} col-12">
                                            {$innyType->htmlInput()}
                                        </div>
                                        {$colCount=$colCount + $innyType->getParamValue('cols',12)}
                                        {if $colCount%12 == 0}
                                            </div>
                                            {if $innyType->getParamValue('separator',true)}<hr/>{/if}
                                        {/if}
                                    {/if}
                                {/foreach}

                                {* INNYCMS INTERNAL FLAGS *}
                                <input type="hidden" id="innyaddeditflag" name="innyaddeditflag" value="save" />
                                <input type="hidden" id="innystatusflag" name="innystatusflag" value="{if $daoDocument->status == "1"}published{else}unpublished{/if}" />
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteItem" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteItemTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteItemTitle">{'Confirm Document Deletion'|_t}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p> {'You are about to delete this document.'|_t} </p>
                <p> {'This means that <strong id="modal-item-data"></strong> will be <strong>permanently deleted</strong>.'|_t} </p>
                <p> {'This action <strong>cannot be undone</strong>.'|_t} </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                <button type="button" class="btn btn-danger">{'Delete'|_t}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<div class="modal fade" id="confirmDiscardDraft" tabindex="-1" role="dialog" aria-labelledby="confirmDiscardDraftTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDiscardDraftTitle">{'Confirm Document Draft Discard'|_t}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p> {'You are about to discard all changes saved to this document.'|_t} </p>
                <p> {'This means that all changes made in <strong id="modal-item-data"></strong> will be <strong>permanently discarded</strong>.'|_t} </p>
                <p> {'This action <strong>cannot be undone</strong>.'} </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                <button type="button" class="btn btn-warning">{'Discard'|_t}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<script type="text/javascript">
    var requestMethod = "PUT";
    var collectionRequestUrl = "{collectionURL($daoCollection->name,'edit',$daoDocument->public_id)}";
    var itemDeleteUrl = "./delete-{$daoCollection->name}!";
    var itemDiscardDraftUrl = "./discard-{$daoCollection->name}!";
    var itemAddUrl = "./add-{$daoCollection->name}";
    var collectionListUrl = "./list-{$daoCollection->name}";
    var itemCloneUrl = "{collectionURL($daoCollection->name,'clone',$daoDocument->public_id)}";
    var itemViewUrl = "{collectionURL($daoCollection->name,'view',$daoDocument->public_id)}";
    var itemEditUrl = "{collectionURL($daoCollection->name,'edit',$daoDocument->public_id)}";
</script>

<!-- end::Body -->
{include file="footer.tpl" script="collection-addedit"}