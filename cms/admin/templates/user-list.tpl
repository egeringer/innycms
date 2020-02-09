<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-users"></i></span>
                        <h3 class="m-portlet__head-text">List Users</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body m-portlet__body--no-padding">

                <div class="table-responsive" style="min-height:500px;">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">Enabled</th>
                            <th scope="col" class="text-center">Role</th>
                            <th scope="col">Username</th>
                            <th scope="col">Name</th>
                            <th scope="col" class="text-center">Creation Date</th>
                            <th scope="col" class="text-center">Update Date</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        {while $daoUser->fetch()}
                            <tr>
                                <td class="text-center">{if $daoUser->status == "1"}<i class="fa fa-toggle-on text-success" title="Enabled"></i>{elseif $daoUser->status == "2"}<i class="fa fa-toggle-off text-danger" title="Disabled"></i>{/if}</td>
                                <td class="text-center">{if $daoUser->role == "sysadmin"}<i class="la la-user-plus text-success" title="SysAdmin"></i>{else}<i class="la la-user text-primary" title="SiteAdmin"></i>{/if}</td>
                                <td>{$daoUser->username}</td>
                                <td>{$daoUser->lastname}, {$daoUser->name}</td>
                                <td class="text-center">{$daoUser->aud_ins_date}</td>
                                <td class="text-center">{$daoUser->aud_upd_date}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item"><i class="fa fa-user"></i> <strong>{$daoUser->username}</strong></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("view","user",$daoUser->id_user)}"><i class="fa fa-eye"></i> View</a>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("edit","user",$daoUser->id_user)}"><i class="fa fa-edit"></i> Edit</a>
                                            <a class="dropdown-item" href="{createSingleResourceUrl("pass","user",$daoUser->id_user)}"><i class="fa fa-asterisk"></i> Change Password</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{createMultipleResourceUrl("list","user",$daoUser->id_user,"site")}"><i class="fa fa-globe"></i> Relate Sites</a>
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