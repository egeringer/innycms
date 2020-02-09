<?php
require_once 'common.php';
require_once '../commons/inny/InnyCMS.php';
################################################################################
################################################################################
InnyCMS::checkLogIn();
################################################################################
################################################################################
if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
    $response = array();
    if($_REQUEST['id'] === "empty"){
        $allowed = InnyCMS::checkPermission("collection","empty",$_REQUEST['name']);
        if(!$allowed) {
            $status = 0;
            $response['message'] = _t("You cannot perform this action");
        }else {
            $status = InnyCMS::emptyCollection($_REQUEST['name']);
            $response['message'] = ($status) ? _t("Collection emptied correctly") : _t("Collection could not be emptied");
        }
    }else{
        $allowed = InnyCMS::checkPermission("collection","delete",$_REQUEST['name'],"public_id",$_REQUEST['id']);
        if(!$allowed) {
            $status = 0;
            $response['message'] = _t("You cannot perform this action");
        }else {
            $siteName = InnyCMS::getSitePublicId();
            $status = InnyDocument::delete($_REQUEST['id'], $_REQUEST['name'],$siteName);
            $response['message'] = ($status) ? _t("Document deleted correctly") : _t("Document could not be deleted");
        }
    }
    $response['status'] = $status;
    $response['redirect'] = collectionURL($_REQUEST['name']);
    Denko::arrayUtf8Encode($response);
    echo json_encode($response);
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $_REQUEST['action'] != "draw" && $_REQUEST['action'] != "import" && $_REQUEST['action'] != "upload"){
    $response = array();
    $response['status'] = 0;

    $allowed = InnyCMS::checkPermission("collection","create",$_REQUEST['name']);
    if(!$allowed){
        $response['message'] = _t("You cannot perform this action");
    }else{
        $siteName = InnyCMS::getSitePublicId();
        $daoCollection = InnyCollection::get($_GET['name'],$siteName);
        if(!$daoCollection) {
            $response['message'] = _t("You are not allowed to insert an document in this collection");
        }else{
            $innyaddeditflag = (!empty($_REQUEST['innyaddeditflag'])) ? $_REQUEST['innyaddeditflag'] : "saveandedit";
            unset($_REQUEST['innyaddeditflag']);

            $siteName = InnyCMS::getSitePublicId();
            $resp = InnyDocument::add($_GET['name'],$siteName,$_REQUEST);

            if(!is_array($resp)){
                $daoDocument = InnyDocument::getOneByField("id_document",$resp,$_GET['name'],$siteName);
                switch ($innyaddeditflag){
                    case 'saveandview':
                        $response['redirect'] = collectionURL($_GET['name'],'view',$daoDocument->public_id);
                        break;
                        break;
                    case 'saveandnew':
                        $response['redirect'] = collectionURL($_GET['name'],'add');
                        break;
                    case 'saveandclone':
                        $response['redirect'] = collectionURL($_GET['name'],'clone',$daoDocument->public_id);
                        break;
                    case 'saveandclose':
                        $response['redirect'] = collectionURL($_GET['name']);
                        break;
                    case 'savepublished':
                    case 'saveandedit':
                    case 'saveunpublished':
                        $response['redirect'] = collectionURL($_GET['name'],'edit',$daoDocument->public_id);
                        break;
                }
                $response['status'] = 1;
                $response['message'] = _t("Document added correctly");
                $response['id'] = $resp;
            }else{
                $response['message'] = _t("Document could not be added.<br/>Please check the marked fields.");
                $response['error'] = $resp;
            }
        }
    }

    Denko::arrayUtf8Encode($response);
    echo json_encode($response);
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    $response = array();
    $response['status'] = 0;

    parse_str(file_get_contents("php://input"),$post_vars);

    $savingDraft = (isset($post_vars['innystatusflag']) && $post_vars['innystatusflag'] == "draft") ? true : false;
    $allowed = (InnyCMS::checkPermission("collection","update",$_REQUEST['name'],"public_id",$_REQUEST['id']) || (InnyCMS::checkPermission("collection","draft",$_REQUEST['name'],"public_id",$_REQUEST['id']) && $savingDraft)) ? 1 : 0;
    if(!$allowed){
        $response['message'] = _t("You cannot perform update on this document");
    }else{
        $siteName = InnyCMS::getSitePublicId();
        $daoCollection = InnyCollection::get($_GET['name'],$siteName);
        if(!$daoCollection) {
            $response['message'] = _t("You are not allowed to edit an document in this collection");
        }else{

            $innyaddeditflag = (!empty($post_vars['innyaddeditflag'])) ? $post_vars['innyaddeditflag'] : "saveandedit";
            unset($post_vars['innyaddeditflag']);

            $siteName = InnyCMS::getSitePublicId();
            $resp = InnyDocument::edit($_REQUEST['id'],$_REQUEST['name'],$siteName,$post_vars);

            if(!is_array($resp) && $resp == 1){
                $response['status'] = 1;
                switch ($innyaddeditflag){
                    case 'saveandview':
                        $response['redirect'] = collectionURL($_GET['name'],'view',$_REQUEST['id']);
                        break;
                    case 'saveandnew':
                        $response['redirect'] = collectionURL($_GET['name'],'add');
                        break;
                    case 'saveandclone':
                        $response['redirect'] = collectionURL($_GET['name'],'clone',$_REQUEST['id']);
                        break;
                    case 'saveandclose':
                        $response['redirect'] = collectionURL($_GET['name']);
                        break;
                    case 'saveandedit':
                    case 'saveunpublished':
                    case 'savedraft':
                    default:
                        $response['redirect'] = collectionURL($_GET['name'],'edit',$_REQUEST['id']);
                        break;
                }
                $response['message'] = _t("Document saved correctly");
            }else{
                $response['message'] = _t("Document could not be saved");
                $response['error'] = $resp;
            }
        }
    }

    Denko::arrayUtf8Encode($response);
    echo json_encode($response);
    exit;
}
################################################################################
################################################################################
if(isset($_REQUEST['action']) && !empty($_REQUEST['action']) && isset($_REQUEST['name']) && !empty($_REQUEST['name'])){
    switch ($_REQUEST['action']) {
        case "list":
            $allowed = InnyCMS::checkPermission("collection","list",$_REQUEST['name']);
            if(!$allowed) Denko::redirect("./");

            $siteName = InnyCMS::getSitePublicId();

            $daoCollection = InnyCollection::get($_GET['name'],$siteName);
            if(!$daoCollection) Denko::redirect("./home");

            $sidebar = InnyCMS::getSidebarMetadata();

            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
            $smarty->assign("name",$_GET['name']);
            $smarty->assign('sidebarUI',$sidebar);
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->display("collection.tpl");
        break;
        case "draw":
            header('Content-Type: application/json');
            $allowed = InnyCMS::checkPermission("collection","list",$_REQUEST['name']);
            if(!$allowed){
                echo json_encode(array());
            }else{

                $permission = InnyCMS::getSiteProperty("user_permission");
                $dashboardCollections = isset($permission['collection']) ? $permission['collection'] : array();
                if(isset($dashboardCollections[$_REQUEST['name']]['documents']) && !empty($dashboardCollections[$_REQUEST['name']]['documents'])){
                    foreach ($dashboardCollections[$_REQUEST['name']]['documents'] as $column => $values){
                        foreach($values as $value){
                            $_REQUEST['query'][$column] = $value;
                        }
                    }
                }

                $res = InnyCMS::drawCollection($_REQUEST);
                echo json_encode($res);
            }
            break;
        case "view":
            $allowed = InnyCMS::checkPermission("collection","read",$_REQUEST['name'],"public_id",$_GET['id']);
            if(!$allowed) Denko::redirect(collectionURL($_GET['name']));

            $siteName = InnyCMS::getSitePublicId();

            $daoCollection = InnyCollection::get($_GET['name'],$siteName);
            if(!$daoCollection) Denko::redirect("./home");

            $daoDocument = InnyDocument::get($_GET['id'],$_GET['name'],$siteName);
            if(!$daoDocument) Denko::redirect("./home");

            $innyTypes = InnyCMS::createInnyTypes($daoCollection,$daoDocument,true);

            $sidebar = InnyCMS::getSidebarMetadata();

            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
            $smarty->assign("name",$_GET['name']);
            $smarty->assign('sidebarUI',$sidebar);
            $smarty->assign("daoDocument",$daoDocument);
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->assign("innyTypes",$innyTypes);
            $smarty->assign("metadata",json_decode($daoCollection->metadata,true));
            $smarty->display("collection-document-view.tpl");
            exit;
            break;
        case "edit":
            $allowed = InnyCMS::checkPermission("collection","update",$_REQUEST['name'],"public_id",$_GET['id']);
            if(!$allowed) $allowed = InnyCMS::checkPermission("collection","draft",$_REQUEST['name'],"public_id",$_GET['id']);
            if(!$allowed) Denko::redirect(collectionURL($_GET['name']));

            $siteName = InnyCMS::getSitePublicId();

            $daoCollection = InnyCollection::get($_GET['name'],$siteName);
            if(!$daoCollection) Denko::redirect("./home");

            $daoDocument = InnyDocument::get($_GET['id'],$_GET['name'],$siteName);
            if(!$daoDocument) Denko::redirect("./home");

            $innyTypes = InnyCMS::createInnyTypes($daoCollection,$daoDocument,true);

            $sidebar = InnyCMS::getSidebarMetadata();

            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
            $smarty->assign("name",$_GET['name']);
            $smarty->assign('sidebarUI',$sidebar);
            $smarty->assign("daoDocument",$daoDocument);
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->assign("innyTypes",$innyTypes);
            $smarty->assign("metadata",json_decode($daoCollection->metadata,true));
            $smarty->display("collection-document-edit.tpl");
            exit;
            break;
        case "publish":
            $response = array();
            $allowed = InnyCMS::checkPermission("collection","publish",$_REQUEST['name'],"public_id",$_GET['id']);
            if(!$allowed){
                $status = 0;
                $response['message'] = _t("You cannot perform this action");
            }else{
                $siteName = InnyCMS::getSitePublicId();
                $result = InnyDocument::publish($_REQUEST['id'],$_REQUEST['name'],$siteName);
                $status = ($result) ? "1" : "0";
                $response['message'] = ($result) ? _t("Document published correctly") : _t("Document could not be published");
            }
            $response['status'] = $status;
            Denko::arrayUtf8Encode($response);
            echo json_encode($response);
            break;
        case "unpublish":
            $response = array();
            $allowed = InnyCMS::checkPermission("collection","unpublish",$_REQUEST['name'],"public_id",$_GET['id']);
            if(!$allowed){
                $status = 0;
                $response['message'] = _t("You cannot perform this action");
            }else{
                $siteName = InnyCMS::getSitePublicId();
                $result = InnyDocument::unpublish($_REQUEST['id'],$_REQUEST['name'],$siteName);
                $status = ($result) ? "1" : "0";
                $response['message'] = ($status) ? _t("Document unpublished correctly") : _t("Document could not be unpublished");
            }
            $response['status'] = $status;
            Denko::arrayUtf8Encode($response);
            echo json_encode($response);
            break;
        case "discard":
            $response = array();
            $allowed = InnyCMS::checkPermission("collection","draft",$_REQUEST['name'],"public_id",$_GET['id']);
            if(!$allowed){
                $status = 0;
                $response['message'] = _t("You cannot perform this action");
            }else{
                $siteName = InnyCMS::getSitePublicId();
                $result = InnyDocument::discardDraft($_REQUEST['id'],$_REQUEST['name'],$siteName);
                $status = ($result) ? "1" : "0";
                $response['message'] = ($result) ? _t("Document draft discarded correctly") : _t("Document draft could not be discarded");
                $response['redirect'] = collectionURL($_GET['name'],'edit',$_REQUEST['id']);
            }
            $response['status'] = $status;
            Denko::arrayUtf8Encode($response);
            echo json_encode($response);
            break;
        case "clone":
            $allowed = InnyCMS::checkPermission("collection","create",$_REQUEST['name'],"public_id",$_GET['id']);
            if(!$allowed) Denko::redirect(collectionURL($_GET['name']));

            $siteName = InnyCMS::getSitePublicId();

            $daoCollection = InnyCollection::get($_GET['name'],$siteName);
            if(!$daoCollection) Denko::redirect("./home");

            $daoDocument = InnyDocument::get($_GET['id'],$_GET['name'],$siteName);
            if(!$daoDocument) Denko::redirect("./home");

            $innyTypes = InnyCMS::createInnyTypes($daoCollection,$daoDocument,true);

            $sidebar = InnyCMS::getSidebarMetadata();

            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
            $smarty->assign("name",$_GET['name']);
            $smarty->assign('sidebarUI',$sidebar);
            $smarty->assign("daoDocument",$daoDocument);
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->assign("innyTypes",$innyTypes);
            $smarty->assign("metadata",json_decode($daoCollection->metadata,true));
            $smarty->display("collection-document-clone.tpl");
            exit;
            break;
        case "add":
            $allowed = InnyCMS::checkPermission("collection","create",$_REQUEST['name']);
            if(!$allowed) Denko::redirect(collectionURL($_GET['name']));

            $siteName = InnyCMS::getSitePublicId();

            $daoCollection = InnyCollection::get($_GET['name'],$siteName);
            if(!$daoCollection) Denko::redirect("./home");

            $daoDocument = null;

            $innyTypes = InnyCMS::createInnyTypes($daoCollection,$daoDocument);

            $sidebar = InnyCMS::getSidebarMetadata();

            $templatePath = InnyCMS::getCustomizationPath('templates');
            $form = null;
            if(file_exists($templatePath."/collection-".$daoCollection->name."-add.tpl")){
                ini_set("display_errors",true);
                error_reporting(E_ALL);
                $smartyForm = new Smarty();
                $smartyForm->assign("innyTypes",$innyTypes);
                $smartyForm->setTemplateDir($templatePath);
                $form = $smartyForm->fetch("collection-".$daoCollection->name."-add.tpl");
            }

            $smarty = new Smarty();
            $smarty->loadFilter('output','dk_include');
            $smarty->assign("name",$_GET['name']);
            $smarty->assign('sidebarUI',$sidebar);
            $smarty->assign("daoDocument",$daoDocument);
            $smarty->assign("daoCollection",$daoCollection);
            $smarty->assign("innyTypes",$innyTypes);
            $smarty->assign("metadata",json_decode($daoCollection->metadata,true));
            $smarty->assign("form",$form);

            $smarty->display("collection-document-add.tpl");
            exit;
            break;
        case "moveup":
            $response = array();
            $allowed = InnyCMS::checkPermission("collection","order",$_REQUEST['name']);
            if(!$allowed){
                $status = 0;
                $response['message'] = _t("You cannot perform this action");
            }else{
                $siteName = InnyCMS::getSitePublicId();
                $result = InnyDocument::moveUp($_REQUEST['id'],$_REQUEST['name'],$siteName);
                $status = ($result) ? "1" : "0";
                $response['message'] = ($result) ? _t("Document moved up correctly") : _t("Document could not be moved up");
            }
            $response['status'] = $status;
            Denko::arrayUtf8Encode($response);
            echo json_encode($response);
            break;
        case "movedown":
            $response = array();
            $allowed = InnyCMS::checkPermission("collection","order",$_REQUEST['name']);
            if(!$allowed){
                $status = 0;
                $response['message'] = _t("You cannot perform this action");
            }else{
                $siteName = InnyCMS::getSitePublicId();
                $result = InnyDocument::moveDown($_REQUEST['id'],$_REQUEST['name'],$siteName);
                $status = ($result) ? "1" : "0";
                $response['message'] = ($result) ? _t("Document moved down correctly") : _t("Document could not be moved down");
            }
            $response['status'] = $status;
            Denko::arrayUtf8Encode($response);
            echo json_encode($response);
            break;
        case "download":
            $allowed = InnyCMS::checkPermission("collection","download",$_REQUEST['name']);
            if(!$allowed) Denko::redirect("./");
            InnyCMS::downloadCollection($_REQUEST['name']);
            exit;
            break;
        case "upload":
            $allowed = InnyCMS::checkPermission("collection","create",$_REQUEST['name']);
            $res = array();
            if(!$allowed) {
                $res['status'] = 0;
                $res['message'] = _t("You are not allowed to upload to this collection");
                $res['redirect'] = collectionURL($_REQUEST['name']);
            }else{
                $res = InnyCMS::uploadCollection($_REQUEST['name'],$_FILES['file']);
            }
            Denko::arrayUTF8Encode($res);
            echo json_encode($res);
            exit;
            break;
        case "exportcsv":
            $allowed = InnyCMS::checkPermission("collection","export",$_REQUEST['name']);
            if(!$allowed) Denko::redirect("./");
            InnyCMS::exportCollection($_REQUEST['name'],"csv");
            exit;
            break;
        case "exportjson":
            $allowed = InnyCMS::checkPermission("collection","export",$_REQUEST['name']);
            if(!$allowed) Denko::redirect("./");
            InnyCMS::exportCollection($_REQUEST['name'],"json");
            exit;
            break;
        case "import":
            $allowed = InnyCMS::checkPermission("collection","import",$_REQUEST['name']);
            $res = array();
            if(!$allowed) {
                $res['status'] = 0;
                $res['message'] = _t("You are not allowed to import this collection");
                $res['redirect'] = collectionURL($_REQUEST['name']);
            }else{
                $res = InnyCMS::importCollection($_REQUEST['name'],$_FILES['file']);
            }
            Denko::arrayUTF8Encode($res);
            echo json_encode($res);
            exit;
            break;
        default:
            echo _t("Invalid action provided");
            exit;

    }
}
