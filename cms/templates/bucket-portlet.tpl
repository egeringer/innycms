{if $daoBucket}
    {$iconClass="fa fa-file-o"}
    {if $daoBucket->type == "image"}
        {$iconClass="fa fa-file-image"}
    {elseif $daoBucket->type == "video"}
        {$iconClass="fa fa-file-video"}
    {elseif $daoBucket->type == "audio"}
        {$iconClass="fa fa-file-audio"}
    {elseif $daoBucket->type == "pdf"}
        {$iconClass="fa fa-file-pdf"}
    {elseif $daoBucket->type == "document"}
        {$iconClass="fa fa-file-word"}
    {elseif $daoBucket->type == "spreadsheet"}
        {$iconClass="fa fa-file-excel"}
    {elseif $daoBucket->type == "presentation"}
        {$iconClass="fa fa-file-powerpoint"}
    {/if}
    <!--begin::Section-->
    <div class="m-section {if $sortable|default:"false" == "true"}m-portlet--sortable{/if} portlet-bucket" id="{$field}-{$daoBucket->hash}">
        <div class="m-section__content">
            <!--begin::Demo-->
            <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                <div class="m-demo__preview">
                    <div class="m-btn-group m-btn-group--pill btn-group mb-3" role="group" aria-label="Button group with nested dropdown">
                        {if $discardable|default:"false" !== "false" || $removable|default:"false" !== "false"}
                            <button type="button" class="m-btn btn btn-secondary" onclick="manageFileRemove('{$field}','{$daoBucket->hash}')"><i class="la la-times"></i></button>
                        {else}
                            <button type="button" class="m-btn btn btn-secondary" onclick="copyStringToClipboard('{InnyCMS::getSiteUrl()}{InnyCMS::getBucketFileUrl($daoBucket,"full")}');"><i class="la la-link"></i></button>
                        {/if}
                        <div class="m-btn-group btn-group" role="group">
                            <button id="btnGroupDrop{$field}{$daoBucket->hash}" type="button" class="btn btn-secondary m-btn m-btn--pill-last dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {'Actions'|_t}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop{$field}{$daoBucket->hash}">
                                <a class="dropdown-item" href="{InnyBucket::getBucketActionUrl($daoBucket,"view")}"><i class="la la-eye"></i> {'View'|_t}</a>
                                <a class="dropdown-item" href="javascript:return false;" onclick="copyStringToClipboard('{InnyCMS::getSiteUrl()}{InnyCMS::getBucketFileUrl($daoBucket,"full")}');"><i class="la la-link"></i> {'Copy Link'|_t}</a>
                                <a class="dropdown-item" href="{InnyCMS::getSiteUrl()}{InnyCMS::getBucketFileUrl($daoBucket,"download")}"><i class="la la-download"></i> {'Download'|_t}</a>
                            </div>
                        </div>
                    </div>
                    <h3 class="m-section__heading m-0">
                        {if $removable|default:"false" === "false"}<i class="{$iconClass}"></i> {$daoBucket->name}{/if}
                    </h3>
                    <span class="m-section__sub">
                        {if $daoBucket->type == "image"}
                            <img src="./{InnyCMS::getBucketFileUrl($daoBucket,"preview")}" width="100%"/>
                        {else}
                            <p>{$daoBucket->name}</p>
                            <i class="text-center {$iconClass}" style="font-size:17rem;"></i>
                        {/if}
                    </span>

                </div>
            </div>
            <!--end::Demo-->
        </div>
    </div>
    <!--end::Section-->

{/if}
