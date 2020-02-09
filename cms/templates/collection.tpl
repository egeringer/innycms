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
                                                    {'Collection Quick Actions'|_t}
                                                </span>
                                            </li>
                                            {if InnyCMS::checkPermission("collection","create",$daoCollection->name)}
                                                <li class="m-nav__item">
                                                    <a href="{collectionURL($daoCollection->name,'add')}" class="m-nav__link">
                                                        <i class="m-nav__link-icon fa fa-plus"></i>
                                                        <span class="m-nav__link-text">
                                                            {'Add'|_t} {$collectionMetadata.item}
                                                        </span>
                                                    </a>
                                                </li>
                                            {/if}
                                            {if InnyCMS::checkPermission("collection","import",$daoCollection->name)}
                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                <li class="m-nav__item">
                                                    <a href="#importCollection" role="button" data-toggle="modal" class="m-nav__link">
                                                        <i class="m-nav__link-icon fa fa-cloud-upload-alt"></i>
                                                        <span class="m-nav__link-text">
                                                            {'Import'|_t} {$collectionMetadata.name}
                                                        </span>
                                                    </a>
                                                </li>
                                            {/if}
                                            {if InnyCMS::checkPermission("collection","export",$daoCollection->name)}
                                                <li class="m-nav__item">
                                                    <a href="#exportCollection" role="button" data-toggle="modal" class="m-nav__link">
                                                        <i class="m-nav__link-icon fa fa-cloud-download-alt"></i>
                                                        <span class="m-nav__link-text">
                                                            {'Export'|_t} {$collectionMetadata.name}
                                                        </span>
                                                    </a>
                                                </li>
                                            {/if}
                                            {if InnyCMS::checkPermission("collection","upload",$daoCollection->name)}
                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                <li class="m-nav__item">
                                                    <a href="#uploadCollection" role="button" data-toggle="modal" class="m-nav__link">
                                                        <i class="m-nav__link-icon fa fa-upload"></i>
                                                        <span class="m-nav__link-text">
                                                            {'Upload'|_t} {$collectionMetadata.name}
                                                        </span>
                                                    </a>
                                                </li>
                                            {/if}
                                            {if InnyCMS::checkPermission("collection","download",$daoCollection->name)}
                                                <li class="m-nav__item">
                                                    <a href="#downloadCollection" role="button" data-toggle="modal" class="m-nav__link">
                                                        <i class="m-nav__link-icon fa fa-download"></i>
                                                        <span class="m-nav__link-text">
                                                            {'Download'|_t} {$collectionMetadata.name}
                                                        </span>
                                                    </a>
                                                </li>
                                            {/if}
                                            {if InnyCMS::checkPermission("collection","empty",$daoCollection->name)}
                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                <li class="m-nav__item">
                                                    <a href="#emptyCollection" role="button" data-toggle="modal" class="m-nav__link">
                                                        <i class="m-nav__link-icon fa fa-trash"></i>
                                                        <span class="m-nav__link-text">
                                                            {'Empty'|_t} {$collectionMetadata.name}
                                                        </span>
                                                    </a>
                                                </li>
                                            {/if}
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
                                    {if InnyCMS::checkPermission("collection","create",$daoCollection->name)}
                                        <a href="{collectionURL($daoCollection->name,'add')}" class="btn btn-brand m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                                            <span><i class="la la-plus"></i><span>{_t('Add')} {$collectionMetadata.item}</span></span>
                                        </a>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <!--begin: Search Form -->
                            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                                <div class="row align-items-center">
                                    <div class="col-xl-8 order-2 order-xl-1">
                                        <div class="form-group m-form__group row align-items-center">
                                            <div class="col-md-4">
                                                <div class="m-form__group m-form__group--inline">
                                                    <div class="m-form__label">
                                                        <label>{'Status'|_t}:</label>
                                                    </div>
                                                    <div class="m-form__control">
                                                        <select class="form-control m-bootstrap-select" id="m_form_status">
                                                            <option value="">{'All'|_t}</option>
                                                            <option value="0">{'Drafts'|_t}</option>
                                                            <option value="1">{'Published'|_t}</option>
                                                            <option value="2">{'Unpublished'|_t}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="d-md-none m--margin-bottom-10"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="m-input-icon m-input-icon--left">
                                                    <input type="text" class="form-control m-input" placeholder="{'Search...'|_t}" id="generalSearch">
                                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                                        <span><i class="la la-search"></i></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--end: Search Form -->

                            <!--begin: Datatable -->
                            <div class="m_datatable" id="collection_data_{$daoCollection->name}"></div>
                            <!--end: Datatable -->
                        </div>
                    </div>

                    <!--end::Portlet-->
                </div>
            </div>

            {if InnyCMS::checkPermission("collection","empty",$daoCollection->name)}
                <div id="emptyCollection" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="emptyCollection" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{'Confirm Empty'|_t} {$collectionMetadata.name}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <p> {'You are about to empty this collection.'|_t} </p>
                                <p> {'<strong>All documents</strong> on this collection will be <strong>permanently deleted</strong>.'|_t} </p>
                                <p> {'This action <strong>cannot be undone</strong>.'|_t} </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                                <a class="btn btn-danger" id="confirm-emptyCollection" href="#">{'Empty Collection'|_t}</a>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}

            {if InnyCMS::checkPermission("collection","export",$daoCollection->name)}
                <div id="exportCollection" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exportCollection" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{'Export Collection'|_t} {$collectionMetadata.name}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <p> {'You are about to export this collection.'|_t} </p>
                                <p> {'You can choose to export the entire collection in <strong>CSV</strong> or <strong>JSON</strong> format. This will be helpful if you want to move information to another InnyCMS instance or edit this collection on an external software.'|_t} </p>
                                <p> {'If you are not sure what to choose, please ask your <strong>webmaster</strong>.'} </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                                <a class="btn btn-primary" href="{collectionURL($daoCollection->name,'exportcsv')}">{'Export'} CSV</a>
                                <a class="btn btn-primary" href="{collectionURL($daoCollection->name,'exportjson')}">{'Export'} JSON</a>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}

            {if InnyCMS::checkPermission("collection","download",$daoCollection->name)}
                <div id="downloadCollection" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="downloadCollection" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{'Download Collection'|_t} {$collectionMetadata.name}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <p> {'You are about to download this collection.'|_t} </p>
                                <p> {'You will be able to use this information in any external software in <strong>CSV</strong> format.'|_t} </p>
                                <p> {'If you are not sure what to choose, please ask your <strong>webmaster</strong>.'|_t} </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                                <a class="btn btn-primary" href="{collectionURL($daoCollection->name,'download')}">{'Download'|_t}</a>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}

            {if InnyCMS::checkPermission("collection","import",$daoCollection->name)}
                <div id="importCollection" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="importCollection" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{'Import Collection'|_t} {$collectionMetadata.name}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <p> {'You are about to import data to this collection.'|_t} </p>
                                <p> {'Make sure you import a clean and genuine copy of the collection in order to preserve data consistency.'|_t} </p>
                                <p> {'Do this under your own risk.'|_t} </p>
                                <form id="importCollectionForm" action="{collectionURL($daoCollection->name,'import')}" method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="col-9">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="fileName" name="file">
                                                <label class="custom-file-label" for="fileName">{'Choose file'|_t}</label>
                                            </div>
                                        </div>
                                        <div class="col-3 text-right">
                                            <button type="submit" class="btn btn-primary">{'Submit'|_t}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}

            {if InnyCMS::checkPermission("collection","create",$daoCollection->name)}
                <div id="uploadCollection" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadCollection" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{'Import Collection'|_t} {$collectionMetadata.name}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <p> {'You are about to upload new data to this collection.'|_t} </p>
                                <p> {'Make sure you import a <strong>CSV</strong> file with the right information fields.'|_t} </p>
                                <p> {'Do this under your own risk.'|_t} </p>
                                <form id="uploadCollectionForm" action="{collectionURL($daoCollection->name,'upload')}" method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="col-9">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="fileName" name="file">
                                                <label class="custom-file-label" for="fileName">{'Choose file'|_t}</label>
                                            </div>
                                        </div>
                                        <div class="col-3 text-right">
                                            <button type="submit" class="btn btn-primary">{'Submit'|_t}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}

            {if InnyCMS::checkPermission("collection","delete",$daoCollection->name)}
                <div id="deleteItem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteItem" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{'Confirm Document Deletion'|_t}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <p> {'You are about to delete a document from this collection.'|_t} </p>
                                <p> {'This means that <strong id="modal-item-data"></strong> will be <strong>permanently deleted</strong>.'|_t} </p>
                                <p> {'This action <strong>cannot be undone</strong>.'|_t} </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                                <a class="btn btn-danger" href="#">{'Delete Document'|_t}</a>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
        </div>
    </div>
