<?php
/**
 * @copyright 2007-2017 Dokko Group
 * @author Dokko Group <info at dokkogroup dot com dot ar>
 */
include_once 'common.php';
require_once '../commons/inny/InnyCMS.php';
################################################################################
################################################################################
if(!InnyCMS::isLoggedUser()){
    Denko::redirect('./logout');
}
# Chequeo si estoy bloqueado
if(InnyCMS::isLockedUser()){
    Denko::redirect('./lock');
}
InnyCMS::logOutSite();
################################################################################
################################################################################
if(isset($_GET['public_id']) && !empty($_GET['public_id'])){
    InnyCMS::setSite($_GET['public_id']);
    Denko::redirect('./home');
}
################################################################################
################################################################################
# Creo la instancia de Smarty y cargo el outputfilter para enviar los includes en el header
$smarty = new Smarty();
$smarty->assign("sites",InnyCMS::getUserSites());
$smarty->loadFilter('output','dk_include');
$smarty->display('choose.tpl');

################################################################################
