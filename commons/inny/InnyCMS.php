<?php
//ini_set("display_errors",true);
//ini_set("error_reporting",E_ALL);
/**
 * Created by PhpStorm.
 * User: egeringer
 * Date: 6/5/17
 * Time: 18:55
 */

require_once dirname(__FILE__).'/InnyBucket.php';
require_once dirname(__FILE__).'/InnyCollection.php';
require_once dirname(__FILE__) .'/InnyDocument.php';
require_once dirname(__FILE__).'/InnyType.php';

class InnyCMS
{
    public static $session_inny_admin = "inny_admin";
    public static $session_inny_cms = "inny_cms";

    public static $session_inny_site = 'inny_site';
    public static $session_inny_user = 'inny_user';

    //$configKey
    public static $session_metadata = 'INNY_RAW_METADATA';
    public static $session_metadata_path = '../innycms.json';

    // Roles
    public static $user_role_sysadmin = 'sysadmin';
    public static $user_role_siteadmin = 'siteadmin';
    public static $user_site_role_admin = 'admin';
    public static $user_site_role_editor = 'editor';

    /*****************************************************************************************/
    /******************************************************** DOCUMENTS CONST ****************/
    /*****************************************************************************************/

    public static $document_status_published = '1';
    public static $document_status_unpublished = '2';

    /*************************************************************************************/
    /******************************************************** CUSTOM PATHS****************/
    /*************************************************************************************/

    public static function getCustomizationPath($resource){
        $configFile=dirname(__FILE__).'/../../config.json.local';
        if(!file_exists($configFile)) $configFile=dirname(__FILE__).'/../../config.json';
        $file = file_get_contents($configFile);
        $json = json_decode($file,true);
        return (isset($json['customizations'][$resource])) ? $json['customizations'][$resource] : "";
    }

    /*************************************************************************************/
    /******************************************************** USER LOGIN *****************/
    /*************************************************************************************/

    public static function logIn($username,$password,$remember = false){
        $username = Denko::lower(Denko::trim($username));
        $result = array();
        $result['status'] = 0;

        # Verifico que haya ingresado el username
        if(empty($username)){
            $result['message'] = _t("Username is required.");
            return $result;
        }

        # Verifico que haya ingresado el password
        if(empty($password)){
            $result['message'] = _t("Password is required.");
            return $result;
        }

        # En caso que no haya errores, verifico que los datos sean válidos

        $daoUser = Denko::daoFactory('innydb_user');

        $daoUser->username = $username;

        # En caso que el usuario exista, verifico su password
        if(!$daoUser->find(true) || !password_verify($password, $daoUser->password)){
            $result['message'] = _t("Incorrect username or password.");
            return $result;
        }

        if($daoUser->status != "1") {
            $result['message'] = _t("Your account has been disabled or deleted.");
            return $result;
        }

        $sites = self::getUserSites($daoUser->id_user);

        if(count($sites) == 0){
            $result['message'] = _t("Your account has no sites.");
            return $result;
        }else if(count($sites) == 1){
            if(array_values($sites)[0]['status'] != "1"){
                $result['message'] = _t("Your site has been disabled.");
                return $result;
            }
        }

        //Store id_user in Session
        InnyCMS::setUser($daoUser->id_user);

        //TODO set cookie to keep user logged in
        $expires = 0;if(isset($remember) && $remember == 1){$expires = time()+31536000;}setcookie('INNYCMS_USERNAME',$username,$expires);

        $result['status'] = 1;
        $result['message'] = "Welcome ".InnyCMS::getUserProperty("name")."!";

        if(count($sites) > 1){
            $result['redirect'] = "./choose";
        }else{
            $public_id = array_values($sites)[0]['public_id'];
            InnyCMS::setSite($public_id);
            $result['redirect'] = "./home";
        }
        return $result;
    }

    /*********************************************************************************************/
    /******************************************************** CHECK SESSION INFO *****************/
    /*********************************************************************************************/

    public static function isLoggedUser(){
        return (isset($_SESSION[self::$session_inny_cms]) && isset($_SESSION[self::$session_inny_cms][self::$session_inny_user]) && is_array($_SESSION[self::$session_inny_cms][self::$session_inny_user]) && !empty($_SESSION[self::$session_inny_cms][self::$session_inny_user]['id_user']) && isset($_SESSION[self::$session_inny_cms][self::$session_inny_user]['status']) && $_SESSION[self::$session_inny_cms][self::$session_inny_user]['status'] == "1");
    }

    public static function isLockedUser(){
        return (isset($_SESSION[self::$session_inny_cms]) && isset($_SESSION[self::$session_inny_cms][self::$session_inny_user]) && is_array($_SESSION[self::$session_inny_cms][self::$session_inny_user]) && !empty($_SESSION[self::$session_inny_cms][self::$session_inny_user]['id_user']) && isset($_SESSION[self::$session_inny_cms][self::$session_inny_user]['locked']) && $_SESSION[self::$session_inny_cms][self::$session_inny_user]['locked'] == true);
    }

    public static function isLoggedSite(){
        return (isset($_SESSION[self::$session_inny_cms]) && !empty($_SESSION[self::$session_inny_cms][self::$session_inny_site]) && is_array($_SESSION[self::$session_inny_cms][self::$session_inny_site]) && !empty($_SESSION[self::$session_inny_cms][self::$session_inny_site]['id_site']) && isset($_SESSION[self::$session_inny_cms][self::$session_inny_site]['status']) && ($_SESSION[self::$session_inny_cms][self::$session_inny_site]['status'] == "1" || $_SESSION[self::$session_inny_cms][self::$session_inny_site]['status'] == "3"));
    }