</div>

{$listingFields = InnyCMS::getCollectionListingFields($daoCollection->name)}

<script type="text/javascript">
    var collectionBase = "{$daoCollection->name}";
    var dataSourceUrl = "{collectionURL($daoCollection->name,'draw')}";
    var dataColumns = [
        {
            field: "position",
            title: "#",
            sortable: 1,
            width: 40,
            textAlign: 'center',
            template: function(e) {
                var a = {
                        1: {
                            title: "{'Published'|_t}", class: "m-badge--success"
                        },
                        2: {
                            title: "{'Unpublished'|_t}", class: " m-badge--danger"
                        }
                    }
                ;
                return e.position+'<br/><span class="m-badge ' + a[e.status].class + ' m-badge--dot"></span>'
            }
        },
        {foreach $listingFields as $key=>$field}
        {
            field: "{$key|replace:" ":""}",
            title: "{$key}",
            {if isset($field.width)}width: {$field.width},{/if}
            {if isset($field.textAlign)}textAlign: "{$field.textAlign}",{/if}
            {if isset($field.type)}type: "{$field.type}",{/if}
            filterable: false, // disable or enable filtering
            {if isset($field.responsive)}{if isset($field.responsive.visible)}responsive: { visible: "{$field.responsive.visible}" },{elseif isset($field.responsive.hidden)}responsive: { hidden: "{$field.responsive.hidden}" }, {/if} {/if}
            {if isset($field.sortable)}sortable: {$field.sortable},{/if}
        },
        {/foreach}
        {
            field: "{'Actions'|_t}",
            width: 70,
            title: "{'Actions'|_t}",
            sortable: false,
            overflow: 'visible',
            textAlign: 'center',
            template: function (row, index, datatable) {
                var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';
                var publishStr = (row.status === "1") ? '<a class="dropdown-item" href="#" data-action="unpublish" data-id="'+row.public_id+'"><i class="la la-times"></i> {'Unpublish'|_t}</a>' : '<a class="dropdown-item" href="#" data-action="publish" data-id="'+row.public_id+'"><i class="la la-check"></i> {'Publish'|_t}</a>';
                return '<div class="dropdown '+ dropup +'">'+
                            '<a href="./edit-'+collectionBase+'!'+row.public_id+'" data-action="edit" data-id="'+row.public_id+'" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="{'Edit'|_t}"><i class="la la-edit"></i></a>'+
                            '<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown"><i class="la la-ellipsis-h"></i></a>'+
                            '<div class="dropdown-menu dropdown-menu-right">'+
                                '<a class="dropdown-item" href="./view-'+collectionBase+'!'+row.public_id+'" data-action="view" data-id="'+row.public_id+'"><i class="la la-eye"></i> {'View'|_t}</a>'+
                                '<a class="dropdown-item" href="./edit-'+collectionBase+'!'+row.public_id+'" data-action="edit" data-id="'+row.public_id+'"><i class="la la-edit"></i> {'Edit'|_t}</a>'+
                                publishStr+
                                '<a class="dropdown-item" href="./clone-'+collectionBase+'!'+row.public_id+'" data-action="clone" data-id="'+row.public_id+'"><i class="la la-copy"></i> {'Duplicate'|_t}</a>'+
                                '<a class="dropdown-item" href="#" data-action="moveup" data-id="'+row.public_id+'"><i class="la la-angle-up"></i> {'Move Up'|_t}</a>'+
                                '<a class="dropdown-item" href="#" data-action="movedown" data-id="'+row.public_id+'"><i class="la la-angle-down"></i> {'Move Down'|_t}</a>'+
                                '<a class="dropdown-item" href="#" data-action="delete" data-id="'+row.public_id+'" data-toggle="modal" data-target="#deleteItem"><i class="la la-trash"></i> {'Delete'|_t}</a>'+
                            '</div>'+
                        '</div>';
            }
        }
    ];
</script>
<!-- end::Body -->
{include file="footer.tpl" script="collection-listing"}