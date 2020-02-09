<?php
require_once 'common.php';
// InnyCMS and InnyCMS Sys Admin Dashboard share users.
// Users have a role which can be one of the following:
// "sysadmin" or "siteadmin"
################################################################################
################################################################################
################################################################################
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && !empty($_GET['action'])) {
    switch ($_GET['action']) {
        case 'logout':
            InnyCMS::logOutAdmin();
            Denko::redirect('./login');
            break;
        case 'lock':
            if(!InnyCMS::isLoggedUser()){ Denko::redirect('./logout'); }
            InnyCMS::setLockedUser();
            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
            $smarty->display('lock-page.tpl');
            break;
        case 'login':
            if(InnyCMS::isLoggedAdmin()){ Denko::redirect('./dashboard'); }
            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
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
            $logged = InnyCMS::logInAdmin($_POST['username'],$_POST['password'],$remember);
            if($logged['status'] == "1") Denko::redirect('./dashboard');
            else Denko::redirect('./login?error');
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
