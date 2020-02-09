<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="fa fa-boxes"></i></span>
                        <h3 class="m-portlet__head-text">Add Collection</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                {if !empty($prevData)}<h6 class="text-danger">There was an problem with the previous input. Check empty fields or duplicated username. Please try again.</h6><hr/>{/if}
                <form action="{createSingleResourceUrl("add","collection")}" method="post">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="name"><strong>Name</strong></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="" value="{if isset($prevData['name'])}{$prevData['name']}{/if}" required>
                                <span id='help-json' class='m-form__help'>Only letters and numbers are allowed on this field.</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="name"><strong>Site Name</strong></label>
                                <select class="form-control m-bootstrap-select m_selectpicker select-type" id="site_name" name="site_name" title="Choose one" required>
                                    {while $daoSite->fetch()}
                                        <option value="{$daoSite->public_id}" {if isset($prevData['site_name']) && $prevData['site_name'] == $daoSite->public_id}selected="selected"{/if}>{$daoSite->public_id}</option>
                                    {/while}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr/>
                            <a role="button" href="{createSingleResourceUrl("add","collection")}" title="Reset Form" class="btn btn-danger float-right ml-2 mb-2"><span><i class="fa fa-times"></i> <span class="d-none d-sm-inline">Reset Form</span></span></a>
                            <button type="submit" title="Add User" class="btn btn-success float-right ml-2"><i class="fa fa-plus"></i> <span class="d-none d-sm-inline">Add Collection</span></button>
                            <a role="button" href="{createSingleResourceUrl("list","collection")}" title="Back" class="btn btn-default float-right ml-2 mb-2 text-dark"><span><i class="fa fa-arrow-left"></i> <span class="d-none d-sm-inline">Back</span></span></a>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!--end::Portlet-->
    </div>
</div>

<!--End::Section-->