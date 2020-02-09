<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="fa fa-boxes"></i></span>
                        <h3 class="m-portlet__head-text">List Collections {if $daoSite}| {$daoSite->name} ({$daoSite->public_id}){/if}</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body m-portlet__body--no-padding">

                <div class="table-responsive" style="min-height:500px;">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                        <tr>
                            {if !$daoSite}<th scope="col">Site</th>{/if}
                            <th scope="col">Name</th>
                            <th scope="col">Creation User</th>
                            <th scope="col">Update User</th>
                            <th scope="col" class="text-center">Creation Date</th>
                            <th scope="col" class="text-center">Update Date</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        {while $daoCollection->fetch()}
                            <tr>
                                {if !$daoSite}<td>{$daoCollection->site_name}</td>{/if}
                                <td>{$daoCollection->name}</td>
                                <td>{$daoCollection->aud_ins_user}</td>
                                <td>{$daoCollection->aud_upd_user}</td>
                                <td class="text-center">{$daoCollection->aud_ins_date}</td>
                                <td class="text-center">{$daoCollection->aud_upd_date}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="{createSingleResourceUrl("view","collection",$daoCollection->id_collection)}"><i class="fa fa-eye"></i> View Collection</a>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("edit","collection",$daoCollection->id_collection)}"><i class="fa fa-edit"></i> Edit Collection</a>
                                            <a class="dropdown-item" href="#" onclick="confirmAction('delete','collection','{$daoCollection->name}','{createSingleResourceUrl("delete","collection",$daoCollection->id_collection)}');"><i class="fa fa-trash-alt"></i> Delete Collection</a>
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