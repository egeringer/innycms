<?php
require_once 'common.php';
################################################################################
# Crea la instancia de Smarty
$smarty = new Smarty();
$smarty->loadFilter('output','dk_include');
$smarty->assign('errorCode',$_GET['code']);

switch($_GET['code']){
    case 403:
        $smarty->assign('message','You do not have permission to access the requested resource.');
        break;

    case 404:
        $smarty->assign('message','File not found on this server.');
        break;

    case 500:
        $smarty->assign('message','An unexpected error occurred while processing your request.<br/>Try again later.');
        break;
}

# Muestra el template
$smarty->display('inny_error.tpl');
################################################################################