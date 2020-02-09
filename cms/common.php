<?php
require_once '../commons/common.php';

header('content-type: text/html; charset=UTF-8');
define('DENKO_WEB_FOLDER','/cms');

################################################################################

function collectionURL($collectionName, $action=null, $key=null){
	if($key!==null){
		return "./$action-$collectionName!$key";
	}
	if($action!==null){
		return "./$action-$collectionName";
	}
	return "./list-$collectionName";
}