    public static function checkLogIn(){
        # Reload User and Site Info From DB
        self::updateUserSession();
        self::updateSiteSession();

        # Chequeo si tengo sesion iniciada
        if(!InnyCMS::isLoggedUser()){
            Denko::redirect('./logout');
        }
        # Chequeo si estoy bloqueado
        if(InnyCMS::isLockedUser()){
            Denko::redirect('./lock');
        }
        # Chequeo que haya sitio logueado
        if(!InnyCMS::isLoggedSite()){
            Denko::redirect('./choose');
        }
    }

    public static function checkUserPassword($password){
        $response = array();
        $response['status'] = 1;

        $username = self::getUserProperty("username");
        $daoUser = Denko::daoFactory('innydb_user');
        $daoUser->username = $username;
        # En caso que el usuario exista, verifico su password
        if(!$daoUser->find(true) || !password_verify($password, $daoUser->password)){
            $response['message'] = _t("Wrong password entered.");
            $response['status'] = 0;
        } else if($daoUser->status != '1') {
            $response['message'] = _t("This account has been disabled.");
            $response['status'] = 0;
        }

        return $response;
    }

    /**********************************************************************************************/
    /******************************************************** UPDATE SESSION INFO *****************/
    /**********************************************************************************************/

    public static function setUser($id_user){
        $daoUser = Denko::daoFactory('innydb_user');
        $daoUser->id_user = $id_user;
        $daoUser->status = "1";
        $found = $daoUser->find();
        if($found){
            $_SESSION[self::$session_inny_cms][self::$session_inny_user] = array(
                'id_user' => $id_user
            );
            self::updateUserSession();
        }
    }

    public static function updateUserSession(){
        $id_user = self::getUserId();
        if(!$id_user) return;
        $daoUser = Denko::daoFactory('innydb_user');
        $found = $daoUser->get($id_user);
        if($found) {
            $_SESSION[self::$session_inny_cms][self::$session_inny_user]['username'] = $daoUser->username;
            $_SESSION[self::$session_inny_cms][self::$session_inny_user]['name'] = $daoUser->name;
            $_SESSION[self::$session_inny_cms][self::$session_inny_user]['lastname'] = $daoUser->lastname;
            $_SESSION[self::$session_inny_cms][self::$session_inny_user]['email'] = $daoUser->email;
            $_SESSION[self::$session_inny_cms][self::$session_inny_user]['role'] = $daoUser->role;
            $_SESSION[self::$session_inny_cms][self::$session_inny_user]['status'] = $daoUser->status;
            $_SESSION[self::$session_inny_cms][self::$session_inny_user]['locked'] = false;
        }
    }

    public static function setSite($public_id){
        $daoSite = Denko::daoFactory('innydb_site');
        $daoSite->public_id = $public_id;
        $daoSite->whereAdd("status = '1' OR status = '3'");
        $found = $daoSite->find();
        if($found){
            $_SESSION[self::$session_inny_cms][self::$session_inny_site] = array(
                'public_id' => $public_id
            );
            self::updateSiteSession();
        }
    }

    public static function updateSiteSession(){
        $public_id = self::getSiteProperty("public_id");
        if(!$public_id) return;
        $daoSite = Denko::daoFactory('innydb_site');
        $daoSite->public_id = $public_id;
        $found = $daoSite->find(true);
        if($found){
            $_SESSION[self::$session_inny_cms][self::$session_inny_site]['id_site'] = $daoSite->id_site;
            $_SESSION[self::$session_inny_cms][self::$session_inny_site]['name'] = $daoSite->name;
            $_SESSION[self::$session_inny_cms][self::$session_inny_site]['public_id'] = $daoSite->public_id;
            $_SESSION[self::$session_inny_cms][self::$session_inny_site]['url'] = $daoSite->url;
            $_SESSION[self::$session_inny_cms][self::$session_inny_site]['metadata'] = json_decode($daoSite->metadata,true);
            $_SESSION[self::$session_inny_cms][self::$session_inny_site]['configs'] = json_decode($daoSite->configs,true);
            $_SESSION[self::$session_inny_cms][self::$session_inny_site]['status'] = $daoSite->status;
            $_SESSION[self::$session_inny_cms][self::$session_inny_site]['user_permission'] = array();

            $daoUserSite = Denko::daoFactory('innydb_user_site');
            $daoUserSite->id_user = self::getUserProperty("username");
            $daoUserSite->id_site = self::getSiteProperty("public_id");
            $daoUserSite->status = "1";
            if($daoUserSite->find(true)) {
                $_SESSION[self::$session_inny_cms][self::$session_inny_site]['user_permission'] = json_decode($daoUserSite->permission,true);
            }
        }
    }

    public static function setLockedUser(){
        $_SESSION[self::$session_inny_cms][self::$session_inny_user]['locked'] = true;
    }

    public static function setUnlockedUser(){
        $_SESSION[self::$session_inny_cms][self::$session_inny_user]['locked'] = false;
    }

    public static function unlockUser($password){
        if(!InnyCMS::isLoggedUser()){
            $result = array();
            $result['status'] = 0;
            $result['message'] = _t("You have been logged out.");
            $result['redirect'] = "./logout";
            return $result;
        }

        $unlocked = self::checkUserPassword($password);
        if($unlocked['status'] == 1) {
            InnyCMS::setUnlockedUser();
            $unlocked['message'] = _t("Welcome back!");
            $unlocked['redirect'] = "./";
        }

        return $unlocked;
    }

    public static function logOut(){
        $_SESSION[self::$session_inny_cms] = array();
    }

