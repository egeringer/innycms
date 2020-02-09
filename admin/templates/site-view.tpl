<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-responsive"></i></span>
                        <h3 class="m-portlet__head-text">View Site | {$daoSite->name} ({$daoSite->public_id})</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <form class="container-fluid" action="{createSingleResourceUrl("add","site")}" method="post">
                    <div class="row">
                        {foreach $fields as $k => $v}
                            {if !in_array($k,$keys) && !in_array($k,$hiddenFields)}
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="{$k}Id"><strong>{$k|replace:"_":" "|capitalize}</strong></label>
                                        <input type="text" class="form-control" id="{$k}Id" name="{$k}" placeholder="" value="{$daoSite->$k}" readonly/>
                                    </div>
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr/>
                            <a role="button" href="#" onclick="confirmAction('delete','site','{$daoSite->name}','{createSingleResourceUrl("delete","site",$daoSite->id_site)}');" title="Delete Site" class="btn btn-danger float-right ml-2 mb-2"><span><i class="fa fa-trash-alt"></i> <span class="d-none d-sm-inline">Delete</span></span></a>
                            {if $daoSite->status == "1"}
                                <a role="button" href="{createSingleResourceUrl("setmaintenance","site",$daoSite->id_site)}" title="Set Maintenance Mode" class="btn btn-warning float-right ml-2 mb-2"><span><i class="fa fa-power-off"></i> <span class="d-none d-sm-inline">Set Maintenance</span></span></a>
                                <a role="button" href="{createSingleResourceUrl("disable","site",$daoSite->id_site)}" title="Disable Site" class="btn btn-warning float-right ml-2 mb-2"><span><i class="fa fa-toggle-off"></i> <span class="d-none d-sm-inline">Disable</span></span></a>
                            {elseif $daoSite->status == "2"}
                                <a role="button" href="{createSingleResourceUrl("enable","site",$daoSite->id_site)}" title="Enable Site" class="btn btn-warning float-right ml-2 mb-2"><span><i class="fa fa-toggle-on"></i> <span class="d-none d-sm-inline">Enable</span></span></a>
                            {elseif $daoSite->status == "3"}
                                <a role="button" href="{createSingleResourceUrl("unsetmaintenance","site",$daoSite->id_site)}" title="Unset Maintenance Mode" class="btn btn-info float-right ml-2 mb-2"><span><i class="fa fa-bolt"></i> <span class="d-none d-sm-inline">Unset Maintenance</span></span></a>
                            {/if}
                            <a role="button" href="{createSingleResourceUrl("list","collection",$daoSite->public_id)}" title="List Collections" class="btn btn-primary float-right ml-2 mb-2"><span><i class="fa fa-boxes"></i> <span class="d-none d-sm-inline">Collections</span></span></a>
                            <a role="button" href="{createSingleResourceUrl("sidebar","site",$daoSite->public_id)}" title="Edit Sidebar" class="btn btn-primary float-right ml-2 mb-2"><span><i class="fa fa-list"></i> <span class="d-none d-sm-inline">Sidebar</span></span></a>
                            <a role="button" href="{createSingleResourceUrl("edit","site",$daoSite->id_site)}" title="Edit Site" class="btn btn-success float-right ml-2 mb-2"><span><i class="fa fa-edit"></i> <span class="d-none d-sm-inline">Edit</span></span></a>
                            <a role="button" href="{createSingleResourceUrl("list","site")}" title="Back" class="btn btn-default float-right ml-2 mb-2 text-dark"><span><i class="fa fa-arrow-left"></i> <span class="d-none d-sm-inline">Back</span></span></a>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!--end::Portlet-->
    </div>
</div>

<!--End::Section-->