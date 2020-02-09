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
                        <li class="m-nav__item"><a href="./" class="m-nav__link"><span class="m-nav__link-text">{'Dashboard'|_t}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">
            {if !empty($dashboardMessages)}
                <div class="m-section">
                    <div class="m-section__content">
                        {foreach $dashboardMessages as $message}
                            <div class="m-alert m-alert--icon m-alert--air alert alert-{$message.type} alert-dismissible fade show" role="alert">
                                <div class="m-alert__icon">
                                    {if $message.type == "primary"}<i class="la la-question-circle"></i>{/if}
                                    {if $message.type == "secondary" || $message.type == "brand" || $message.type == "info"}<i class="la la-info-circle"></i>{/if}
                                    {if $message.type == "success"}<i class="la la-check-circle"></i>{/if}
                                    {if $message.type == "warning"}<i class="la la-exclamation-circle"></i>{/if}
                                    {if $message.type == "danger"}<i class="la la-times-circle"></i>{/if}
                                </div>
                                <div class="m-alert__text">
                                    {$message.message}
                                </div>
                                <div class="m-alert__close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="{'Close'|_t}"></button>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            {/if}

            {foreach $dashboardCollections as $collectionName => $dashboardCollection}
                {$daoCollection = InnyCMS::getCollectionDao($collectionName)}
                {$metadata = json_decode($daoCollection->metadata,true)}
                <!--begin::Portlet-->
                <div class="m-portlet m-portlet--creative m-portlet--bordered-semi">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h2 class="m-portlet__head-label m-portlet__head-label--primary"><span>{$metadata.name}</span></h2>
                                <small>{$metadata.help|default:""}</small>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                {if $dashboardCollection.actions.create == "true"}
                                    <li class="m-portlet__nav-item"><a href="{collectionUrl($collectionName,"add")}" class="m-portlet__nav-link m-portlet__nav-link--icon"><i class="la la-plus-circle"></i></a></li>
                                {/if}
                                {if $dashboardCollection.actions.list == "true"}
                                    <li class="m-portlet__nav-item"><a href="{collectionUrl($collectionName,"list")}" class="m-portlet__nav-link m-portlet__nav-link--icon"><i class="la la-list"></i></a></li>
                                {/if}
                            </ul>
                        </div>
                    </div>
                    {if isset($dashboardCollection.documents)}
                        {foreach $dashboardCollection.documents as $field => $values}
                            {foreach $values as $k => $value}
                                {$daoDocument = InnyCMS::getAllDocumentsByField($field,$value,$collectionName)}
                                {$daoDocument->limit(100)}{$trash = $daoDocument->find()}
                                {while $daoDocument->fetch()}
                                    <div class="m-portlet__head" style="height:100%;">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon"><i class="flaticon-mark"></i></span>
                                                <h3 class="m-portlet__head-text d-block mt-3 mb-3">
                                                    {foreach $metadata.listingFields as $field => $value}
                                                        {if isset($value.fields)}
                                                            {$key = key($value.fields)}
                                                            <b>{$field}:</b> {$daoDocument->$key}<br/>
                                                        {/if}
                                                    {/foreach}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="m-portlet__head-tools">
                                            <ul class="m-portlet__nav">
                                                {if $dashboardCollection.actions.read == "true"}<li class="m-portlet__nav-item"><a href="{collectionURL($collectionName,"view",$daoDocument->public_id)}" class="m-portlet__nav-link m-portlet__nav-link--icon"><i class="la la-eye"></i></a></li>{/if}
                                                {if $dashboardCollection.actions.update == "true" || $dashboardCollection.actions.draft == "true"}<li class="m-portlet__nav-item"><a href="{collectionURL($collectionName,"edit",$daoDocument->public_id)}" class="m-portlet__nav-link m-portlet__nav-link--icon"><i class="la la-edit"></i></a></li>{/if}
                                                {if $dashboardCollection.actions.delete == "true"}<li class="m-portlet__nav-item"><a href="{collectionURL($collectionName,"delete",$daoDocument->public_id)}" class="m-portlet__nav-link m-portlet__nav-link--icon"><i class="la la-trash"></i></a></li>{/if}
                                            </ul>
                                        </div>
                                    </div>
                                {/while}
                            {/foreach}
                            <div class="m-portlet__body"></div>
                        {/foreach}
                    {/if}
                </div>
                <!--end::Portlet-->
            {/foreach}
        </div>
    </div>
</div>
<!-- end::Body -->
{include file="footer.tpl"}