    public static function logOutSite(){
        $_SESSION[self::$session_inny_cms][self::$session_inny_site] = array();
    }

    /************************************************************************************************/
    /******************************************************** RETRIEVE SESSION INFO *****************/
    /************************************************************************************************/

    public static function getUserProperty($prop){
        $returnValue = isset($_SESSION[self::$session_inny_cms][self::$session_inny_user]) ? isset($_SESSION[self::$session_inny_cms][self::$session_inny_user][$prop]) ? $_SESSION[self::$session_inny_cms][self::$session_inny_user][$prop] : null : null;
        if(!$returnValue) $returnValue = isset($_SESSION[self::$session_inny_admin][self::$session_inny_user]) ? isset($_SESSION[self::$session_inny_admin][self::$session_inny_user][$prop]) ? $_SESSION[self::$session_inny_admin][self::$session_inny_user][$prop] : null : null;
        return $returnValue;
    }

    public static function getUserId(){
        return self::getUserProperty("id_user");
    }

    public static function getSiteProperty($prop){
        return isset($_SESSION[self::$session_inny_cms][self::$session_inny_site]) ? isset($_SESSION[self::$session_inny_cms][self::$session_inny_site][$prop]) ? $_SESSION[self::$session_inny_cms][self::$session_inny_site][$prop] : null : null;
    }

    public static function getSiteUrl(){
        return self::getSiteProperty("url");
    }

    public static function getSiteName(){
        $name = self::getSiteProperty("name");
        return !empty($name) ? $name : _t("Welcome!");
    }

    public static function getSiteId(){
        // Retrieve info from session (CMS)
        $site_id = self::getSiteProperty("id_site");
        if($site_id) return $site_id;
        // Retrieve info from disk (Website)
        $siteMetadata = self::getSiteMetadata();
        $daoSite = Denko::daoFactory('innydb_site');
        $daoSite->public_id = $siteMetadata['public_id'];
        $found = $daoSite->find(true);
        if($found) return $daoSite->id_site;
        return null;
    }

    public static function getSitePublicId(){
        // Retrieve info from session (CMS)
        $id = self::getSiteProperty("public_id");
        if($id) return $id;
        // Retrieve info from disk (Website)
        $siteMetadata = self::getSiteMetadata();
        $daoSite = Denko::daoFactory('innydb_site');
        $daoSite->public_id = $siteMetadata['public_id'];
        $found = $daoSite->find(true);
        if($found) return $daoSite->public_id;
        return null;
    }

    public static function getCurrentSiteDao(){
        $daoSite = Denko::daoFactory('innydb_site');
        $id_site = self::getSiteId();
        if(!$id_site) return null;
        $daoSite->id_site = $id_site;
        $daoSite->find(true);
        return $daoSite;
    }

    /************************************************************************************************/
    /******************************************************** RECOVER USER PASSWORD *****************/
    /************************************************************************************************/

    public static function recoverPassword($username){
        // TODO: Implement password recovery
        $username = Denko::lower(Denko::trim($username));
        $result = array();
        $result['status'] = 1;
        $result['message'] = _t("Password recovery instructions has been sent to your email.");
        return $result;
    }

    /**************************************************************************************/
    /******************************************************** CMS GENERAL *****************/
    /**************************************************************************************/

    public static function getSidebarMetadata(){
        if(!isset($GLOBALS['sidebarMetadata'])){
            $metadata = self::getSiteProperty("metadata");
            if(!isset($metadata['sidebar'])) $GLOBALS['sidebarMetadata'] = array();
            if(self::adminUserLogged()){
                $GLOBALS['sidebarMetadata'] = $metadata['sidebar'];
            }else{
                $newMetadata = array();
                foreach($metadata['sidebar'] as $k => $m){
                    $meta = $m;
                    $meta['collection'] = array();
                    if($m['type'] == 'collection'){
                        foreach($m['collection'] as $c){
                            if(self::checkPermission("collection","list",$c)){
                                $meta['collection'][] = $c;
                            }
                        }
                    }
                    if(!empty($meta['collection'])){
                        $newMetadata[] = $meta;
                    }
                }
                $GLOBALS['sidebarMetadata'] = $newMetadata;
            }
        }
        return $GLOBALS['sidebarMetadata'];
    }

    public static function getUserSites($id_user = null){
        $sites = array();
        if(!$id_user) $id_user = self::getUserId();
        $daoUser = Denko::daoFactory('innydb_user');
        $found = $daoUser->get($id_user);
        if(!$found) return $sites;

        $daoUserSite = Denko::daoFactory('innydb_user_site');
        $daoUserSite->id_user = $daoUser->username;
        $daoUserSite->status = "1";
        $daoUserSite->find();

        while($daoUserSite->fetch()){
            $daoSite = Denko::daoFactory('innydb_site');
            $daoSite->public_id = $daoUserSite->id_site;
            $daoSite->whereAdd("status > 0");
            if($daoSite->find(true)){
                $site = array();
                $site['id_site'] = $daoSite->id_site;
                $site['public_id'] = $daoSite->public_id;
                $site['name'] = $daoSite->name;
                $site['url'] = $daoSite->url;
                $site['configs'] = json_decode($daoSite->configs,true);
                $site['status'] = $daoSite->status;
                $site['user_permission'] = json_decode($daoUserSite->permission,true);
                $sites[$daoSite->id_site] = $site;
                unset($site);
            }
            $daoSite->free();
        }
        return $sites;
    }

    /*******************************************************************************/
    /******************************************************** PERMISSION ***********/
    /*******************************************************************************/

