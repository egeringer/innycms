<?php
require_once 'common.php';
// InnyCMS and InnyCMS Sys Admin Dashboard share users.
// Users have a role which can be one of the following:
// "sysadmin" or "siteadmin"
InnyCMS::checkAdminLogIn();
$continue = false;
################################################################################
################################################################################
################################################################################
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
    switch ($_REQUEST['action']){
        case 'add':
            $daoUser = Denko::daoFactory('innydb_user');
            $tableFields = $daoUser->table();
            $tableKeys = $daoUser->keys();
            foreach ($tableFields as $k => $v){
                if(!in_array($k,$tableKeys) && !in_array($k,$hiddenUserAddFields)){
                    if(!empty($_REQUEST[$k]) || $_REQUEST[$k] === "0") $daoUser->$k = $_REQUEST[$k];
                    else $continue = true;
                }
            }

            if(!$continue){
                $id_user = $daoUser->insert();
                if($id_user) Denko::redirect(createSingleResourceUrl("view","user",$id_user));
            }

            $continue = true;
            break;
        case 'edit':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_REQUEST['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $original = clone($daoUser);
            $tableFields = $daoUser->table();
            $tableKeys = $daoUser->keys();
            foreach ($tableFields as $k => $v){
                if(isset($_REQUEST[$k]) && (!empty($_REQUEST[$k]) || $_REQUEST[$k] === "0") && !in_array($k,$tableKeys) && !in_array($k,$hiddenFields)) $daoUser->$k = $_REQUEST[$k];
            }
            if($daoUser->update($original)) Denko::redirect(createSingleResourceUrl("view","user",$_REQUEST['id']));
            $continue = true;
            break;
        case 'pass':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_REQUEST['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $original = clone($daoUser);
            if($_REQUEST['newpass'] === $_REQUEST['newpassrepeat']){
                $daoUser->password = $_REQUEST['newpass'];
                if($daoUser->update($original)) Denko::redirect(createSingleResourceUrl("view","user",$_REQUEST['id']));
            }else{
                $continue = true;
            }
            break;
        case 'permission':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_GET['id_site'];
            if(!$daoSite->find(true)) Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            if($daoSite->status == "0") Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            $daoUserSite = Denko::daoFactory('innydb_user_site');
            $daoUserSite->id_user = $daoUser->username;
            $daoUserSite->id_site = $daoSite->public_id;
            if($daoUserSite->find(true)){
                $jsonArr = json_decode($_REQUEST['permission'],true);
                if($jsonArr){
                    $json = json_encode($jsonArr);
                    $daoUserSite->permission = $json;
                    $daoUserSite->update();
                }
            }else{
                Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            }
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createMultipleResourceUrl("permission","user",$_REQUEST['id'],"site",$_REQUEST['id_site']));
            break;
    }
}
################################################################################
################################################################################
################################################################################
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_REQUEST['action']) && !empty($_REQUEST['action']) || $continue) {
    switch ($_REQUEST['action']) {
        case 'list':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->whereAdd("status > 0");
            $daoUser->orderBy("role desc, username asc");
            $daoUser->find();
            $smarty = new Smarty();
            $smarty->assign("daoUser",$daoUser);
            $smarty->assign("templateFile",'user-list.tpl');
            $smarty->assign("title",'Users');
            $smarty->assign("resource",'user');
            $smarty->display("common.tpl");
            break;
        case 'view':
            $daoUser = Denko::daoFactory('innydb_user');
            $tableFields = $daoUser->table();
            $tableKeys = $daoUser->keys();
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $smarty = new Smarty();
            $smarty->assign("daoUser",$daoUser);
            $smarty->assign("fields",$tableFields);
            $smarty->assign("keys",$tableKeys);
            $smarty->assign("hiddenFields",$hiddenUserViewFields);
            $smarty->assign("templateFile",'user-view.tpl');
            $smarty->assign("title",'Users');
            $smarty->assign("resource",'user');
            $smarty->assign("resourceId",$_GET['id']);
            $smarty->assign("action",'view');
            $smarty->display("common.tpl");
            break;
        case 'edit':
            $daoUser = Denko::daoFactory('innydb_user');
            $tableFields = $daoUser->table();
            $tableKeys = $daoUser->keys();
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $prevData = $continue ? $_POST : array();
            $smarty = new Smarty();
            $smarty->assign("daoUser",$daoUser);
            $smarty->assign("fields",$tableFields);
            $smarty->assign("keys",$tableKeys);
            $smarty->assign("hiddenFields",array_merge($hiddenFields,array("username","role")));
            $smarty->assign("prevData",$prevData);
            $smarty->assign("templateFile",'user-edit.tpl');
            $smarty->assign("title",'Users');
            $smarty->assign("resource",'user');
            $smarty->assign("resourceId",$_GET['id']);
            $smarty->assign("action",'edit');
            $smarty->display("common.tpl");
            break;
        case 'pass':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $smarty = new Smarty();
            $smarty->assign("daoUser",$daoUser);
            $smarty->assign("templateFile",'user-pass.tpl');
            $smarty->assign("title",'Users');
            $smarty->assign("resource",'user');
            $smarty->assign("resourceId",$_GET['id']);
            $smarty->assign("action",'pass');
            $smarty->display("common.tpl");
            break;
        case 'enable':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $original = clone($daoUser);
            $daoUser->status = "1";
            $daoUser->update($original);
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createSingleResourceUrl("list","user"));
            break;
        case 'disable':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $original = clone($daoUser);
            $daoUser->status = "2";
            $daoUser->update($original);
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createSingleResourceUrl("list","user"));
            break;
        case 'assignsiteadmin':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $original = clone($daoUser);
            $daoUser->role = "siteadmin";
            $daoUser->update($original);
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createSingleResourceUrl("list","user"));
            break;
        case 'assignsysadmin':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $original = clone($daoUser);
            $daoUser->role = "sysadmin";
            $daoUser->update($original);
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createSingleResourceUrl("list","user"));
            break;
        case 'add':
            $daoUser = Denko::daoFactory('innydb_user');
            $tableFields = $daoUser->table();
            $tableKeys = $daoUser->keys();
            $prevData = $continue ? $_POST : array();
            $smarty = new Smarty();
            $smarty->assign("fields",$tableFields);
            $smarty->assign("keys",$tableKeys);
            $smarty->assign("hiddenFields",$hiddenUserAddFields);
            $smarty->assign("prevData",$prevData);
            $smarty->assign("templateFile",'user-add.tpl');
            $smarty->assign("title",'Users');
            $smarty->assign("resource",'user');
            $smarty->assign("action",'add');
            $smarty->assign("resourceId",'');
            $smarty->display("common.tpl");
            break;
        case 'delete':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_REQUEST['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $original = clone($daoUser);
            $daoUser->status = "0";
            $daoUser->update($original);
            Denko::redirect(createSingleResourceUrl("list","user"));
            break;
        case 'site':
            $daoUser = Denko::daoFactory('innydb_user');
            $tableFields = $daoUser->table();
            $tableKeys = $daoUser->keys();
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $daoSites = Denko::daoFactory('innydb_site');
            $daoSites->whereAdd("status > 0");
            $daoSites->find();
            $smarty = new Smarty();
            $smarty->assign("daoUser",$daoUser);
            $smarty->assign("daoSites",$daoSites);
            $smarty->assign("templateFile",'user-sites.tpl');
            $smarty->assign("title",'Users');
            $smarty->assign("resource",'user');
            $smarty->assign("resourceId",$_GET['id']);
            $smarty->assign("resource2",'site');
            $smarty->assign("action",'list');
            $smarty->display("common.tpl");
            break;
        case 'assign':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_GET['id_site'];
            if(!$daoSite->find(true)) Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            if($daoSite->status == "0") Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            $daoUserSite = Denko::daoFactory('innydb_user_site');
            $daoUserSite->id_user = $daoUser->username;
            $daoUserSite->id_site = $daoSite->public_id;
            if($daoUserSite->find(true)){
                $daoUserSite->status = "1";
                if(empty($daoUserSite->permission) || $daoUserSite->permission == "[]") $daoUserSite->permission = json_encode(array("role" => "admin","collection" => null));
                $daoUserSite->update();
            }else{
                $daoUserSite->status = "1";
                $daoUserSite->permission = json_encode(array("role" => "admin","collection" => null));
                $daoUserSite->insert();
            }
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            break;
        case 'unassign':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_GET['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_GET['id_site'];
            if(!$daoSite->find(true)) Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            if($daoSite->status == "0") Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            $daoUserSite = Denko::daoFactory('innydb_user_site');
            $daoUserSite->id_user = $daoUser->username;
            $daoUserSite->id_site = $daoSite->public_id;
            if($daoUserSite->find(true)){
                $daoUserSite->status = "0";
                if(empty($daoUserSite->permission) || $daoUserSite->permission == "[]") $daoUserSite->permission = json_encode(array("role" => "admin","collection" => null));
                $daoUserSite->update();
            }else{
                $daoUserSite->status = "0";
                $daoUserSite->permission = json_encode(array("role" => "admin","collection" => null));
                $daoUserSite->insert();
            }
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            break;
        case 'permission':
            $daoUser = Denko::daoFactory('innydb_user');
            $daoUser->id_user = $_REQUEST['id'];
            if(!$daoUser->find(true)) Denko::redirect(createSingleResourceUrl("list","user"));
            if($daoUser->status == "0") Denko::redirect(createSingleResourceUrl("list","user"));
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_REQUEST['id_site'];
            if(!$daoSite->find(true)) Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            if($daoSite->status == "0") Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            $daoUserSite = Denko::daoFactory('innydb_user_site');
            $daoUserSite->id_user = $daoUser->username;
            $daoUserSite->id_site = $daoSite->public_id;
            if(!$daoUserSite->find(true)){
                Denko::redirect(createMultipleResourceUrl("list","user",$_REQUEST['id'],"site"));
            }
            $smarty = new Smarty();
            $smarty->assign("daoSite",$daoSite);
            $smarty->assign("daoUser",$daoUser);
            $smarty->assign("daoUserSite",$daoUserSite);
            $smarty->assign("permission",$daoUserSite->permission);
            $smarty->assign("templateFile",'usersite-permission.tpl');
            $smarty->assign("title",'User Site Permissions');
            $smarty->assign("resource",'user');
            $smarty->assign("resourceId",$_REQUEST['id']);
            $smarty->assign("resource2",'site');
            $smarty->assign("resourceId2",$_REQUEST['id_site']);
            $smarty->assign("action",'permission');
            $smarty->display("common.tpl");
            break;
    }
    exit;
}
################################################################################
################################################################################
################################################################################
