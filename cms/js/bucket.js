/************************************************************************************
 ************************************************************************************
 ************************************************************************************
 *************************** Dropzone
 *************************************************************************************
 *************************************************************************************
 *************************************************************************************/

if($("#bucket-zone").length){
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#bucket-zone",{ dictDefaultMessage:'' });
    myDropzone.on("complete", function(file) {
        setTimeout(function(){
            myDropzone.removeFile(file);
            $('.divheader').before(file.xhr.responseText);
        },2000);
    });
}

/************************************************************************************
 ************************************************************************************
 ************************************************************************************
 *************************** Bootstrap Select
 *************************************************************************************
 *************************************************************************************
 *************************************************************************************/

//== Class definition
var BootstrapSelect = function () {
    //== Private functions
    var build = function () {
        $('.m_selectpicker').selectpicker();
    };

    return {
        // public functions
        init: function() {
            build();
        }
    };
}();

jQuery(document).ready(function() {
    BootstrapSelect.init();
});

/************************************************************************************
 ************************************************************************************
 ************************************************************************************
 *************************** Confirm Delete
 *************************************************************************************
 *************************************************************************************
 *************************************************************************************/

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

$('#deleteItem').on('show.bs.modal', function(e) {
    $("#modal-item-data").html($(e.relatedTarget).data('name'));
    var id = $(e.relatedTarget).data('id');
    var hash = $(e.relatedTarget).data('hash');
    var from = $(e.relatedTarget).data('from');
    var button = $(this).find('.btn-danger');
    var container = $(e.relatedTarget).parent().parent();
    button.unbind();
    button.on("click",function(e){
        e.preventDefault();
        var url = "./delete-bucket!"+id+"x"+hash+"?from="+from;
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === "0") {
                    toastr.error(data.message, "Error!");
                }else{
                    toastr.success(data.message, "Success!");
                    if(data.hasOwnProperty('redirect')) { setTimeout(function () { window.location.href = data.redirect; }, 2000); }
                    else { container.remove(); }
                }
            }
        });
        $("#deleteItem").modal('toggle');
    });
});

$("#cleanBucketSubmit").on("click",function(e){
    e.preventDefault();
    var pass = $("#cleanBucketPass").val();
    var url = "bucket?action=cleanBucket&password="+pass;
    $.ajax({
        url: url,
        type: 'DELETE',
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "0") {
                toastr.error(data.message, "Error!");
            }else{
                toastr.success(data.message, "Success!");
            }
            if(data.hasOwnProperty("redirect")) { setTimeout(function () { window.location.href = data.redirect; }, 2000); }
        }
    });
    $("#cleanBucket").modal('toggle');
});

$("#emptyBucketSubmit").on("click",function(e){
    e.preventDefault();
    var pass = $("#emptyBucketPass").val();
    var url = "bucket?action=emptyBucket&password="+pass;
    $.ajax({
        url: url,
        type: 'DELETE',
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "0") {
                toastr.error(data.message, "Error!");
            }else{
                toastr.success(data.message, "Success!");
            }
            if(data.hasOwnProperty("redirect")) { setTimeout(function () { window.location.href = data.redirect; }, 2000); }
        }
    });
    $("#emptyBucket").modal('toggle');
});

$("BUTTON[type=reset]").on("click",function(e){ e.preventDefault(); window.location.href = window.location.pathname; return false;});