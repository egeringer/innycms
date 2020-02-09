{include file="header.tpl" section="bucket"}
{dk_include file="css/bucket-tags.css"}
<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
    {include file="menu-aside.tpl" section="bucket"}
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
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="./bucket?id={$smarty.get.id}&action=tags" class="m-nav__link"><span class="m-nav__link-text">{'Add and remove tags from'|_t} {$daoBucket->name}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">

            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-folder"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                {$daoBucket->name}
                            </h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="m-form m-form--fit m-form--label-align-right">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <div class="col-12">
                                <input type="text" class="tagsinput" value="{InnyCMS::getBucketItemTags($daoBucket)}"  id="tags-{$daoBucket->id_bucket}" placeholder="{'Add tags'|_t}..."/>
                                <div class="m-separator m-separator--space m-separator--dashed"></div>
                            </div>
                            <div class="col-12 col-sm-6">
                                {include file="bucket-item-preview.tpl"}
                            </div>
                            <div class="col-12 col-sm-6">
                                <b>{"Name"|_t}:</b> {$daoBucket->name}<br/>
                                <b>{"File Url"|_t}:</b> {InnyCMS::getSiteUrl()}{InnyCMS::getBucketFileUrl($daoBucket,"full")}<br/>
                                <b>{"Size"|_t}:</b> {Denko::bytesToFriendlyUnit({$daoBucket->size})}<br/>
                                <b>{"Mime"|_t}:</b> {$daoBucket->mime}<br/>
                                <b>{"Type"|_t}:</b> {$daoBucket->type}<br/>
                                <b>{"Count"|_t}:</b> {$daoBucket->count}<br/>
                                <b>{"Created"|_t}:</b> {$daoBucket->aud_ins_date}<br/>
                                {if $daoBucket->aud_ins_date != $daoBucket->aud_upd_date}<b>{"Modified"|_t}:</b> {$daoBucket->aud_upd_date}<br/>{/if}
                                <b>{"Tags"|_t}:</b> {InnyCMS::getBucketItemTags($daoBucket)}<br/>
                                <b>{"Id"|_t}:</b> {$daoBucket->id_bucket}<br/>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <a class="btn btn-secondary" title="{'Back'|_t}" href="./bucket"><span><i class='fa fa-reply'></i>&nbsp;{"Back"|_t}</span></a>
                                <a class="btn btn-info" title="{'View'|_t} {$daoBucket->name}" href="{InnyBucket::getBucketActionUrl($daoBucket,'view')}"><span><i class='fa fa-eye'></i>&nbsp;{"View"|_t}</span></a>
                                <a class="btn btn-success" title="{'Add Tags'|_t} {$daoBucket->name}" href="{InnyBucket::getBucketActionUrl($daoBucket,'tags')}"><span><i class='fa fa-tags'></i>&nbsp;{"Add Tags"|_t}</span></a>
                                <a class="btn btn-primary" title="{'Download'|_t} {$daoBucket->name}" href="./{InnyCMS::getBucketFileUrl($daoBucket,"download")}"><span><i class='fa fa-cloud-download'></i>&nbsp;{"Download"|_t}</span></a>
                                {if $daoBucket->count == "0"}<a class="btn btn-danger" href="#" data-name="{$daoBucket->name}" data-id="{$daoBucket->id_bucket}" data-hash="{substr($daoBucket->hash,0,5)}" data-from="bucket" title="{'Delete'|_t} {$daoBucket->name}" data-toggle="modal" data-target="#deleteItem"><span><i class='fa fa-trash'></i>&nbsp;{"Delete"|_t}</span></a>{/if}
                            </div>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Portlet-->

            {include file="bucket-common-footer.tpl"}

        </div>
    </div>
</div>
<!-- end::Body -->
{include file="footer.tpl" script="bucket-tags"}