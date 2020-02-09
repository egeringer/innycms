<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-users"></i></span>
                        <h3 class="m-portlet__head-text">Change User Password | {$daoUser->lastname}, {$daoUser->name} ({$daoUser->username})</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <form action="{createSingleResourceUrl("pass","user",$daoUser->id_user)}" method="post">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="newPassId"><strong>New Pass</strong></label>
                                <input type="text" class="form-control" id="newPassId" name="newpass" placeholder="" value="" required/>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="newPassRepeatId"><strong>Repeat New Pass</strong></label>
                                <input type="text" class="form-control" id="newPassRepeatId" name="newpassrepeat" placeholder="" value="" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr/>
                            <a role="button" href="{createSingleResourceUrl("pass","user",$daoUser->id_user)}" title="Reset Form" class="btn btn-danger float-right ml-2 mb-2"><span><i class="fa fa-times"></i> <span class="d-none d-sm-inline">Reset Form</span></span></a>
                            <button type="submit" title="Change Password" class="btn btn-success float-right ml-2"><i class="fa fa-asterisk"></i> <span class="d-none d-sm-inline">Change Password</span></button>
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