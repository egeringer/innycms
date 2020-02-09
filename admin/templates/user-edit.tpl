<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-users"></i></span>
                        <h3 class="m-portlet__head-text">Edit User | {$daoUser->lastname}, {$daoUser->name} ({$daoUser->username})</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                {if !empty($prevData)}<h6 class="text-danger">There was an problem with the previous input. Check empty fields or duplicated username. Please try again.</h6><hr/>{/if}
                <form action="{createSingleResourceUrl("edit","user",$daoUser->id_user)}" method="post">
                    <div class="row">
                        {foreach $fields as $k => $v}
                            {if !in_array($k,$keys) && !in_array($k,$hiddenFields)}
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="{$k}Id"><strong>{$k|replace:"_":" "|capitalize}</strong></label>
                                        <input type="text" class="form-control" id="{$k}Id" name="{$k}" placeholder="" value="{if isset($prevData[$k])}{$prevData[$k]}{else}{$daoUser->$k}{/if}" required/>
                                    </div>
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr/>
                            <a role="button" href="{createSingleResourceUrl("edit","user",$daoUser->id_user)}" title="Reset Form" class="btn btn-danger float-right ml-2 mb-2"><span><i class="fa fa-times"></i> <span class="d-none d-sm-inline">Reset Form</span></span></a>
                            <button type="submit" title="Edit" class="btn btn-success float-right ml-2"><i class="fa fa-edit"></i> <span class="d-none d-sm-inline">Edit User</span></button>
                            <a role="button" href="{createSingleResourceUrl("view","user",$daoUser->id_user)}" title="Back" class="btn btn-secondary float-right ml-2 mb-2"><i class="fa fa-arrow-left"></i> <span class="d-none d-sm-inline">Back</span></a>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!--end::Portlet-->
    </div>
</div>

<!--End::Section-->