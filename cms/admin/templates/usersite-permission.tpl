<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-responsive"></i></span>
                        <h3 class="m-portlet__head-text">Edit User Site Permission | {$daoUser->lastname}, {$daoUser->name} ({$daoUser->username}) - {$daoSite->name} ({$daoSite->public_id})</h3>
                    </div>
                </div>
            </div>

            <form class="m-form" id="m_form" method="post">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group m-form__group">
                                <label for="permission">User Site Permission</label>
                                <textarea class="form-control m-input m-input--air" id="permission" name="permission" rows="15">{json_encode(json_decode($permission,true),JSON_PRETTY_PRINT)}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-brand">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--end::Portlet-->

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-responsive"></i></span>
                        <h3 class="m-portlet__head-text">Edit User Site Permission</h3>
                    </div>
                </div>
            </div>

            <form class="m-form" id="m_form" method="post">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-12">
                            <pre>{json_encode(json_decode($permission,true),JSON_PRETTY_PRINT)}</pre>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!--end::Portlet-->
    </div>
</div>

<!--End::Section-->