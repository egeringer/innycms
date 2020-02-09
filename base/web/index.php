<?php
require_once 'common.php';
################################################################################
$smarty = new Smarty();
$smarty->loadFilter('output','dk_include');
$smarty->display('index.tpl');