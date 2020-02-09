toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "1200",
    "hideDuration": "500",
    "timeOut": "1500",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$("#itemForm").on("submit",function(e){
    mApp.block("#main_portlet",{});
    e.preventDefault();
    if (typeof CKEDITOR !== 'undefined') { for (instance in CKEDITOR.instances) { CKEDITOR.instances[instance].updateElement(); } }
    $("#itemForm .m-form__help").each(function(){
        $(this).html($(this).data("original-text"));
    });
    $("#itemForm .form-group").removeClass("has-danger").addClass("has-success");
    $("#itemForm .m-form__help").removeClass("form-control-feedback");
    var data = $(this).serialize();
    $.ajax({
        method: requestMethod,
        url: collectionRequestUrl,
        data: data
    }).done(function( response ) {
        var data = JSON.parse(response);
        if (data.status === "0") {
            toastr.error(data.message);
            var errores = data.error;
            if(errores){
                var first = Object.keys(errores)[0];
                $("html, body").animate({ scrollTop: $('.form-group-'+first).offset().top - 90}, 500);
                $.each(errores,function(field,value){
                    // Mark errors on general text fields
                    $(".form-group-"+field).removeClass("has-success").addClass("has-danger");
                    if(!value.msg_list[0]){
                        $.each(value.msg_list, function (subfield, subvalue) {
                            // Mark errors on language text fields
                            $("#help-" + field + "_" + subfield).html(subvalue[0]).addClass('form-control-feedback');
                            $(".form-group-" + field + "_" + subfield).removeClass("has-success").addClass("has-danger");
                        });
                    }else{
                        $("#help-"+field).html(value.msg_list[0]).addClass('form-control-feedback');
                    }
                });
            }
        }else{
            $('#itemForm').data('serialize',$('#itemForm').serialize()); // On load save form current state
            if(data.hasOwnProperty("redirect")) {
                setTimeout(function () {
                    window.location.href = data.redirect;
                }, 2000);
            }
            toastr.success(data.message, "Success!");
        }
        mApp.unblock("#main_portlet");
    });
});

$('#itemForm').data('serialize',$('#itemForm').serialize()); // On load save form current state

$(window).bind('beforeunload', function(e){
    if($('#itemForm').serialize()!=$('#itemForm').data('serialize'))return true;
    else e=null; // i.e; if form state change show warning box, else don't show it.
});

$('#confirmDeleteItem').on('show.bs.modal', function(e) {
    mApp.block("#main_portlet",{});
    $("#modal-item-data").html($(e.relatedTarget).data('id'));
    var id = $(e.relatedTarget).data('id');
    var button = $(this).find('.btn-danger');
    button.unbind();
    button.on("click",function(e){
        e.preventDefault();
        var url = itemDeleteUrl+id;
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === "0") {
                    toastr.error(data.message, "Error!");
                }else{
                    toastr.success(data.message, "Success!");
                    $(window).unbind('beforeunload');
                    setTimeout(function() {
                        window.location.href = data.redirect;
                    }, 2000);
                }
            }
        });
        $("#confirmDeleteItem").modal('toggle');
    });
}).on('show.bs.modal', function(e) {
    mApp.unblock("#main_portlet");
});

$('#confirmDiscardDraft').on('show.bs.modal', function(e) {
    mApp.block("#main_portlet",{});
    $("#modal-item-data").html($(e.relatedTarget).data('id'));
    var id = $(e.relatedTarget).data('id');
    var button = $(this).find('.btn-warning');
    button.unbind();
    button.on("click",function(e){
        e.preventDefault();
        var url = itemDiscardDraftUrl+id;
        $.ajax({
            url: url,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === "0") {
                    toastr.error(data.message, "Error!");
                }else{
                    toastr.success(data.message, "Success!");
                    $(window).unbind('beforeunload');
                    setTimeout(function() {
                        window.location.href = data.redirect;
                    }, 2000);
                }
                $("#confirmDiscardDraft").modal('toggle');
            }
        });
    });
}).on('show.bs.modal', function(e) {
    mApp.unblock("#main_portlet");
});

// Set Up Dropzones
if(typeof InnyDropzoneOptions !== 'undefined'){
    for (var property in InnyDropzoneOptions) {
        if (InnyDropzoneOptions.hasOwnProperty(property)) {
            Dropzone.options[property] = InnyDropzoneOptions[property];
        }
    }
}

