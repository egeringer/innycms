var DatatableResponsiveColumns = {
    init:function() {
        var t;
        t=$(".m_datatable").mDatatable(
            {
                data: {
                    type:"remote",
                    source: {
                        read: {
                            url: dataSourceUrl,
                        }
                    },
                    pageSize:10,
                    serverPaging:!0,
                    serverFiltering:!0,
                    serverSorting:!0
                },
                layout: {
                    theme: "default",
                    class: "",
                    scroll: !1,
                    footer: !1
                },
                sortable:!0,
                pagination:!0,
                toolbar: {
                    items: {
                        pagination: {
                            pageSizeSelect: [5, 10, 20, 30, 50, 100]
                        }
                    }
                },
                search: {
                    input: $("#generalSearch")
                },
                columns: dataColumns
            }
        ),
        $("#m_form_status").on("change", function() {
                t.search($(this).val(), "status")
            }
        ),
        $("#m_form_status").selectpicker()
    }
};

jQuery(document).ready(function() {
    DatatableResponsiveColumns.init()
});

$('#deleteItem').on('show.bs.modal', function(e) {
    $("#modal-item-data").html($(e.relatedTarget).data('id'));
    mApp.block("#main_portlet",{});
    var id = $(e.relatedTarget).data('id');
    var button = $(this).find('.btn-danger');
    button.unbind();
    button.on("click",function(e){
        e.preventDefault();
        var url = "delete-"+collectionBase+"!"+id;
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === "0") {
                    toastr.error(data.message, "Error!");
                }else{
                    toastr.success(data.message, "Success!");
                    $('.m_datatable').mDatatable('reload');
                }
            }
        });
        $("#deleteItem").modal('toggle');
    });
}).on('hide.bs.modal', function(e) {
    mApp.unblock("#main_portlet");
});

$('#confirm-emptyCollection').on("click",function(e){
    e.preventDefault();
    mApp.blockPage();
    var url = "delete-"+collectionBase+"!empty";
    $.ajax({
        url: url,
        type: 'DELETE',
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "0") {
                toastr.error(data.message, "Error!");
            }else{
                toastr.success(data.message, "Success!");
                $('.m_datatable').mDatatable('reload');
            }
            mApp.unblockPage();
        }
    });
    $("#emptyCollection").modal('toggle');
});

$(document).on("click", ".m_datatable A[data-action=moveup]", function(e){
    e.preventDefault();
    mApp.block("#main_portlet",{});
    var url = "moveup-"+collectionBase+"!"+$(this).data("id");

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "0") {
                toastr.error(data.message, "Error!");
            }else{
                toastr.success(data.message, "Success!");
                $('.m_datatable').mDatatable('reload');
            }
            mApp.unblock("#main_portlet");
        }
    });
});

$(document).on("click", ".m_datatable A[data-action=movedown]", function(e){
    e.preventDefault();
    mApp.block("#main_portlet",{});
    var url = "movedown-"+collectionBase+"!"+$(this).data("id");

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "0") {
                toastr.error(data.message, "Error!");
            }else{
                toastr.success(data.message, "Success!");
                $('.m_datatable').mDatatable('reload');
            }
            mApp.unblock("#main_portlet");
        }
    });
});

$(document).on("click", ".m_datatable A[data-action=publish]", function(e){
    e.preventDefault();
    mApp.block("#main_portlet",{});
    var url = "publish-"+collectionBase+"!"+$(this).data("id");

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "0") {
                toastr.error(data.message, "Error!");
            }else{
                toastr.success(data.message, "Success!");
                $('.m_datatable').mDatatable('reload');
            }
            mApp.unblock("#main_portlet");
        }
    });
});

$(document).on("click", ".m_datatable A[data-action=unpublish]", function(e){
    e.preventDefault();
    mApp.block("#main_portlet",{});
    var url = "unpublish-"+collectionBase+"!"+$(this).data("id");

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "0") {
                toastr.error(data.message, "Error!");
            }else{
                toastr.success(data.message, "Success!");
                $('.m_datatable').mDatatable('reload');
            }
            mApp.unblock("#main_portlet");
        }
    });
});

$("#uploadCollectionForm").on("submit",function(e){
    e.preventDefault();
    var data = new FormData(this);
    $.ajax({
        method: "POST",
        url: "upload-"+collectionBase,
        data: data,
        processData: false,
        contentType: false
    }).done(function( response ) {
        var data = JSON.parse(response);
        if (data.status === "0") {
            toastr.error(data.message);
        }else{
            toastr.success(data.message, "Success!");
            $("#uploadCollection").modal('toggle');
            $('.m_datatable').mDatatable('reload');
        }
        if(data.hasOwnProperty("redirect")) {
            setTimeout(function () {
                window.location.href = data.redirect;
            }, 2000);
        }
    });
});

$("#importCollectionForm").on("submit",function(e){
    e.preventDefault();
    var data = new FormData(this);
    $.ajax({
        method: "POST",
        url: "import-"+collectionBase,
        data: data,
        processData: false,
        contentType: false
    }).done(function( response ) {
        var data = JSON.parse(response);
        if (data.status === "0") {
            toastr.error(data.message);
        }else{
            toastr.success(data.message, "Success!");
            $("#importCollection").modal('toggle');
            $('.m_datatable').mDatatable('reload');
        }
        if(data.hasOwnProperty("redirect")) {
            setTimeout(function () {
                window.location.href = data.redirect;
            }, 2000);
        }
    });
});

$('#uploadCollection').on('show.bs.modal', function(e) {
    mApp.blockPage();
}).on('hide.bs.modal', function(e) {
    mApp.unblockPage();
});

$('#downloadCollection').on('show.bs.modal', function(e) {
    mApp.blockPage();
}).on('hide.bs.modal', function(e) {
    mApp.unblockPage();
});

$('#importCollection').on('show.bs.modal', function(e) {
    mApp.blockPage();
}).on('hide.bs.modal', function(e) {
    mApp.unblockPage();
});

$('#exportCollection').on('show.bs.modal', function(e) {
    mApp.blockPage();
}).on('hide.bs.modal', function(e) {
    mApp.unblockPage();
});