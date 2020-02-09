<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-users"></i></span>
                        <h3 class="m-portlet__head-text">View User Sites | {$daoUser->lastname}, {$daoUser->name} ({$daoUser->username})</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body m-portlet__body--no-padding">

                {$userSites = InnyCMS::getUserSites($daoUser->id_user)}
                <div class="table-responsive" style="min-height:500px;">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">Current State</th>
                            <th scope="col">Public Id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Url</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        {while $daoSites->fetch()}
                            <tr>
                                <td class="text-center">{if !isset($userSites[$daoSites->id_site])}<i class="fa fa-times text-danger"></i>{else}<i class="fa fa-check text-success"></i>{/if}</td>
                                <td>{$daoSites->public_id}</td>
                                <td>{$daoSites->name}</td>
                                <td>{$daoSites->url}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="{$daoSites->url}"><i class="fa fa-globe"></i> Visit Site</a>
                                            <div class="dropdown-divider"></div>
                                            {if !isset($userSites[$daoSites->id_site])}
                                                <a class="dropdown-item" href="{createMultipleResourceUrl("assign","user",$daoUser->id_user,"site",$daoSites->id_site)}"><i class="fa fa-check"></i> Assign Site</a>
                                            {else}
                                                <a class="dropdown-item" href="{createMultipleResourceUrl("unassign","user",$daoUser->id_user,"site",$daoSites->id_site)}"><i class="fa fa-times"></i> Unassign Site</a>
                                            {/if}
                                            <a class="dropdown-item" href="{createMultipleResourceUrl("permission","user",$daoUser->id_user,"site",$daoSites->id_site)}"><i class="fa fa-user-edit"></i> Edit Permissions</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("view","site",$daoSites->id_site)}"><i class="fa fa-eye"></i> View Site Details</a>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("edit","site",$daoSites->id_site)}"><i class="fa fa-edit"></i> Edit Site Details</a>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("sidebar","site",$daoSites->id_site)}"><i class="fa fa-list"></i> Edit Sidebar</a>
                                            {*
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("list","site",$daoSites->id_site,"user")}"><i class="fa fa-users"></i> View Site Users</a>
                                            *}
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