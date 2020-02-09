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
            $daoSite = Denko::daoFactory('innydb_site');
            $tableFields = $daoSite->table();
            $tableKeys = $daoSite->keys();
            foreach ($tableFields as $k => $v){
                if(!in_array($k,$tableKeys) && !in_array($k,$hiddenFields)){
                    if(!empty($_REQUEST[$k]) ||  $_REQUEST[$k] === "0") $daoSite->$k = $_REQUEST[$k];
                    else $continue = true;
                }
            }

            if(!$continue){
                $id_site = $daoSite->insert();
                if($id_site) Denko::redirect(createSingleResourceUrl("view","site",$id_site));
            }

            $continue = true;
            break;
        case 'edit':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_REQUEST['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $original = clone($daoSite);
            $tableFields = $daoSite->table();
            $tableKeys = $daoSite->keys();
            foreach ($tableFields as $k => $v){
                if(isset($_REQUEST[$k]) && (!empty($_REQUEST[$k]) || $_REQUEST[$k] === "0") && !in_array($k,$tableKeys) && !in_array($k,$hiddenFields)) $daoSite->$k = $_REQUEST[$k];
            }
            if($daoSite->update($original)) Denko::redirect(createSingleResourceUrl("view","site",$_REQUEST['id']));
            $continue = true;
            break;
        case 'sidebar':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->public_id = $_GET['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $original = clone($daoSite);
            $metadata = json_decode($daoSite->metadata,true);
            $metadata['sidebar'] = (isset($_POST['sidebar']) && !empty($_POST['sidebar'])) ? $_POST['sidebar'] : json_decode($_POST['json'],true);
            $daoSite->metadata = json_encode($metadata);
            if($daoSite->update($original)) Denko::redirect(createSingleResourceUrl("sidebar","site",$_REQUEST['id']));
            $continue = true;
            break;
    }
}
################################################################################
################################################################################
################################################################################
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_REQUEST['action']) && !empty($_REQUEST['action']) || $continue) {
    switch ($_REQUEST['action']) {
        case 'list':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->whereAdd("status > 0");
            $daoSite->find();
            $smarty = new Smarty();
            $smarty->assign("daoSite",$daoSite);
            $smarty->assign("templateFile",'site-list.tpl');
            $smarty->assign("title",'Sites');
            $smarty->assign("resource",'site');
            $smarty->display("common.tpl");
            break;
        case 'view':
            $daoSite = Denko::daoFactory('innydb_site');
            $tableFields = $daoSite->table();
            $tableKeys = $daoSite->keys();
            $daoSite->id_site = $_GET['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $smarty = new Smarty();
            $smarty->assign("daoSite",$daoSite);
            $smarty->assign("fields",$tableFields);
            $smarty->assign("keys",$tableKeys);
            $smarty->assign("hiddenFields",$hiddenSiteViewFields);
            $smarty->assign("templateFile",'site-view.tpl');
            $smarty->assign("title",'Sites');
            $smarty->assign("resource",'site');
            $smarty->assign("resourceId",$_GET['id']);
            $smarty->assign("action",'view');
            $smarty->display("common.tpl");
            break;
        case 'edit':
            $daoSite = Denko::daoFactory('innydb_site');
            $tableFields = $daoSite->table();
            $tableKeys = $daoSite->keys();
            $daoSite->id_site = $_GET['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $prevData = $continue ? $_POST : array();
            $smarty = new Smarty();
            $smarty->assign("daoSite",$daoSite);
            $smarty->assign("fields",$tableFields);
            $smarty->assign("keys",$tableKeys);
            $smarty->assign("hiddenFields",array_merge($hiddenFields,array("public_id")));
            $smarty->assign("prevData",$prevData);
            $smarty->assign("templateFile",'site-edit.tpl');
            $smarty->assign("title",'Sites');
            $smarty->assign("resource",'site');
            $smarty->assign("resourceId",$_GET['id']);
            $smarty->assign("action",'edit');
            $smarty->display("common.tpl");
            break;
        case 'enable':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_GET['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $daoSite->status = "1";
            $daoSite->update();
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createSingleResourceUrl("list","site"));
            break;
        case 'disable':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_GET['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $daoSite->status = "2";
            $daoSite->update();
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createSingleResourceUrl("list","site"));
            break;
        case 'setmaintenance':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_GET['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $daoSite->status = "3";
            $daoSite->update();
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createSingleResourceUrl("list","site"));
            break;
        case 'unsetmaintenance':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_GET['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $daoSite->status = "1";
            $daoSite->update();
            if(isset($_SERVER['HTTP_REFERER'])) { Denko::redirect($_SERVER['HTTP_REFERER']); }
            Denko::redirect(createSingleResourceUrl("list","site"));
            break;
        case 'add':
            $daoSite = Denko::daoFactory('innydb_site');
            $tableFields = $daoSite->table();
            $tableKeys = $daoSite->keys();
            $prevData = $continue ? $_POST : array();
            $smarty = new Smarty();
            $smarty->assign("fields",$tableFields);
            $smarty->assign("keys",$tableKeys);
            $smarty->assign("hiddenFields",$hiddenFields);
            $smarty->assign("prevData",$prevData);
            $smarty->assign("templateFile",'site-add.tpl');
            $smarty->assign("title",'Sites');
            $smarty->assign("resource",'site');
            $smarty->assign("action",'add');
            $smarty->assign("resourceId",'');
            $smarty->display("common.tpl");
            break;
        case 'delete':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->id_site = $_REQUEST['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $daoSite->status = "0";
            $daoSite->update();
            Denko::redirect(createSingleResourceUrl("list","site"));
            break;
        case 'sidebar':
            $daoSite = Denko::daoFactory('innydb_site');
            $tableFields = $daoSite->table();
            $tableKeys = $daoSite->keys();
            $daoSite->public_id = $_GET['id'];
            if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
            if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            $metadata = json_decode($daoSite->metadata,true);
            $sidebar = isset($metadata['sidebar']) ? $metadata['sidebar'] : array();
            $daoCollection = Denko::daoFactory('innydb_collection');
            $daoCollection->id_site = $daoSite->id_site;
            $daoCollection->find();
            $smarty = new Smarty();
            $smarty->assign("daoSite",$daoSite);
            $smarty->assign("sidebar",$sidebar);
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->assign("fields",$tableFields);
            $smarty->assign("keys",$tableKeys);
            $smarty->assign("hiddenFields",$hiddenFields);
            $smarty->assign("templateFile",'site-sidebar.tpl');
            $smarty->assign("title",'Sites');
            $smarty->assign("resource",'site');
            $smarty->assign("resourceId",$_GET['id']);
            $smarty->assign("action",'sidebar');
            $smarty->display("common.tpl");
            break;

    }
    exit;
}
################################################################################
################################################################################
################################################################################