// References Select
var Select2= {
    init:function() {
        $(".m-select2").select2({
            placeholder:"Click an item from the list to select it",
            allowClear:!0,
            ajax: {
                url: function(){
                    var collectionName = $(this).data("collection-name");
                    var fieldName = $(this).data("field-name");
                    var keyField = $(this).data("key-field");
                    return "./ws-"+collectionName+"?field="+fieldName+"&keyField="+keyField;
                },
                dataType:"json",
                delay:250,
                data:function(e) {
                    return {
                        q: e.term, page: e.page
                    }
                },
                processResults:function(e, t) {
                    return {
                        results: [
                            {
                                text: "Search results",
                                children: e.documents
                            }
                        ]
                    };
                },
                cache:!0
            },
            escapeMarkup:function(e) {
                return e
            },
            minimumInputLength:0,
            templateResult:function(e) {
                if(e.loading) return e.text;
                var t="<div class='select2-result-repository clearfix'><div class='select2-result-repository__meta'><div class='select2-result-repository__title'>"+e.text+"</div>";
                return e.description&&(t+="<div class='select2-result-repository__description'>"+e.text+"</div>"),
                        t
            }
        });
    }
};

jQuery(document).ready(function() {
    Select2.init()
});

//var PortletDraggable={init:function(){$(".m_sortable_portlets").sortable({connectWith:".m-portlet__head",items:".m-portlet",opacity:.8,handle:".m-portlet__head",coneHelperSize:!0,placeholder:"m-portlet--sortable-placeholder",forcePlaceholderSize:!0,tolerance:"pointer",helper:"clone",tolerance:"pointer",forcePlaceholderSize:!0,helper:"clone",cancel:".m-portlet--sortable-empty",revert:250,update:function(e,t){t.item.prev().hasClass("m-portlet--sortable-empty")&&t.item.prev().before(t.item)}})}};jQuery(document).ready(function(){PortletDraggable.init()});

function copyStringToClipboard (str) {
    // Create new element
    var el = document.createElement('textarea');
    // Set value (string to be copied)
    el.value = str;
    // Set non-editable to avoid focus and move outside of view
    el.setAttribute('readonly', '');
    el.style = {position: 'absolute', left: '-9999px'};
    document.body.appendChild(el);
    // Select text inside element
    el.select();
    // Copy text to clipboard
    document.execCommand('copy');
    // Remove temporary element
    document.body.removeChild(el);
}

function manageFileUpload(field,responseText){
    var response = JSON.parse(responseText);
    var fileBucketId = response.hash;
    var alreadyPlacedFilesIds = document.getElementById(field).value;
    var alreadyPlacedFilesIdsArray = [];
    if (alreadyPlacedFilesIds.length !== 0) alreadyPlacedFilesIdsArray = alreadyPlacedFilesIds.split(",");
    var maxLength = document.getElementById(field).getAttribute("data-max");
    if(alreadyPlacedFilesIdsArray.length >= maxLength){
        alert("You have already reached the limit of "+maxLength+" files in this field.")
    }else{
        if (alreadyPlacedFilesIdsArray.length === 0) alreadyPlacedFilesIdsArray = [fileBucketId];
        else alreadyPlacedFilesIdsArray.push(fileBucketId);
        document.getElementById(field).value = alreadyPlacedFilesIdsArray.toString();
        $('#'+field+'-portlets').append(response.html);
    }
}

function manageFileRemove(field,hash,from){
    var alreadyPlacedFilesIds = document.getElementById(field).value;
    var alreadyPlacedFilesIdsArray = [];
    if(alreadyPlacedFilesIds.length !== 0) alreadyPlacedFilesIdsArray = alreadyPlacedFilesIds.split(",");
    var index = alreadyPlacedFilesIdsArray.indexOf(hash);
    if(index > -1) { alreadyPlacedFilesIdsArray.splice(index, 1); document.getElementById(field).value = alreadyPlacedFilesIdsArray.toString(); }
    $("#"+field+"-"+hash).remove();
}

function savepublished(){
    $("#innyaddeditflag").val("saveunpublished");
    $("#innystatusflag").val("published");
    $("#itemForm").submit();
}

function saveunpublished(){
    $("#innyaddeditflag").val("saveunpublished");
    $("#innystatusflag").val("unpublished");
    $("#itemForm").submit();
}

function savedraft(){
    $("#innyaddeditflag").val("savedraft");
    $("#innystatusflag").val("draft");
    $("#itemForm").submit();
}

function saveandnew(){
    $("#innyaddeditflag").val("saveandnew");
    $("#itemForm").submit();
}

function saveandview(){
    $("#innyaddeditflag").val("saveandview");
    $("#itemForm").submit();
}

function saveandclose(){
    $("#innyaddeditflag").val("saveandclose");
    $("#itemForm").submit();
}

function saveandclone(){
    $("#innyaddeditflag").val("saveandclone");
    $("#itemForm").submit();
}

function cancel(){
    $(window).unbind('beforeunload');
    location.reload();
}
