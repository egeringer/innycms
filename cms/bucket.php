<?php
include_once 'common.php';
require_once '../commons/inny/InnyCMS.php';

################################################################################
################################################################################
InnyCMS::checkLogIn();
################################################################################
################################################################################

if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
    $response = array();
    if(isset($_REQUEST['action'])){
        switch($_REQUEST['action']){
            case 'delete':
                $allowed = InnyCMS::checkPermission("bucket","delete");
                if(!$allowed){
                    $response['status'] = 0;
                    $response['message'] = _t("You cannot perform this action");
                }else{
                    if(isset($_REQUEST['id']) && !empty($_REQUEST['id']) && $allowed){
                        $response['status'] = InnyCMS::deleteBucketFile($_REQUEST['id']);
                    }else{
                        Denko::redirect("bucket");
                    }
                    $response['message'] = ($response['status']) ? _t("Item deleted correctly") : _t("Item could not be deleted");
                    if(isset($_GET['from']) && $_GET['from'] != "bucket") $response['redirect'] = "./bucket";
                }
                break;
            case 'cleanBucket':
                $response['status'] = 0;
                $allowed = InnyCMS::checkPermission("bucket","cleanBucket");
                if(!$allowed){
                    $response['message'] = _t("You cannot perform this action");
                }else{
                    $validPassword = InnyCMS::checkUserPassword($_REQUEST['password']);
                    if($validPassword['status'] == 1) {
                        $response['status'] = InnyCMS::cleanBucket();
                        $response['message'] = ($response['status']) ? _t('Bucket cleaned correctly')." (".$response['status'].")" : _t("Bucket could not be cleaned");
                        $response['redirect'] = "./bucket";
                    }else{
                        $response['status'] = 0;
                        $response['message'] = _t("Wrong password entered");
                    }
                }
                break;
            case 'emptyBucket':
                $response['status'] = 0;
                $allowed = InnyCMS::checkPermission("bucket","emptyBucket");
                if(!$allowed){
                    $response['message'] = _t("You cannot perform this action");
                }else {
                    $validPassword = InnyCMS::checkUserPassword($_REQUEST['password']);
                    if ($validPassword['status'] == 1) {
                        $response['status'] = InnyCMS::emptyBucket();
                        $response['message'] = ($response['status']) ? _t("Bucket emptied correctly")." (" . $response['status'] . ")" : _t("Bucket could not be emptied");
                        $response['redirect'] = "./bucket";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = _t("Wrong password entered");
                    }
                }
                break;
        }
    }

    Denko::arrayUtf8Encode($response);
    echo json_encode($response);
    exit;
}

################################################################################
################################################################################

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!empty($_FILES)  && isset($_FILES['file'])) {
        if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            exit;
        }
        $id_bucket = InnyCMS::addFileToBucket($_FILES['file']);
        if(!$id_bucket) header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        else {
            if(isset($_GET['quick-upload'])){
                echo json_encode(InnyCMS::getBucketFileInfo($id_bucket));
            }else{
                $smarty = new Smarty();
                $smarty->assign("daoBucket",InnyCMS::getBucketDaoById($id_bucket));
                if(isset($_GET['portlet-upload'])) {
                    $response = InnyCMS::getBucketFileInfo($id_bucket);
                    $smarty->assign("field",$_REQUEST['field']);
                    $smarty->assign("discardable","true");
                    $response['html'] = $smarty->fetch("bucket-portlet.tpl");
                    $response = json_encode($response);
                }else{
                    $response = $smarty->fetch("bucket-item.tpl");
                }
                echo $response;
            }
        }
        exit;
    }else if(isset($_POST['action'])){
        switch($_POST['action']){
            case 'addtag':
                if(!InnyCMS::checkPermission("bucket","addtag")) return false;
                InnyCMS::addTagToBucketFile($_POST['id_bucket'],$_POST['tag']);
                exit;
                break;
            case 'removetag':
                if(!InnyCMS::checkPermission("bucket","removetag")) return false;
                InnyCMS::removeTagFromBucketFile($_POST['id_bucket'],$_POST['tag']);
                exit;
                break;
            case 'cleartags':
                if(!InnyCMS::checkPermission("bucket","cleartags")) return false;
                InnyCMS::clearTagsFromBucketFile($_POST['id_bucket']);
                exit;
                break;
            default:
                break;
        }
    }

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit;
}

################################################################################
################################################################################

if(!InnyCMS::checkPermission("bucket","list")) Denko::redirect("./");

################################################################################
################################################################################

$smarty = new Smarty();
$smarty->loadFilter('output','dk_include');
$daoBucket = null;

# Obtengo el dao del Bucket
if(isset($_GET['action'])){
    switch($_GET['action']){
        case 'statistics':
            $stats = InnyCMS::getBucketStats();

            $sidebar = InnyCMS::getSidebarMetadata();
            $smarty->assign('sidebarUI',$sidebar);
            $smarty->assign("stats",$stats);;
            $smarty->display('bucket-statistics.tpl');
            exit;
        case 'search':
            if((!empty($_GET['q']) || !empty($_GET['type']))){
                $type = (!empty($_GET['type']) && $_GET['type'] != "all") ? $_GET['type'] : null;
                $daoBucket = InnyCMS::searchBucketFiles($_GET['q'], $type);
            }else{
                $daoBucket = InnyCMS::searchBucketFiles();
            }
        break;
        case 'view':
        case 'tags':
            if(!isset($_GET['id']) || empty($_GET['id'])) Denko::redirect("bucket");
            $daoBucket = InnyCMS::getBucketDaoById($_GET['id']);
            if(!$daoBucket) Denko::redirect("./bucket");
            $hash = (isset($_GET['hash']) && !empty($_GET['hash'])) ? $_GET['hash']: null;
            $bucketHash = substr($daoBucket->hash,0,5);
            if($hash != $bucketHash) Denko::redirect("./bucket");
            $sidebar = InnyCMS::getSidebarMetadata();
            $smarty->assign('sidebarUI',$sidebar);
            $smarty->assign("daoBucket",$daoBucket);
            $smarty->display('bucket-'.$_GET['action'].'.tpl');
            exit;
        case 'delete':
            Denko::redirect("./bucket");
    }
}else{
    $daoBucket = InnyCMS::searchBucketFiles();
}

$sidebar = InnyCMS::getSidebarMetadata();
$smarty->assign('sidebarUI',$sidebar);
$smarty->assign("daoBucket",$daoBucket);
$smarty->assign("section","bucket");
$smarty->display('bucket.tpl');
