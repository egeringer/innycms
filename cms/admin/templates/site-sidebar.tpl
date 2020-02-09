<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-responsive"></i></span>
                        <h3 class="m-portlet__head-text">Edit Site Sidebar | {$daoSite->name} ({$daoSite->public_id})</h3>
                    </div>
                </div>
            </div>

            <form class="m-form" id="m_form" method="post">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-12">
                            <div id="m_repeater_1">
                                <div class="form-group m-form__group row">
                                    <div data-repeater-list="sidebar" class="col-12">
                                        {$collections = array()}
                                        {while $daoCollection->fetch()}{$collections[] = $daoCollection->name}{/while}
                                        {if !empty($sidebar)}
                                            {foreach $sidebar as $key => $item}
                                                {include file="site-sidebar-item.tpl" daoCollection=$daoCollection}
                                            {/foreach}
                                        {else}
                                            {include file="site-sidebar-item.tpl" item=array()}
                                        {/if}
                                    </div>
                                </div>
                                <div class="m-form__group form-group row">
                                    <div class="col-12">
                                        <div data-repeater-create="" class="btn btn-primary m-btn m-btn--icon m-btn--wide"><span><i class="fa fa-plus"></i><span>Add Item</span></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-12">
                                <a role="button" href="{createSingleResourceUrl("sidebar","site",$daoSite->public_id)}" title="Reset Form" class="btn btn-danger float-right ml-2 mb-2"><span><i class="fa fa-times"></i> <span class="d-none d-sm-inline">Reset Form</span></span></a>
                                <button type="submit" class="btn btn-success float-right ml-2 mb-2"><span><i class="fa fa-save"></i> <span class="d-none d-sm-inline">Save</span></span></button>
                                <a role="button" href="{createSingleResourceUrl("view","site",$daoSite->id_site)}" title="Back" class="btn btn-info float-right ml-2 mb-2"><span><i class="fa fa-eye"></i> <span class="d-none d-sm-inline">View</span></span></a>
                                <a role="button" href="{createSingleResourceUrl("list","site")}" title="Back" class="btn btn-default float-right ml-2 mb-2 text-dark"><span><i class="fa fa-arrow-left"></i> <span class="d-none d-sm-inline">Back</span></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--end::Portlet-->

        <!--begin::Portlet-->
        <div class="m-portlet" id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="flaticon-responsive"></i></span>
                        <h3 class="m-portlet__head-text">JSON Site Sidebar</h3>
                    </div>
                </div>
            </div>

            <form class="m-form" id="m_form_json" method="post">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-12">
                            <div class='form-group m-form__group form-group-json'>
                                <label class='control-label' for='json'><strong>Edit JSON <span class='text-danger'>*</span></strong></label>
                                <textarea class='form-control' rows='20' id="json" name="json">{json_encode($sidebar)}</textarea>
                                <span id='help-json' class='m-form__help'>Please do not edit this field if you are not sure what you are doing or you are not familiar with JSON format.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <pre id="result"></pre>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-12">
                                <a role="button" href="{createSingleResourceUrl("sidebar","site",$daoSite->public_id)}" title="Back" class="btn btn-danger float-right ml-2 mb-2"><span><i class="fa fa-times"></i> <span class="d-none d-sm-inline">Reset Form</span></span></a>
                                <button type="submit" class="btn btn-success float-right ml-2 mb-2" id="button"><span><i class="fa fa-save"></i> <span class="d-none d-sm-inline">Save</span></span></button>
                                <a role="button" href="{createSingleResourceUrl("view","site",$daoSite->id_site)}" title="Back" class="btn btn-info float-right ml-2 mb-2"><span><i class="fa fa-eye"></i> <span class="d-none d-sm-inline">View</span></span></a>
                                <a role="button" href="{createSingleResourceUrl("list","site")}" title="Back" class="btn btn-default float-right ml-2 mb-2 text-dark"><span><i class="fa fa-arrow-left"></i> <span class="d-none d-sm-inline">Back</span></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <script src="js/jsonlint.js"></script>
            <script>
                window.onload = function () {
                    var ugly = document.getElementById('json').value;
                    var obj = JSON.parse(ugly);
                    var pretty = JSON.stringify(obj, undefined, 4);
                    document.getElementById('json').value = pretty;

                    document.getElementById("button").onclick = function (e) {
                        e.preventDefault();
                        $(".form-group-json").removeClass("has-danger").addClass("has-success");
                        $(".form-group-json .m-form__help").removeClass("form-control-feedback");
                        try {
                            var result = jsonlint.parse(document.getElementById("json").value);
                            if (result) {
                                $("#help-json").html("JSON is valid!");
                                document.getElementById("json").value = JSON.stringify(result, null, "  ");
                                $("#m_form_json").submit();
                            }else{
                                $(".form-group-json").removeClass("has-success").addClass("has-danger");
                                $("#help-json").html("JSON is not valid!").addClass('form-control-feedback');
                            }
                        } catch(e) {
                            $(".form-group-json").removeClass("has-success").addClass("has-danger");
                            $("#help-json").html(e).addClass('form-control-feedback');
                        }
                    };
                }
            </script>
        </div>

        <!--end::Portlet-->
    </div>
</div>

<!--End::Section-->