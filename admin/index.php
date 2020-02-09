<?php
require_once 'common.php';
// InnyCMS and InnyCMS Sys Admin Dashboard share users.
// Users have a role which can be one of the following:
// "sysadmin" or "siteadmin"
InnyCMS::checkAdminLogIn();

$daoUsers = Denko::daoFactory('innydb_user');
$daoUsers->status = "1";
$enabledUsers = $daoUsers->count();
$daoUsers->status = "2";
$disabledUsers = $daoUsers->count();
unset($daoUsers->status);
$daoUsers->role = "sysadmin";
$adminUsers = $daoUsers->count();
unset($daoUsers->role);
$totalUsers = $enabledUsers + $disabledUsers;

$daoSites = Denko::daoFactory('innydb_site');
$daoSites->status = "1";
$enabledSites = $daoSites->count();
$daoSites->status = "2";
$disabledSites = $daoSites->count();
unset($daoSites->status);
$daoSites->status = "3";
$maintenanceSites = $daoSites->count();
unset($daoSites->maintenance);
$totalSites = $enabledSites + $disabledSites + $maintenanceSites;

$smarty = new Smarty();
$smarty->assign("totalUsers",$totalUsers);
$smarty->assign("enabledUsers",$enabledUsers);
$smarty->assign("disabledUsers",$disabledUsers);
$smarty->assign("adminUsers",$adminUsers);
$smarty->assign("totalSites",$totalSites);
$smarty->assign("enabledSites",$enabledSites);
$smarty->assign("disabledSites",$disabledSites);
$smarty->assign("maintenanceSites",$maintenanceSites);
$smarty->assign("templateFile","dashboard.tpl");
$smarty->assign("title","Dashboard");
$smarty->display('common.tpl');