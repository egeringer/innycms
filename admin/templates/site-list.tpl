<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-responsive"></i></span>
                        <h3 class="m-portlet__head-text">List Sites</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body m-portlet__body--no-padding">

                <div class="table-responsive" style="min-height:500px;">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Creation User</th>
                            <th scope="col" class="text-center">Creation Date</th>
                            <th scope="col" class="text-center">Update Date</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        {while $daoSite->fetch()}
                            <tr>
                                <td class="text-center">
                                    {if $daoSite->status == "1"}<i class="fa fa-power-off text-success" title="Enabled"></i>{elseif $daoSite->status == "2"}<i class="fa fa-power-off text-danger" title="Disabled"></i>{elseif $daoSite->status == "3"}<i class="fa fa-power-off text-warning" title="Under maintenance"></i>{/if}
                                </td>
                                <td>{$daoSite->public_id}</td>
                                <td>{$daoSite->name}</td>
                                <td>{$daoSite->aud_ins_user}</td>
                                <td class="text-center">{$daoSite->aud_ins_date}</td>
                                <td class="text-center">{$daoSite->aud_upd_date}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="{$daoSite->url}"><i class="fa fa-globe"></i> Visit Site</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("view","site",$daoSite->id_site)}"><i class="fa fa-eye"></i> View Site Details</a>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("edit","site",$daoSite->id_site)}"><i class="fa fa-edit"></i> Edit Site Details</a>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("sidebar","site",$daoSite->public_id)}"><i class="fa fa-list"></i> Edit Sidebar</a>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("list","collection",$daoSite->public_id)}"><i class="fa fa-boxes"></i> List Collections</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        {/while}
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!--end::Portlet-->
    </div>
</div>

<!--End::Section-->