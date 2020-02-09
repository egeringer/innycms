<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-responsive"></i></span>
                        <h3 class="m-portlet__head-text">Edit Site | {$daoSite->name} ({$daoSite->public_id})</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                {if !empty($prevData)}<h6 class="text-danger">There was an problem with the previous input. Check empty fields. Please try again.</h6><hr/>{/if}
                <form class="container-fluid" action="{createSingleResourceUrl("edit","site",$daoSite->id_site)}" method="post">
                    <div class="row">
                        {foreach $fields as $k => $v}
                            {if !in_array($k,$keys) && !in_array($k,$hiddenFields)}
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="{$k}Id"><strong>{$k|replace:"_":" "|capitalize}</strong></label>
                                        <input type="text" class="form-control" id="{$k}Id" name="{$k}" placeholder="" value="{if isset($prevData[$k])}{$prevData[$k]}{else}{$daoSite->$k}{/if}" required/>
                                    </div>
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr/>
                            <a role="button" href="{createSingleResourceUrl("edit","site",$daoSite->id_site)}" title="Reset Form" class="btn btn-danger float-right ml-2 mb-2"><span><i class="fa fa-times"></i> <span class="d-none d-sm-inline">Reset Form</span></span></a>
                            <button type="submit" title="Edit Site" class="btn btn-success float-right ml-2"><span><i class="fa fa-edit"></i> <span class="d-none d-sm-inline">Edit Site</span></span></button>
                            <a role="button" href="{createSingleResourceUrl("view","site",$daoSite->id_site)}" title="Back" class="btn btn-info float-right ml-2 mb-2"><span><i class="fa fa-eye"></i> <span class="d-none d-sm-inline">View</span></span></a>
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