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
            $daoCollection = Denko::daoFactory('innydb_collection');
            // Check collection name
            $daoCollection->name = $_REQUEST['name'];
            // Check site exists
            $daoCollection->site_name = $_REQUEST['site_name'];
            $daoCollection->public_id = InnyCMS::createUniqueId("innydb_collection","public_id","site_name",$_REQUEST['site_name']);
            if(!$continue){
                $id_collection = $daoCollection->insert();
                if($id_collection) Denko::redirect(createSingleResourceUrl("edit","collection",$id_collection));
            }

            $continue = true;
            break;
        case 'edit':
            $daoCollection = Denko::daoFactory('innydb_collection');
            $daoCollection->id_collection = $_REQUEST['id'];
            if(!$daoCollection->find(true)) Denko::redirect(createSingleResourceUrl("list","collection"));
            $original = clone($daoCollection);
            $daoCollection->metadata = $_REQUEST['metadata'];
            if($daoCollection->update($original)) Denko::redirect(createSingleResourceUrl("view","collection",$_REQUEST['id']));
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
            $daoCollection = Denko::daoFactory('innydb_collection');
            $daoSite = null;
            if(isset($_REQUEST['id'])) {
                $daoCollection->site_name = $_REQUEST['id'];
                $daoSite = Denko::daoFactory('innydb_site');
                $daoSite->public_id = $_REQUEST['id'];
                if(!$daoSite->find(true)) Denko::redirect(createSingleResourceUrl("list","site"));
                if($daoSite->status == "0") Denko::redirect(createSingleResourceUrl("list","site"));
            }
            $daoCollection->orderBy("site_name asc, name asc");
            $daoCollection->find();
            $smarty = new Smarty();
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->assign("daoSite",$daoSite);
            $smarty->assign("templateFile",'collection-list.tpl');
            $smarty->assign("title",'Collections');
            $smarty->assign("resource",'collection');
            $smarty->display("common.tpl");
            break;
        case 'view':
            $daoCollection = Denko::daoFactory('innydb_collection');
            $daoCollection->id_collection = $_REQUEST['id'];
            if(!$daoCollection->find(true)) Denko::redirect(createSingleResourceUrl("list","collection"));
            $smarty = new Smarty();
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->assign("templateFile",'collection-view.tpl');
            $smarty->assign("title",'Collection');
            $smarty->assign("resource",'collection');
            $smarty->assign("resourceId",$_REQUEST['id']);
            $smarty->assign("action",'view');
            $smarty->display("common.tpl");
            break;
        case 'edit':
            $daoCollection = Denko::daoFactory('innydb_collection');
            $daoCollection->id_collection = $_REQUEST['id'];
            if(!$daoCollection->find(true)) Denko::redirect(createSingleResourceUrl("list","collection"));
            $smarty = new Smarty();
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->assign("templateFile",'collection-edit.tpl');
            $smarty->assign("title",'Collection');
            $smarty->assign("resource",'collection');
            $smarty->assign("resourceId",$_REQUEST['id']);
            $smarty->assign("action",'edit');
            $smarty->display("common.tpl");
            break;
        case 'add':
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->whereAdd("status > 0");
            $daoSite->find();
            $prevData = $continue ? $_POST : array();
            $smarty = new Smarty();
            $smarty->assign("daoSite",$daoSite);
            $smarty->assign("prevData",$prevData);
            $smarty->assign("templateFile",'collection-add.tpl');
            $smarty->assign("title",'Collection');
            $smarty->assign("resource",'collection');
            $smarty->assign("resourceId",'');
            $smarty->assign("action",'add');
            $smarty->display("common.tpl");
            break;
        case 'delete':
            $daoCollection = Denko::daoFactory('innydb_collection');
            $daoCollection->id_collection = $_REQUEST['id'];
            if(!$daoCollection->find(true)) Denko::redirect(createSingleResourceUrl("list","collection"));
            $empty = InnyCMS::emptyCollection($daoCollection->name,$daoCollection->site_name);
            if($empty) $daoCollection->delete();
            Denko::redirect(createSingleResourceUrl("list","collection"));
            break;
    }
    exit;
}
################################################################################
################################################################################
################################################################################