<!--Begin::Section-->
<div class="row">
    <div class="col-xl-12">

        <!--begin::Portlet-->
        <div class="m-portlet" id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon"><i class="fa fa-boxes"></i></span>
                        <h3 class="m-portlet__head-text">View Collection | {$daoCollection->name} - {$daoCollection->site_name}</h3>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-12">
                        <div class='form-group m-form__group form-group-json'>
                            <label class='control-label' for='json'><strong>View JSON Metadata<span class='text-danger'>*</span></strong></label>
                            <textarea class='form-control' rows='20' id="metadata" disabled="disabled">{$daoCollection->metadata}</textarea>
                            <span id='help-json' class='m-form__help'></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr/>
                        <a role="button" href="#" onclick="confirmAction('delete','collection','{$daoCollection->name}','{createSingleResourceUrl("delete","collection",$daoCollection->id_collection)}');" title="Delete Collection" class="btn btn-danger float-right ml-2 mb-2"><span><i class="fa fa-trash-alt"></i> <span class="d-none d-sm-inline">Delete Collection</span></span></a>
                        <a role="button" href="{createSingleResourceUrl("edit","collection",$daoCollection->id_collection)}" title="Edit Collection" class="btn btn-success float-right ml-2 mb-2"><span><i class="fa fa-edit"></i> <span class="d-none d-sm-inline">Edit Collection</span></span></a>
                    </div>
                </div>
            </div>

            <script src="js/jsonlint.js"></script>
            <script>
                window.onload = function () {
                    var ugly = document.getElementById('metadata').value;
                    var obj = JSON.parse(ugly);
                    var pretty = JSON.stringify(obj, undefined, 4);
                    document.getElementById('metadata').value = pretty;
                }
            </script>
        </div>

        <!--end::Portlet-->
    </div>
</div>

<!--End::Section-->