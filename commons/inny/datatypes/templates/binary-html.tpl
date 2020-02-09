<div class='form-group m-form__group form-group-{$field}'>
    <label class='control-label' for='{$field}'><strong>{$fieldName} <span class='text-danger'>{$requiredText}</span></strong></label>
    <input type='hidden' class='form-control' id='{$field}' name='{$field}' value='{$value}' aria-describedby='help-{$field}' data-max="{$quantity}" />
    <div class="m-dropzone dropzone" id="{$field}-dropzone" action="./bucket?portlet-upload">
        <div class="m-dropzone__msg dz-message needsclick">
            <h3 class="m-dropzone__msg-title">{"Drop files here or click to upload."|_t}</h3>
            <span class="m-dropzone__msg-desc">{"Repeated files will be skipped."|_t}</span>
        </div>
    </div>
    <span id='help-{$field}' data-original-text='{$helpText}' class='help-block'>{$helpText}</span>
    <div class="row {*m_sortable_portlets*}" id="{$field}-portlets">
        {foreach $files as $key => $file}
            {$daoBucket = InnyCMS::getBucketDao($file)}
            {include file="bucket-portlet.tpl" sortable="false" removable="true" field=$field}
            <!--end::Portlet-->
        {/foreach}
        {*<!-- begin:Empty Portlet: sortable porlet required for each columns! -->
        <div class="m-portlet m-portlet--sortable-empty"></div>
        <!--end::Empty Portlet-->*}
    </div>
</div>

<style>
    .portlet-bucket {
        float:left;
        width: calc(100% - 20px);
        margin:10px;
    }

    @media (min-width: 576px) {
        .portlet-bucket {
            float:left;
            width: calc(50% - 20px);
            margin:10px;
        }
    }

    @media (min-width: 992px) {
        .portlet-bucket {
            float: left;
            width: calc(33% - 20px);
            margin: 10px;
        }
    }

    @media (min-width: 1200px) {
        .portlet-bucket {
            float: left;
            width: calc(25% - 20px);
            margin: 10px;
        }
    }
</style>

<script type="text/javascript">
    var dropzoneName = "{lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",str_replace("-"," ",$field)))))}Dropzone";
    if(typeof InnyDropzoneOptions === 'undefined') var InnyDropzoneOptions = [];

    var dropzoneConfig = {
        url: "./bucket?portlet-upload&field={$field}",
        paramName: "file",
        preventDuplicates: false,
        maxFiles: '{$quantity}',
        addRemoveLinks: true,
        dictRemoveFile: "Remove file",
        dictCancelUpload: "Cancel Upload",
        dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
        init: function(){
            this.on("complete", function(file){
                if(file.xhr && file.xhr.status === 200) {
                    var _this = this;
                    setTimeout(function(){
                        manageFileUpload('{$field}', file.xhr.responseText);
                        _this.removeFile(file);
                    },2000);
                }
            });
        }
    };
    InnyDropzoneOptions[dropzoneName] = dropzoneConfig;
</script>