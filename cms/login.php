<?php
/**
 * Project: Inny Clientes
 * File: index.php
 *
 * @copyright 2007-2017 Dokko Group
 * @author Dokko Group <info at dokkogroup dot com dot ar>
 */
include_once 'common.php';
require_once '../commons/inny/InnyCMS.php';
################################################################################
################################################################################
################################################################################
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && !empty($_GET['action'])) {
    switch ($_GET['action']) {
        case 'logout':
            InnyCMS::logOut();
            Denko::redirect("./login");
            break;
        case 'lock':
            if(!InnyCMS::isLoggedUser()){ Denko::redirect('./logout'); }
            InnyCMS::setLockedUser();
            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
            $smarty->display('lock-page.tpl');
            break;
        case 'login':
            if(InnyCMS::isLoggedUser()){ Denko::redirect('./home'); }
            $stylesPath = InnyCMS::getCustomizationPath('css')."/login.css";
            if(file_exists($stylesPath)) $stylesPath = str_replace("web/","",$stylesPath);
            else $stylesPath = null;
            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
            $smarty->assign("stylesPath",$stylesPath);
            $smarty->display('login-page.tpl');
            break;
    }
}
################################################################################
################################################################################
################################################################################
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && !empty($_POST['action'])){
    switch ($_POST['action']){
        case 'login':
            $remember = (isset($_POST['remember'])) ? $_POST['remember'] : 0;
            $logged = InnyCMS::logIn($_POST['username'],$_POST['password'],$remember);
            echo json_encode($logged);
            break;
        case 'unlock':
            $unlocked = InnyCMS::unlockUser($_POST['password']);
            echo json_encode($unlocked);
            break;
        case 'recover':
            $recover = InnyCMS::recoverPassword($_POST['username']);
            echo json_encode($recover);
            break;
    }
    exit;
}
################################################################################
################################################################################
################################################################################