    public static function checkPermission($resource,$action = null,$name = null,$field = null,$id = null){
        if(self::adminUserLogged()) return true;
        else {
        $permissions = self::getSiteProperty("user_permission");
            if(isset($permissions[$resource][$name]) && isset($permissions[$resource][$name]['actions']) && isset($permissions[$resource][$name]['actions'][$action]) && $permissions[$resource][$name]['actions'][$action] == true){
                if(isset($field) && isset($id)) {
                    if(isset($permissions[$resource][$name]['documents'][$field]) && !in_array($id,$permissions[$resource][$name]['documents'][$field])) return false;
                }
                return true;
            }
        }
        return false;
    }

    public static function adminUserLogged(){
        $currentUserRole = self::getUserProperty("role");
        if ($currentUserRole == self::$user_role_sysadmin) return true;
        if($currentUserRole == self::$user_role_siteadmin){
            $permissions = self::getSiteProperty("user_permission");
            if($permissions['role'] == self::$user_site_role_admin) return true;
        }

        return false;
    }

    /**********************************************************************************/
    /******************************************************** BUCKET ******************/
    /**********************************************************************************/

    public static function addFileToBucket($file){
        $siteId = self::getSitePublicId();
        return InnyBucket::setFromArray($file,$siteId);
    }

    public static function searchBucketFiles($tags = "",$type = null){
        $siteId = self::getSitePublicId();
        return InnyBucket::searchFiles($tags,$siteId,$type);
    }

    public static function addTagToBucketFile($bucketId,$tag){
        InnyBucket::addTag($bucketId,$tag);
    }

    public static function removeTagFromBucketFile($bucketId,$tag){
        InnyBucket::removeTag($bucketId,$tag);
    }

    public static function clearTagsFromBucketFile($bucketId){
        InnyBucket::clearTags($bucketId);
    }

    public static function getBucketDaoById($bucketId){
        $siteId = self::getSitePublicId();
        return InnyBucket::getDaoById($bucketId,$siteId);
    }

    public static function getBucketDao($hash){
        $siteId = self::getSitePublicId();
        return InnyBucket::getDao($hash,$siteId);
    }

    public static function getBucketFileInfo($bucketId){
        $siteId = self::getSitePublicId();
        return InnyBucket::getFileInfoById($bucketId,$siteId);
    }

    public static function getBucketFileUrl($key,$mode = "full",$additionalParams=array()){
        $siteId = self::getSitePublicId();
        return InnyBucket::getFileUrl($key,$siteId,$mode,$additionalParams);
    }

    /**
     * @deprecated deprecated since Jun 2019. Please use InnyCMS::deleteBucketFile()
     */
    public static function deleteBucketItem($bucketId){
        $siteId = self::getSitePublicId();
        return InnyBucket::deleteId($bucketId,$siteId);
    }

    public static function deleteBucketFile($bucketId){
        $siteId = self::getSitePublicId();
        return InnyBucket::deleteId($bucketId,$siteId);
    }

    /**
     * @deprecated deprecated since Jun 2019. Please use InnyCMS::getBucketFileTags()
     */
    public static function getBucketItemTags($bucketDao){
        return InnyBucket::getPublicTags($bucketDao);
    }

    public static function getBucketFileTags($bucketDao){
        return InnyBucket::getPublicTags($bucketDao);
    }

    public static function cleanBucket(){
        $siteId = self::getSitePublicId();
        return InnyBucket::cleanSet($siteId);
    }

    public static function emptyBucket(){
        $siteId = self::getSitePublicId();
        return InnyBucket::cleanSet($siteId,true);
    }

    public static function fileIdExists($id_bucket){
        $siteId = self::getSitePublicId();
        return (InnyBucket::getDaoById($id_bucket,$siteId)) ? true : false;
    }

    public static function fileExists($hash){
        $siteId = self::getSitePublicId();
        return (InnyBucket::getDao($hash,$siteId)) ? true : false;
    }

    public static function getBucketStats($tags = "",$type = null){
        $siteId = self::getSitePublicId();
        $daoBucket = InnyBucket::searchFiles($tags,$siteId,$type);

        $daoBucketC = clone $daoBucket;
        $count = $daoBucketC->count();
        $daoBucketC->free();

        $daoBucketS = clone $daoBucket;
        $daoBucketS->selectAdd();
        $daoBucketS->selectAdd("sum(size) as tam");
        $daoBucketS->find(true);
        $filesSize = $daoBucketS->tam;
        $daoBucketS->free();

        $daoBucketGrouped = clone $daoBucket;
        $daoBucketGrouped->selectAdd();
        $daoBucketGrouped->selectAdd("type");
        $daoBucketGrouped->selectAdd("sum(size) as type_size");
        $daoBucketGrouped->selectAdd("count(*) as type_count ");
        $daoBucketGrouped->groupBy("type");
        $daoBucketGrouped->orderBy();
        $daoBucketGrouped->orderBy("type_size DESC");
        $daoBucketGrouped->find();

        $bucketSize = 10000000;
        $used = ceil(($filesSize * 100) / $bucketSize);
        $free = floor((($bucketSize - $filesSize) * 100 )/ $bucketSize);
        $stats = array();
        $stats['filesCount'] = $count;
        $stats['bucketSize'] = $bucketSize;
        $stats['filesSize'] = $filesSize;
        $stats['freeSize'] = $bucketSize - $filesSize;
        $stats['usedSpacePercentage'] = ($used > 100) ? 100 : $used;
        $stats['freeSpacePercentage'] = ($free < 0) ? 0 : $free;

        while($daoBucketGrouped->fetch()){
            $group = array();
            $group['filesCount'] = $daoBucketGrouped->type_count;
            $group['filesSize'] = $daoBucketGrouped->type_size;
            $group['diff'] = $filesSize - $daoBucketGrouped->type_size;
            $stats['grouped'][$daoBucketGrouped->type] = $group;
        }

        $daoBucketGrouped->free();

        return $stats;
    }

