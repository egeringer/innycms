<?php
require_once '../commons/common.php';

header('content-type: text/html; charset=UTF-8');
define('DENKO_WEB_FOLDER','/admin');

$hiddenFields = array("aud_ins_date","aud_upd_date","aud_ins_user","aud_upd_user","deleted","password","metadata","configs","status");

$hiddenSiteViewFields = array("id_site","metadata","configs","status");
$hiddenUserViewFields = array("id_user","password","configs","status");
$hiddenUserAddFields = array("aud_ins_date","aud_upd_date","aud_ins_user","aud_upd_user","status","role");

function createSingleResourceUrl($action,$resource,$id = null){
    return "./$action-$resource".((!empty($id)) ? "!$id" : "");
}

function createMultipleResourceUrl($action,$resource1,$id1,$resource2,$id2 = null){
    return "./$action-$resource1!$id1-$resource2".((!empty($id2)) ? "!$id2" : "");
}