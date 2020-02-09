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
                        <li class="m-nav__item"><a href="{collectionURL($daoCollection->name,'view',$daoDocument->public_id)}" class="m-nav__link"><span class="m-nav__link-text">{'View'|_t} {$daoDocument->public_id}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">

            <div class="row">
                <div class="col-lg-12">
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
                                            <span><i class="la la-arrow-left"></i><span>Back</span></span>
                                        </a>
                                    {/if}

                                    <div class="btn-group m--margin-right-10">
                                        {if InnyCMS::checkPermission("collection","update",$daoCollection->name,$daoDocument->public_id) || InnyCMS::checkPermission("collection","draft",$daoCollection->name,$daoDocument->public_id)}
                                            <a href="{collectionURL($daoCollection->name,'edit',$daoDocument->public_id)}" class="btn btn-brand m-btn m-btn--icon m-btn--wide m-btn--md">
                                                <span><i class="la la-edit"></i><span>{'Edit'|_t}</span></span>
                                            </a>
                                        {/if}
                                        {if InnyCMS::checkPermission("collection","delete",$daoCollection->name,$daoDocument->public_id)}
                                            <button type="button" class="btn btn-brand  dropdown-toggle dropdown-toggle-split m-btn m-btn--md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="javascript:return false;" data-id="{$daoDocument->public_id}" data-role="button" data-toggle="modal" data-target="#confirmDeleteItem" title="{'Delete'|_t} {$daoDocument->public_id}"><i class="la la-trash"></i> {'Delete'|_t}</a>
                                            </div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">

                            {$colCount=0}
                            {foreach $innyTypes as $innyType}
                                {if $colCount%12 == 0}<div class="form-group m-form__group row">{/if}
                                <div class="col-md-{$innyType->getParamValue('cols',12)} col-12">
                                    {$innyType->preview()}
                                </div>
                                {$colCount=$colCount + $innyType->getParamValue('cols',12)}
                                {if $colCount%12 == 0}
                                    </div>
                                    {if $innyType->getParamValue('separator',true)}<hr/>{/if}
                                {/if}
                            {/foreach}

                        </div>
                        <div class="m-portlet__foot">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <p>{'Status'|_t}: <span class="m-badge {if $daoDocument->status == "2"}m-badge--danger{else}m-badge--success{/if} m-badge--dot"></span> <i>{if $daoDocument->status == "2"}{'Unpublished'|_t}{else}{'Published'|_t}{/if}</i></p>
                                    <p>{'Draft'|_t}: <span class="m-badge {if !empty($daoDocument->draft)}m-badge--warning{else}m-badge--success{/if} m-badge--dot"></span> <i>{if !empty($daoDocument->draft)}{'Has Draft'|_t}{else}{'No Draft'|_t}{/if}</i></p>
                                    <p>{'Creation Date'|_t}: <i>{$daoDocument->aud_ins_date}</i></p>
                                    <p>{'Last Update Date'|_t}: <i>{$daoDocument->aud_upd_date}</i></p>
                                    <p>{'Creation User'|_t}: <i>{$daoDocument->aud_ins_user}</i></p>
                                    <p>{'Last Update User'|_t}: <i>{$daoDocument->aud_upd_user}</i></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end::Portlet-->
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="confirmDeleteItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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

        </div>
    </div>
</div>

<script type="text/javascript">
    var itemDeleteUrl = "./delete-{$daoCollection->name}!";
</script>

<!-- end::Body -->
{include file="footer.tpl" script="collection-view"}