    public static function updateBucketQuantities($documentPublicId,$collectionName,$existingFiles,$uploadedFiles){

        if(!isset($existingFiles)) $existingFiles = array();
        if(!isset($uploadedFiles)) $uploadedFiles = array();
        $oldFiles = array();
        foreach ($existingFiles as $fileFields){
            $json = json_decode($fileFields,true);
            if(is_array($json)){
                // Multilang
                foreach ($json as $files){
                    $xp = explode(",",$files);
                    foreach ($xp as $x) $oldFiles[] = $x;
                }
            }else{
                $xp = explode(",",$fileFields);
                foreach ($xp as $x) $oldFiles[] = $x;
            }
        }

        $newFiles = array();
        foreach ($uploadedFiles as $fileFields){
            $json = json_decode($fileFields,true);
            if(is_array($json)){
                // Multilang
                foreach ($json as $files){
                    $xp = explode(",",$files);
                    foreach ($xp as $x) $newFiles[] = $x;
                }
            }else{
                $xp = explode(",",$fileFields);
                foreach ($xp as $x) $newFiles[] = $x;
            }
        }

        $oldCount = array_count_values($oldFiles);
        $newCount = array_count_values($newFiles);
        $increment = array();
        $decrement = array();

        foreach ($oldCount as $key => $value) {
            if(isset($newCount[$key])) {
                $diferencia = $oldCount[$key] - $newCount[$key];
                if($diferencia > 0) $decrement[$key] = $diferencia;
                else if($diferencia < 0) $increment[$key] = -1 * $diferencia;
                unset($newCount[$key]);
            }else{
                $decrement[$key] = $value;
            }
        }

        foreach ($newCount as $key => $value){
            $increment[$key] = $value;
        }

        $siteId = self::getSitePublicId();

        InnyBucket::incrementFileCount($increment,$documentPublicId,$collectionName,$siteId);
        InnyBucket::decrementFileCount($decrement,$documentPublicId,$collectionName,$siteId);
    }

    /**********************************************************************************/
    /******************************************************** COLLECTION **************/
    /**********************************************************************************/

    public static function getCollectionDao($collectionName){
        $siteName = self::getSitePublicId();
        return InnyCollection::get($collectionName,$siteName);
    }

    public static function drawCollection($params){
        // Used by Datatables
        $siteName = self::getSitePublicId();
        return InnyCollection::getDocuments($params,$siteName);
    }

    public static function getCollectionListingFields($collectionName){
        $siteName = self::getSitePublicId();
        return InnyCollection::getListingFields($collectionName,$siteName);
    }

    public static function emptyCollection($collectionName){
        $siteName = self::getSitePublicId();
        if(!InnyCollection::exists($collectionName,$siteName)) return null;
        return InnyCollection::emptyCollection($collectionName,$siteName);
    }

    public static function downloadCollection($collectionName){
        $siteName = self::getSitePublicId();
        if(!InnyCollection::exists($collectionName,$siteName)) return null;
        InnyCollection::download($collectionName,$siteName);
    }

    public static function uploadCollection($collectionName,$files){
        $siteName = self::getSitePublicId();
        if(!InnyCollection::exists($collectionName,$siteName)) return null;
        return InnyCollection::upload($collectionName,$siteName,$files);
    }

    public static function exportCollection($collectionName,$format = "json"){
        $siteName = self::getSitePublicId();
        if(!InnyCollection::exists($collectionName,$siteName)) return null;
        InnyCollection::export($collectionName,$siteName,$format);
    }

    public static function importCollection($collectionName,$files){
        $siteName = self::getSitePublicId();
        if(!InnyCollection::exists($collectionName,$siteName)) return null;
        return InnyCollection::import($collectionName,$siteName,$files);
    }

    /**********************************************************************************/
    /******************************************************** DOCUMENT ****************/
    /**********************************************************************************/

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::getDocument()
     */
    public static function getItem($documentPublicId,$collectionName){
        return self::getDocument($documentPublicId,$collectionName);
    }

