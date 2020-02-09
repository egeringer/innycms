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
    Denko::redirect('logout');
}
if(InnyCMS::isLockedUser()){
    Denko::redirect('lock');
}
################################################################################
################################################################################
$daoUser = Denko::daoFactory('innydb_user');
$daoUser->get(InnyCMS::getUserId());
$originalDaoUser=clone($daoUser);
$error = array();
$ok = "";
$tab = (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) ? $_REQUEST['action'] : "profile";
################################################################################
################################################################################
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
    switch ($_REQUEST['action']){
        case 'profile':
            if(isset($_REQUEST['name']) && !empty($_REQUEST['name']) && isset($_REQUEST['lastname']) && !empty($_REQUEST['lastname']) && isset($_REQUEST['email']) && !empty($_REQUEST['email'])){
                $daoUser->name = $_REQUEST['name'];
                $daoUser->lastname = $_REQUEST['lastname'];
                $daoUser->email = $_REQUEST['email'];
                $daoUser->update($originalDaoUser);
                InnyCMS::updateUserSession();
                $ok = _t("New Personal Information Saved");
            }else{
                $error[] = _t("All fields are required.");
            }
            break;
        case 'password':
            if(!isset($_REQUEST['old_pass']) || empty($_REQUEST['old_pass'])){
                $error[] = _t("Old Password is required.");
            }

            if(!isset($_REQUEST['new_pass']) || empty($_REQUEST['new_pass'])){
                $error[] = _t("New Password is required.");
            }

            if(!isset($_REQUEST['new_pass_repeat']) || empty($_REQUEST['new_pass_repeat'])){
                $error[] = _t("New Password Repeat is required.");
            }

            if(isset($_REQUEST['new_pass_repeat']) && isset($_REQUEST['new_pass_repeat']) && $_REQUEST['new_pass_repeat'] != $_REQUEST['new_pass']){
                $error[] = _t("New Passwords do not match.");
            }

            if(empty($error)){
                if(!password_verify($_REQUEST['old_pass'], $daoUser->password)) {
                    $error[] = _t("Old Password is incorrect.");
                }else{
                    $daoUser->password = $_REQUEST['new_pass'];
                    $result = $daoUser->update($originalDaoUser);
                    $ok = _t("Password Changed Correctly");
                }

            }

            break;
    }
}

$sidebar = InnyCMS::getSidebarMetadata();
$userInfo = $daoUser->toArray();
# Creo la instancia de Smarty y cargo el outputfilter para enviar los includes en el header
$smarty = new Smarty();
$smarty->loadFilter('output','dk_include');
$smarty->assign('sidebarUI',$sidebar);
$smarty->assign('userInfo',$userInfo);
$smarty->assign('error',$error);
$smarty->assign('ok',$ok);
$smarty->assign("tab",$tab);
$smarty->display('profile.tpl');
################################################################################
