<b>{$header}</b>

<div class="row" {*id="m_sortable_portlets"*}>
    {foreach $files as $key => $file}
        {$daoBucket = InnyCMS::getBucketDao($file)}
        {include file="bucket-portlet.tpl" sortable="false" removable="false" field=$field}
    {/foreach}
    {*
    <!-- begin:Empty Portlet: sortable porlet required for each columns! -->
    <div class="m-portlet m-portlet--sortable-empty"></div>
    <!--end::Empty Portlet-->
    *}
</div>

<style>
    .portlet-bucket {
        float:left;
        width: calc(100% - 20px);
        margin:10px;
    }

    @media (min-width: 576px) {
        .portlet-bucket {
            float:left;
            width: calc(50% - 20px);
            margin:10px;
        }
    }

    @media (min-width: 1440px) {
        .portlet-bucket {
            float: left;
            width: calc(33% - 20px);
            margin: 10px;
        }
    }

</style>