{if $daoBucket->type == "image"}
    <img src="./{InnyCMS::getBucketFileUrl($daoBucket,"preview")}" width="100%" />
{elseif $daoBucket->type == "flash"}
    <object class="center-block" width="100%" height="auto">
        <param name="movie" value="./{InnyCMS::getBucketFileUrl($daoBucket,"preview")}">
        <embed src="./{InnyCMS::getBucketFileUrl($daoBucket,"preview")}"></embed>
    </object>
{else}
    {$iconClass="fa fa-file-o"}

    {if $daoBucket->type == "video"}
        {$iconClass="fa fa-file-video-o"}
    {elseif $daoBucket->type == "audio"}
        {$iconClass="fa fa-file-audio-o"}
    {elseif $daoBucket->type == "pdf"}
        {$iconClass="fa fa-file-pdf-o"}
    {elseif $daoBucket->type == "document"}
        {$iconClass="fa fa-file-word-o"}
    {elseif $daoBucket->type == "spreadsheet"}
        {$iconClass="fa fa-file-excel-o"}
    {elseif $daoBucket->type == "presentation"}
        {$iconClass="fa fa-file-powerpoint-o"}
    {/if}

    <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
        <div class="m-portlet__body m-portlet__body--no-padding">
            <div class="m-widget17">
                <div class="m-widget17__stats">
                    <div class="m-widget17__items m-widget17__items-col1">
                        <div class="m-widget17__item" style="margin-top:0;-webkit-box-shadow: 0 0 0 0 #000000;-moz-box-shadow: 0 0 0 0 #000000;box-shadow: 0 0 0 0 #000000;">
                            <span class="m-widget17__icon"><i class="{$iconClass} m--font-brand"></i></span>
                            <span class="m-widget17__subtitle">{$daoBucket->type|capitalize}</span>
                            <span class="m-widget17__desc">{"Used %s times"|_t:$daoBucket->count}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}