    public static function getDocument($documentPublicId,$collectionName){
        $siteName = self::getSitePublicId();
        return InnyDocument::get($documentPublicId,$collectionName,$siteName);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::getOneDocument()
     */
    public static function getOneItem($collectionName,$order = "desc"){
        return self::getOneDocument($collectionName,$order);
    }

    public static function getOneDocument($collectionName,$order = "desc"){
        $siteName = self::getSitePublicId();
        return InnyDocument::getOne($collectionName,$siteName,self::$document_status_published,$order);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::getAllDocuments()
     */
    public static function getAllItems($collectionName,$order = "desc"){
        return self::getAllDocuments($collectionName,$order);
    }

    public static function getAllDocuments($collectionName,$order = "desc"){
        $siteName = self::getSitePublicId();
        return InnyDocument::getAll($collectionName,$siteName,self::$document_status_published,$order);
    }

    // ---

    /**
     * @deprecated deprecated since Feb 2019. Please use InnyCMS::getOneDocument() or InnyCMS::getAllDocuments()
     */
    public static function getItems($collectionName,$autofetch = false,$order = "desc"){
        $siteName = self::getSitePublicId();
        if($autofetch) return InnyDocument::getOne($collectionName,$siteName,self::$document_status_published,$order);
        else return InnyDocument::getAll($collectionName,$siteName,self::$document_status_published,$order);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::getOneDocumentByField()
     */
    public static function getOneItemByField($fieldName,$fieldValue,$collectionName,$order = "desc"){
        return self::getOneDocumentByField($fieldName,$fieldValue,$collectionName,$order);
    }

    public static function getOneDocumentByField($fieldName,$fieldValue,$collectionName,$order = "desc"){
        $siteName = self::getSitePublicId();
        return InnyDocument::getOneByField($fieldName,$fieldValue,$collectionName,$siteName,self::$document_status_published,$order);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::getAllDocumentsByField()
     */
    public static function getAllItemsByField($fieldName,$fieldValue,$collectionName,$order = "desc"){
        return self::getAllDocumentsByField($fieldName,$fieldValue,$collectionName,$order);
    }

    public static function getAllDocumentsByField($fieldName,$fieldValue,$collectionName,$order = "desc"){
        $siteName = self::getSitePublicId();
        return InnyDocument::getAllByField($fieldName,$fieldValue,$collectionName,$siteName,self::$document_status_published,$order);
    }

    // ---

    /**
     * @deprecated deprecated since Feb 2019. Please use InnyCMS::getOneDocumentByField() or InnyCMS::getAllDocumentsByField()
     */
    public static function getItemByField($fieldName,$fieldValue,$collectionName,$autofetch = false){
        $siteName = self::getSitePublicId();
        if($autofetch) return InnyDocument::getOneByField($fieldName,$fieldValue,$collectionName,$siteName,self::$document_status_published);
        else return InnyDocument::getAllByField($fieldName,$fieldValue,$collectionName,$siteName,self::$document_status_published);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::addDocument()
     */
    public static function addItem($collectionName,$data){
        return self::addDocument($collectionName,$data);
    }

    public static function addDocument($collectionName,$data){
        $siteName = self::getSitePublicId();
        if(!InnyCollection::exists($collectionName,$siteName)) return null;
        return InnyDocument::add($collectionName,$siteName,$data);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::editDocument()
     */
    public static function editItem($documentPublicId,$collectionName,$data){
        return self::editDocument($documentPublicId,$collectionName,$data);
    }

    public static function editDocument($documentPublicId,$collectionName,$data){
        $siteName = self::getSitePublicId();
        if(!InnyCollection::exists($collectionName,$siteName)) return null;
        if(!InnyCollection::contains($documentPublicId,$collectionName,$siteName)) return null;
        return InnyDocument::edit($documentPublicId,$collectionName,$siteName,$data);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::deleteDocument()
     */
    public static function deleteItem($documentPublicId,$collectionName){
        return self::deleteDocument($documentPublicId,$collectionName);
    }

    public static function deleteDocument($documentPublicId,$collectionName){
        $siteName = self::getSitePublicId();
        return InnyDocument::delete($documentPublicId,$collectionName,$siteName);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::moveDocumentUp()
     */
    public static function moveItemUp($documentPublicId,$collectionName){
        return self::moveDocumentUp($documentPublicId,$collectionName);
    }

    public static function moveDocumentUp($documentPublicId,$collectionName){
        $siteName = self::getSitePublicId();
        return InnyDocument::moveUp($documentPublicId,$collectionName,$siteName);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::moveDocumentDown()
     */
    public static function moveItemDown($documentPublicId,$collectionName){
        return self::moveDocumentDown($documentPublicId,$collectionName);
    }

    public static function moveDocumentDown($documentPublicId,$collectionName){
        $siteName = self::getSitePublicId();
        return InnyDocument::moveDown($documentPublicId,$collectionName,$siteName);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::publishDocument()
     */
    public static function publishItem($documentPublicId,$collectionName){
        return self::publishDocument($documentPublicId,$collectionName);
    }

    public static function publishDocument($documentPublicId,$collectionName){
        $siteName = self::getSitePublicId();
        return InnyDocument::publish($documentPublicId,$collectionName,$siteName);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::unpublishDocument()
     */
    public static function unpublishItem($documentPublicId,$collectionName){
        return self::unpublishDocument($documentPublicId,$collectionName);
    }

    public static function unpublishDocument($documentPublicId,$collectionName){
        $siteName = self::getSitePublicId();
        return InnyDocument::unpublish($documentPublicId,$collectionName,$siteName);
    }

    // ---

    /**
     * @deprecated deprecated since March 2019. Please use InnyCMS::searchDocuments()
     */
    public static function searchItems($collectionName,$searchOptions=array(),$additionalParams=array()){
        return self::searchDocuments($collectionName,$searchOptions,$additionalParams);
    }

    public static function searchDocuments($collectionName,$searchOptions=array(),$additionalParams=array()){
        $siteName = self::getSitePublicId();
        return InnyDocument::searchDocuments($collectionName,$siteName,$searchOptions,$additionalParams);
    }

    /**********************************************************************************/
    /******************************************************** TYPES *******************/
    /**********************************************************************************/

    public static function createInnyType($params){
        $tipo = isset($params['type']) ? $params['type'] : "text";

        $innyType = InnyType::factory($tipo);

        $innyType->setMetadata($params);

        # Retorno el elemento
        return $innyType;
    }

    public static function createInnyTypes($daoCollection,$daoDocument = null,$unsafeMode = false){
        $metadata = json_decode($daoCollection->metadata,true);
        $fields = isset($metadata['fields']) ? $metadata['fields'] : array();
        $files = isset($metadata['files']) ? $metadata['files'] : array();
        $innyTypes = array();
        unset($metadata);

        if(isset($daoDocument) && !empty($daoDocument->draft)) $daoDocument->setFrom(json_decode($daoDocument->draft,true));

        $storedFiles = isset($daoDocument) ? json_decode($daoDocument->files,true) : array();

        foreach ($files as $file => $params){
            $params['field'] = $file;
            $innyType = self::createInnyType($params);
            if($unsafeMode) $innyType->setUnsafeMode();
            if(isset($storedFiles[$file])){
                if(is_array($storedFiles[$file]) && sizeof($storedFiles[$file]) > 1){
                    $innyType->setValue(json_encode($storedFiles[$file]));
                } else {
                    $innyType->setValue($storedFiles[$file]);
                }
            }
            $innyTypes[] = $innyType;
        }

        foreach ($fields as $field => $params) {
            $params['field'] = $field;
            $innyType = self::createInnyType($params);
            if($unsafeMode) $innyType->setUnsafeMode();
            if($daoDocument && isset($daoDocument->$field)) $innyType->setValue($daoDocument->$field);
            $innyTypes[] = $innyType;
        }

        return $innyTypes;
    }

    /*************************************************************************************/
    /******************************************************** USERS API ******************/
    /*************************************************************************************/

    public static function userExists($username){
        $daoUser = Denko::daoFactory("innydb_user");
        $daoUser->username = $username;
        $found = $daoUser->find(true);
        $daoUser->free();
        return $found;
    }

    public static function getUser($username){
        $daoUser = Denko::daoFactory("innydb_user");
        $daoUser->username = $username;
        $daoUser->find(true);
        return $daoUser;
    }

    public static function createUser($username,$password,$name,$lastname,$email,$role = "siteadmin",$status = "1"){
        if(self::userExists($username)) return false;
        $daoUser = Denko::daoFactory('innydb_user');
        $daoUser->username = $username;
        $daoUser->password = $password;
        $daoUser->name = $name;
        $daoUser->lastname = $lastname;
        $daoUser->email = $email;
        $daoUser->role = $role;
        $daoUser->status = $status;
        $id_user = $daoUser->insert();
        if($id_user) return true;
        return false;
    }

    public static function editUser($username,$properties){
        if(!self::userExists($username)) return false;
        $daoUser = self::getUser($username);
        $daoClone = clone($daoUser);
        foreach ($properties as $key => $property) {
            if(!isset($daoUser->$key)) continue;
            $daoUser->$key = $property;
        }
        $updated = $daoUser->update($daoClone);
        $daoUser->free();
        return $updated;
    }

    public static function disableUsers($role = "siteadmin"){
        $daoUser = Denko::daoFactory("innydb_user");
        $daoUser->role = $role;
        $daoUser->find();
        while($daoUser->fetch()){
            $daoClone = clone($daoUser);
            $daoUser->status = 0;
            $daoUser->update($daoClone);
        }
    }

    public static function unassignAllUsers($sitePublicId, $role = "siteadmin"){
        $daoUser = Denko::daoFactory("innydb_user");
        $daoUser->role = $role;
        $daoUser->find();
        while($daoUser->fetch()){
            $daoUserSite = Denko::daoFactory("innydb_user_site");
            $daoUserSite->id_user = $daoUser->username;
            $daoUserSite->id_site = $sitePublicId;
            $daoUserSite->find(true);
            $daoUserSite->status = 0;
            $daoUserSite->update();
        }
    }

    /*************************************************************************************/
    /******************************************************** SITES API ******************/
    /*************************************************************************************/

    public static function siteExists($public_id){
        $daoSite = Denko::daoFactory("innydb_user");
        $daoSite->public_id = $public_id;
        $found = $daoSite->find(true);
        $daoSite->free();
        return $found;
    }

    public static function getSite($public_id){
        $daoSite = Denko::daoFactory("innydb_site");
        $daoSite->public_id = $public_id;
        $daoSite->find(true);
        return $daoSite;
    }

    public static function assignUserSite($username,$sitePublicId,$newPermissions = array("role" => "admin","collection" => null)){
        if(!self::userExists($username)) return false;
        if(!self::siteExists($sitePublicId)) return false;
        $daoUserSite = Denko::daoFactory('innydb_user_site');
        $daoUserSite->id_user = $username;
        $daoUserSite->id_site = $sitePublicId;
        if($daoUserSite->find(true)){
            $daoUserSite->status = "1";
            if(!empty($newPermissions)) $daoUserSite->permission = json_encode($newPermissions);
            $done = $daoUserSite->update();
        } else {
            $daoUserSite->status = "1";
            $daoUserSite->permission = json_encode($newPermissions);
            $done = $daoUserSite->insert();
        }

        return $done;
    }

    public static function unassignUserSite($username,$sitePublicId){
        if(!self::userExists($username)) return false;
        if(!self::siteExists($sitePublicId)) return false;
        $daoUserSite = Denko::daoFactory('innydb_user_site');
        $daoUserSite->id_user = $username;
        $daoUserSite->id_site = $sitePublicId;
        if($daoUserSite->find(true)){
            $daoUserSite->status = "0";
            if(empty($daoUserSite->permission) || $daoUserSite->permission == "[]") $daoUserSite->permission = json_encode(array("role" => "admin","collection" => null));
            $done = $daoUserSite->update();
        } else {
            $daoUserSite->status = "0";
            $daoUserSite->permission = json_encode(array("role" => "admin","collection" => null));
            $done = $daoUserSite->insert();
        }

        return $done;
    }
    /**********************************************************************************/
    /******************************************************** BASE ********************/
    /**********************************************************************************/

    /**
     * Gets the site metadata from disk
     *
     * @static
     * @access public
     * @return array
     */
    public static function getMetadataFromDisk(){
        $metadataFile = file_get_contents(self::$session_metadata_path);
        $metadata = json_decode($metadataFile,true);
        return isset($metadata) ? $metadata : array();
    }

    /**
     * Gets the site metadata from database or disk
     *
     * @static
     * @access public
     * @return array
     */
    public static function getSiteMetadata(){
        // Should also check the file if it is on the disk?
        if(!isset($GLOBALS[self::$session_metadata])){
            if(self::isLoggedSite()){
                $metadata = self::getSiteProperty("metadata");
                $GLOBALS[self::$session_metadata] = isset($metadata) ? $metadata : array();
            }else{
                $GLOBALS[self::$session_metadata] = self::getMetadataFromDisk();
            }
        }
        return $GLOBALS[self::$session_metadata];
    }

    /*******************************************************************************/
    /******************************************************** SYSADMIN *************/
    /*******************************************************************************/

    public static function getAdminUserProperty($prop){
        return isset($_SESSION[self::$session_inny_admin][self::$session_inny_user]) ? isset($_SESSION[self::$session_inny_admin][self::$session_inny_user][$prop]) ? $_SESSION[self::$session_inny_admin][self::$session_inny_user][$prop] : null : null;
    }

    public static function getAdminUserId(){
        return self::getAdminUserProperty("id_user");
    }

    public static function setAdminUserId($id_user){
        $daoUser = Denko::daoFactory('innydb_user');
        $daoUser->id_user = $id_user;
        $daoUser->status = "1";
        $daoUser->role = "sysadmin";
        $found = $daoUser->find();
        if($found){
            $_SESSION[self::$session_inny_admin][self::$session_inny_user] = array(
                'id_user' => $id_user
            );
            self::updateAdminUserSession();
        }
    }

    public static function updateAdminUserSession(){
        $id_user = self::getAdminUserId();
        if(!$id_user) return;
        $daoUser = Denko::daoFactory('innydb_user');
        $found = $daoUser->get($id_user);
        if($found) {
            $_SESSION[self::$session_inny_admin][self::$session_inny_user]['username'] = $daoUser->username;
            $_SESSION[self::$session_inny_admin][self::$session_inny_user]['name'] = $daoUser->name;
            $_SESSION[self::$session_inny_admin][self::$session_inny_user]['lastname'] = $daoUser->lastname;
            $_SESSION[self::$session_inny_admin][self::$session_inny_user]['email'] = $daoUser->email;
            $_SESSION[self::$session_inny_admin][self::$session_inny_user]['role'] = $daoUser->role;
            $_SESSION[self::$session_inny_admin][self::$session_inny_user]['status'] = $daoUser->status;
            $_SESSION[self::$session_inny_admin][self::$session_inny_user]['locked'] = false;
        }
    }

    public static function isLoggedAdmin(){
        return (isset($_SESSION[self::$session_inny_admin]) && isset($_SESSION[self::$session_inny_admin][self::$session_inny_user]) && is_array($_SESSION[self::$session_inny_admin][self::$session_inny_user]) && !empty($_SESSION[self::$session_inny_admin][self::$session_inny_user]['id_user']) && isset($_SESSION[self::$session_inny_admin][self::$session_inny_user]['status']) && $_SESSION[self::$session_inny_admin][self::$session_inny_user]['status'] == "1" && isset($_SESSION[self::$session_inny_admin][self::$session_inny_user]['role']) && $_SESSION[self::$session_inny_admin][self::$session_inny_user]['role'] == "sysadmin");
    }

    public static function checkAdminLogIn(){
        self::updateAdminUserSession();

        if(!InnyCMS::isLoggedAdmin()){
            Denko::redirect('./logout');
        }
    }

    public static function logInAdmin($username,$password,$remember = false){
        $username = Denko::lower(Denko::trim($username));
        $result = array();
        $result['status'] = 0;

        # Verifico que haya ingresado el username
        if(empty($username)){
            $result['message'] = "Username is required.";
            return $result;
        }

        # Verifico que haya ingresado el password
        if(empty($password)){
            $result['message'] = "Password is required.";
            return $result;
        }

        # En caso que no haya errores, verifico que los datos sean válidos

        $daoUser = Denko::daoFactory('innydb_user');
        $daoUser->username = $username;

        # En caso que el usuario exista, verifico su password
        if(!$daoUser->find(true) || !password_verify($password, $daoUser->password)){
            $result['message'] = "Incorrect username or password.";
            return $result;
        }

        if($daoUser->status != '1') {
            $result['message'] = "Your account has been disabled or deleted.";
            return $result;
        }

        if($daoUser->role != 'sysadmin') {
            $result['message'] = "Your account has no access to this area.";
            return $result;
        }

        # Seteo las variables en sesion
        InnyCMS::setAdminUserId($daoUser->id_user);

        $result['status'] = 1;
        $result['message'] = "Welcome ".InnyCMS::getUserProperty("name")."!";

        return $result;
    }

    public static function logOutAdmin(){
        $_SESSION[self::$session_inny_admin] = array();
    }

    /*****************************************************************************************/
    /******************************************************** OTHER FUNCTIONS ****************/
    /*****************************************************************************************/

    public static function createUniqueId($tableName,$insertColum,$groupColumn = null,$valueGroupColumn = null){
        $dao = Denko::daoFactory($tableName);
        if(isset($groupColumn) && !empty($groupColumn)&& isset($valueGroupColumn)) $dao->$groupColumn = $valueGroupColumn;
        $dao->limit(1);

        do {
            $hash = strtoupper(md5(microtime(true)));
            $rand = rand(0,22);
            $id = substr($hash,$rand,10);
            $dao->$insertColum = $id;
            $found = $dao->count()==0;
        }while(!$found);

        $dao->free();

        return $id;
    }

    public static function mysqlEscape($value) {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
    }

}
