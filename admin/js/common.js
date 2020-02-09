function confirmAction(action, resourceType, resourceName, url){
    if (confirm("Are you sure you want to "+action+" "+resourceType+" "+resourceName+"?")) {
        window.location.href = url;
    }
}