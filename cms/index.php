<?php
/**
 * @copyright 2007-2017 Dokko Group
 * @author Dokko Group <info at dokkogroup dot com dot ar>
 */
include_once 'common.php';
require_once '../commons/inny/InnyCMS.php';
################################################################################
################################################################################
InnyCMS::checkLogIn();
################################################################################
################################################################################
$sidebar = InnyCMS::getSidebarMetadata();
################################################################################
################################################################################
$permission = InnyCMS::getSiteProperty("user_permission");
$dashboardCollections = isset($permission['collection']) ? $permission['collection'] : array();
$dashboardMessages = isset($permission['messages']) ? $permission['messages'] : array();
################################################################################
################################################################################
if(empty($dashboardCollections)) Denko::redirect("./profile");
################################################################################
################################################################################
$smarty = new Smarty();
$smarty->loadFilter('output','dk_include');
$smarty->assign('sidebarUI',$sidebar);
$smarty->assign('dashboardCollections',$dashboardCollections);
$smarty->assign('dashboardMessages',$dashboardMessages);
$smarty->display('dashboard.tpl');
################################################################################