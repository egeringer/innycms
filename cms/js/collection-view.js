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

$('#confirmDeleteItem').on('show.bs.modal', function(e) {
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
                    setTimeout(function() {
                        window.location.href = data.redirect;
                    }, 2000);
                }
            }
        });
        $("#confirmDeleteItem").modal('toggle');
    });
});

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