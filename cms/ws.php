<?php
require_once 'common.php';
require_once '../commons/inny/InnyCMS.php';

header('Content-Type: application/json');

// TODO: Check user logged in and permissions on this file

if(!isset($_GET['q']) || empty($_GET['q']) || strlen($_GET['q']) < 3){
    $documents = array();
    $daoDocuments = InnyCMS::getAllDocuments($_GET['collectionName']);
    $daoDocuments->limit(20);$count = $daoDocuments->find();
    while($daoDocuments->fetch()){
        $fieldName = $_GET['field'];
        $keyField = (isset($_GET['keyField']) && !empty($_GET['keyField'])) ? $_GET['keyField'] : "public_id";
        $doc = array();
        $doc['id'] = $daoDocuments->$keyField;
        $doc['text'] = $daoDocuments->$keyField." - ".$daoDocuments->$fieldName;
        $documents[] = $doc;
    }
    $arr = array();
    $arr['currentPage'] = 1;
    $arr['pagesCount'] = 1;
    $arr['documents'] = $documents;
    $arr['resultsCount'] = $count;
    $arr['total'] = $count;
    echo json_encode($arr);
    exit;
}

$additionalParams = array();
if(isset($_GET['limit']) && !empty($_GET['limit'])) $additionalParams['limit'] = $_GET['limit'];
if(isset($_GET['page']) && !empty($_GET['page'])) $additionalParams['page'] = $_GET['page'];
if(isset($_GET['order']) && !empty($_GET['order'])) $additionalParams['order'] = $_GET['order'];
if(isset($_GET['orderField']) && !empty($_GET['orderField'])) $additionalParams['orderField'] = $_GET['orderField'];
if(isset($_GET['keyField']) && !empty($_GET['keyField'])) $additionalParams['keyField'] = $_GET['keyField'];

$res = InnyCMS::searchDocuments($_GET['collectionName'],array(array("searchField"=>$_GET['field'],"searchString"=>$_GET['q'],"operator"=>"like")),$additionalParams);

echo json_encode($res);
exit;
