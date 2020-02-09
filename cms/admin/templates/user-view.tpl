<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-users"></i></span>
                        <h3 class="m-portlet__head-text">View User | {$daoUser->lastname}, {$daoUser->name} ({$daoUser->username})</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <div class="row">
                    {foreach $fields as $k => $v}
                        {if !in_array($k,$hiddenFields)}
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="{$k}Id"><strong>{$k|replace:"_":" "|capitalize}</strong></label>
                                    <input type="text" class="form-control" id="{$k}Id" name="{$k}" placeholder="" value="{$daoUser->$k}" readonly/>
                                </div>
                            </div>
                        {/if}
                    {/foreach}
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr/>
                        <a role="button" onclick="confirmAction('delete','user','{$daoUser->username}','{createSingleResourceUrl("delete","user",$daoUser->id_user)}');" title="Delete" class="btn btn-danger text-white float-right ml-2 mb-2"><i class="fa fa-trash-alt"></i> <span class="d-none d-sm-inline">Delete</span></a>
                        {if $daoUser->status == "1"}
                            <a role="button" href="{createSingleResourceUrl("disable","user",$daoUser->id_user)}" title="Disable" class="btn btn-warning float-right ml-2 mb-2"><i class="fa fa-toggle-off"></i> <span class="d-none d-sm-inline">Disable</span></a>
                        {elseif $daoUser->status == "2"}
                            <a role="button" href="{createSingleResourceUrl("enable","user",$daoUser->id_user)}" title="Enable" class="btn btn-warning float-right ml-2 mb-2"><i class="fa fa-toggle-on"></i> <span class="d-none d-sm-inline">Enable</span></a>
                        {/if}
                        {if $daoUser->role == "siteadmin"}
                            <a role="button" href="{createSingleResourceUrl("assignsysadmin","user",$daoUser->id_user)}" title="Assign SysAdmin" class="btn btn-warning float-right ml-2 mb-2"><i class="la la-user-plus"></i> <span class="d-none d-sm-inline">Assign SysAdmin</span></a>
                        {else}
                            <a role="button" href="{createSingleResourceUrl("assignsiteadmin","user",$daoUser->id_user)}" title="Assign SiteAdmin" class="btn btn-warning float-right ml-2 mb-2"><i class="la la-user"></i> <span class="d-none d-sm-inline">Assign SiteAdmin</span></a>
                        {/if}
                        <a role="button" href="{createMultipleResourceUrl("list","user",$daoUser->id_user,"site")}" title="Relate Sites" class="btn btn-primary float-right ml-2 mb-2"><i class="fa fa-globe"></i> <span class="d-none d-sm-inline">Relate Sites</span></a>
                        <a role="button" href="{createSingleResourceUrl("pass","user",$daoUser->id_user)}" title="Change Password" class="btn btn-success float-right ml-2 mb-2"><i class="fa fa-asterisk"></i> <span class="d-none d-sm-inline">Change Password</span></a>
                        <a role="button" href="{createSingleResourceUrl("edit","user",$daoUser->id_user)}" title="Edit" class="btn btn-success float-right ml-2 mb-2"><i class="fa fa-edit"></i> <span class="d-none d-sm-inline">Edit</span></a>
                        <a role="button" href="{createSingleResourceUrl("list","user")}" title="Back" class="btn btn-secondary float-right ml-2 mb-2"><i class="fa fa-arrow-left"></i> <span class="d-none d-sm-inline">Back</span></a>
                    </div>
                </div>

            </div>
        </div>

        <!--end::Portlet-->
    </div>
</div>

<!--End::Section-->