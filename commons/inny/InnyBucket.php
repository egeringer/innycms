<?php

/**
 * Created by PhpStorm.
 * User: egeringer
 * Date: 11/4/17
 * Time: 18:35
 */
class InnyBucket {

    private static $private_prefix = "bucket_tag_";

    private static $set_id_prefix = "set_id_";
    private static $hash_prefix = "hash_";
    private static $type_prefix = "type_";
    private static $name_prefix = "name_";


    private static function fileExists($filePath,$set_id=null){
        // Checks if the binary file is already in the set
        $tag = self::makePrivateTag(self::makeHashTag(self::hashFile($filePath)));
        $daoBucket = self::_searchFiles($tag,$set_id);
        $daoBucket->fetch();
        return $daoBucket->id_bucket;
    }

    /**********************************************************************************/
    /******************************************************** SET FILES ***************/
    /**********************************************************************************/

    public static function setFromData($data,$set_id=null,$size=null,$name=null){
        if(!$data) return null;

        $hash = self::hashData($data);

        $words = explode('.', $name);
        $extension = array_pop($words);
        $first_chunk = implode('-', $words);
        $name = Denko::str_to_friendlyUrl($first_chunk).".".$extension;

        $daoBucket = Denko::daoFactory('innydb_bucket');
        $daoBucket->data = $data;
        $daoBucket->size = $size;
        $daoBucket->name = $name;
        $daoBucket->hash = $hash;
        $daoBucket->count = 1;

        $id_bucket = $daoBucket->insert();

        self::addPrivateTag($id_bucket,self::makeHashTag($hash));
        if($set_id) self::addPrivateTag($id_bucket,self::makeSetIdTag($set_id));

        return $id_bucket;
    }

    public static function setFromFile($fileName,$set_id=null){
        $fd = fopen($fileName, 'r');
        $data = fread($fd,filesize($fileName));
        $hash = self::hashFile($fileName);

        $nameArr = explode("/",$fileName);

        $words = explode('.', $nameArr[count($nameArr)-1]);
        $extension = array_pop($words);
        $first_chunk = implode('-', $words);
        $name = Denko::str_to_friendlyUrl($first_chunk).".".$extension;


        $daoBucket = Denko::daoFactory('innydb_bucket');
        $daoBucket->data = $data;
        $daoBucket->name = $name;
        $daoBucket->hash = $hash;
        $daoBucket->count = 1;

        $id_bucket = $daoBucket->insert();

        $daoBucket->free();
        fclose($fd);
        unset($data);

        self::addPrivateTag($id_bucket,self::makeHashTag($hash));
        self::addPrivateTag($id_bucket,self::makeNameTag($name));
        if($set_id) self::addPrivateTag($id_bucket,self::makeSetIdTag($set_id));

        return $id_bucket;
    }

    public static function setFromArray($fileData,$set_id=null){

        $readBytes = 8000000;

        // We have to check if the file exists on the given set
        if(isset($set_id)){
            $id_bucket = self::fileExists($fileData['tmp_name'],$set_id);
            if($id_bucket) return $id_bucket;
        }

        // Insert Bucket Dao
        $hash = self::hashFile($fileData['tmp_name']);
        $type = self::getTypeFromMime($fileData['type']);

        $words = explode('.', $fileData['name']);
        $extension = array_pop($words);
        $first_chunk = implode('-', $words);
        $name = Denko::str_to_friendlyUrl($first_chunk).".".$extension;

        $daoBucket = Denko::daoFactory('innydb_bucket');
        $daoBucket->size = $fileData['size'];
        $daoBucket->name = $name;
        $daoBucket->mime = $fileData['type'];
        $daoBucket->type = $type;
        $daoBucket->hash = $hash;
        $daoBucket->count = 0;

        $id_bucket = $daoBucket->insert();

        $daoBucket->free();

        // Process Chunks
        $fd = fopen($fileData['tmp_name'], 'r');
        $prev_chunk = null;
        while ($data = fread($fd, $readBytes)) {
            $chunk = Denko::daoFactory('innydb_bucket_chunk');
            $chunk->data = $data;
            $chunk->id_bucket = $id_bucket;
            $id = $chunk->insert();
            if(isset($prev_chunk)){
                $prev_chunk->next_chunk = $id;
                $prev_chunk->update();
                $prev_chunk->free();
            }
            $prev_chunk = clone($chunk);
            unset($chunk);
            unset ($data);
        }
        unset($prev_chunk);
        fclose($fd);

        self::addPrivateTag($id_bucket,self::makeHashTag($hash));
        self::addPrivateTag($id_bucket,self::makeTypeTag($type));
        self::addPrivateTag($id_bucket,self::makeNameTag($name));
        if($set_id) self::addPrivateTag($id_bucket,self::makeSetIdTag($set_id));

        return $id_bucket;
    }

    /**********************************************************************************/
    /******************************************************** GET DAOs ****************/
    /**********************************************************************************/

    public static function getDaoById($id_bucket,$set_id = null){
        $daoBucket = Denko::daoFactory('innydb_bucket');
        $daoBucket->id_bucket = $id_bucket;
        if($set_id){
            $set_id_tag = self::makePrivateTag(self::makeSetIdTag($set_id));
            $daoBucket->whereAdd('MATCH(tags) AGAINST(\''. $set_id_tag .'\')');
        }
        if($daoBucket->find(true)) return $daoBucket;
        $daoBucket->free();
        return null;
    }

    public static function getDao($hash,$set_id){
        $daoBucket = Denko::daoFactory('innydb_bucket');
        $hash_tag = self::makePrivateTag(self::makeHashTag($hash));
        $set_id_tag = self::makePrivateTag(self::makeSetIdTag($set_id));
        $daoBucket->whereAdd('MATCH(tags) AGAINST(\''. $set_id_tag .'\')');
        $daoBucket->whereAdd('MATCH(tags) AGAINST(\''. $hash_tag .'\')');
        if($daoBucket->find(true)) return $daoBucket;
        $daoBucket->free();
        return null;
    }

    /*************************************************************************************/
    /******************************************************** GET FILE INFO **************/
    /*************************************************************************************/

    public static function getFileInfoById($id_bucket,$set_id = null){
        $daoBucket = self::getDaoById($id_bucket,$set_id);

        if(!$daoBucket) return null;

        $data = array();
        $data['id_bucket'] = $daoBucket->id_bucket;
        $data['size'] = $daoBucket->size;
        $data['name'] = $daoBucket->name;
        $data['mime'] = $daoBucket->mime;
        $data['type'] = $daoBucket->type;
        $data['count'] = $daoBucket->count;
        $data['hash'] = $daoBucket->hash;
        $data['aud_ins_date'] = $daoBucket->aud_ins_date;
        $data['aud_upd_date'] = $daoBucket->aud_upd_date;
        $data['full_url'] = self::getFileUrl($daoBucket, $set_id,"full");
        $data['preview_url'] = self::getFileUrl($daoBucket, $set_id,"preview");
        $data['download_url'] = self::getFileUrl($daoBucket, $set_id,"download");
        $daoBucket->free();

        return $data;
    }

    /*************************************************************************************/
    /******************************************************** DELETE FILES ***************/
    /*************************************************************************************/

    public static function deleteId($id_bucket,$set_id){
        $daoBucket = self::getDaoById($id_bucket,$set_id);
        if ($daoBucket && $daoBucket->count == 0) return $daoBucket->delete();
        return null;
    }

    public static function cleanSet($set_id,$forceAll=false){
        $daoBucket = Denko::daoFactory('innydb_bucket');
        $set_id_tag = self::makePrivateTag(self::makeSetIdTag($set_id));
        if(!$forceAll) $daoBucket->count = 0;
        $daoBucket->whereAdd('MATCH(tags) AGAINST(\''. $set_id_tag .'\')');
        $count = $daoBucket->find();
        while($daoBucket->fetch()){
            $daoBucket->delete();
        }
        return $count;

    }

    /**********************************************************************************/
    /******************************************************** SEARCH ******************/
    /**********************************************************************************/

    private static function _searchFiles($q = "",$set_id=null,$type = null){
        $daoBucket = Denko::daoFactory('innydb_bucket');

        // Force AND
        if(!empty($q)) {
            $tags = explode(" ", $q);
            foreach ($tags as $tag) {
                $daoBucket->whereAdd('MATCH(tags) AGAINST(\'' . $tag . '\')');
            }
        }

        // This line allowed OR
        //if(!empty($q)) $daoBucket->whereAdd('MATCH(tags) AGAINST(\''.$q.'\')');
        if(isset($type) && !empty($type)) {
            $t = self::makePrivateTag(self::makeTypeTag($type));
            $daoBucket->whereAdd('MATCH(tags) AGAINST(\'' . $t . '\')');
        }
        $set_id_tag = self::makePrivateTag(self::makeSetIdTag($set_id));
        $daoBucket->whereAdd('MATCH(tags) AGAINST(\''. $set_id_tag .'\')');
        $daoBucket->orderBy("aud_upd_date DESC");
        $daoBucket->limit(100);
        $daoBucket->find();

        return $daoBucket;
    }

    public static function searchFiles($tags = "",$set_id=null,$type = null){
        $tags = self::clearPrivateTags($tags);
        return self::_searchFiles($tags,$set_id,$type);
    }

    /**********************************************************************************/
    /******************************************************** TAGS ********************/
    /**********************************************************************************/

    private static function makePrivateTag($tag){
        return self::$private_prefix.$tag;
    }

    private static function clearPrivateTags($tag){
        $tag = preg_replace('/bucket_tag_\S+/', '', $tag);
        return $tag;
    }

    public static function addPrivateTag($id_bucket,$tag){
        $daoBucket = self::getDaoById($id_bucket,null);
        if($daoBucket){
            $tag = str_replace(" ","-",$tag);
            $tag = self::makePrivateTag($tag);
            $original = clone($daoBucket);
            $daoBucket->tags = Denko::trim($daoBucket->tags . " " . $tag);
            return $daoBucket->update($original);
        }
        return false;
    }

    public static function addTag($id_bucket,$tag){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            $tag = self::clearPrivateTags($tag);
            $original = clone($daoBucket);
            $daoBucket->tags = Denko::trim($daoBucket->tags . " " . $tag);
            return $daoBucket->update($original);
        }
        return false;
    }

    public static function removeTag($id_bucket,$tag){
        $daoBucket = self::getDaoById($id_bucket,null);
        if($daoBucket){
            $tag = self::clearPrivateTags($tag);
            $original = clone($daoBucket);
            $tags = $daoBucket->tags;
            $tags = str_replace(Denko::trim($tag),'',$tags);
            $tags = str_replace('  ',' ',$tags);
            $daoBucket->tags = Denko::trim($tags);
            return $daoBucket->update($original);
        }
        return false;
    }

    public static function clearTags($id_bucket){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            $tags = $daoBucket->tags;
            $tagsToKeep = array();
            if(!empty($tags)) {
                $tagsArray = explode(" ", $tags);
                foreach ($tagsArray as $tag) {
                    if(strpos(self::$private_prefix,$tag) !== FALSE){
                        $tagsToKeep[] = $tag;
                    }
                }
            }
            $tags = implode(" ",$tagsToKeep);

            $original = clone($daoBucket);
            $daoBucket->tags = $tags;
            return $daoBucket->update($original);
        }
        return false;
    }

    public static function getPublicTags($daoBucket){
        $tags = self::clearPrivateTags($daoBucket->tags);
        $tags = str_replace(" ",", ",Denko::trim($tags));
        return $tags;
    }

    public static function makeSetIdTag($set_id){
        return self::$set_id_prefix.$set_id;
    }

    public static function makeHashTag($hash){
        return self::$hash_prefix.$hash;
    }

    public static function makeTypeTag($type){
        return self::$type_prefix.$type;
    }

    public static function makeNameTag($name){
        return self::$name_prefix.$name;
    }

    /**********************************************************************************/
    /******************************************************** GENERAL *****************/
    /**********************************************************************************/

    public static function getTypeFromMime($mime){
        if(in_array($mime,array('image/jpg','image/jpeg','image/pjpeg','image/gif','image/png','image/webp','image/svg+xml'))) return "image";
        if(in_array($mime,array('application/pdf'))) return "pdf";
        if(in_array($mime,array('text/csv','application/x-iwork-keynote-sffnumbers','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/x-iwork-keynote-sffnumbers','application/vnd.ms-excel.sheet.binary.macroenabled.12','application/vnd.ms-excel.sheet.macroenabled.12','application/vnd.oasis.opendocument.spreadsheet-template','application/vnd.sun.xml.calc.template','application/vnd.sun.xml.calc','application/vnd.oasis.opendocument.spreadsheet-flat-xml','application/vnd.oasis.opendocument.spreadsheet'))) return "spreadsheet";
        if(in_array($mime,array('application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/epub+zip','application/x-iwork-pages-sffpages','text/rtf','text/plain','text/xml','application/vnd.ms-word.document.macroenabled.12','application/vnd.openxmlformats-officedocument.wordprocessingml.template','application/vnd.oasis.opendocument.text-template','application/vnd.oasis.opendocument.text','application/vnd.sun.xml.writer.template','application/vnd.sun.xml.writer','application/vnd.oasis.opendocument.text-flat-xml','application/doc','application/rtf','application/x-rtf','text/richtext'))) return "document";
        if(in_array($mime,array('application/vnd.ms-powerpoint.slideshow.macroenabled.12','application/vnd.openxmlformats-officedocument.presentationml.slideshow','application/vnd.ms-powerpoint.presentation.macroenabled.12','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/x-iwork-keynote-sffkey','application/vnd.ms-powerpoint','application/vnd.oasis.opendocument.presentation','application/vnd.oasis.opendocument.presentation-template','application/vnd.sun.xml.impress.template','application/vnd.sun.xml.impress','application/vnd.oasis.opendocument.presentation-flat-xml','application/vnd.oasis.opendocument.graphics'))) return "presentation";
        if(in_array($mime,array('audio/aac','audio/mp3','audio/mp4','audio/mpeg','audio/ogg','audio/wav','audio/webm'))) return "audio";
        if(in_array($mime,array('video/x-msvideo','video/mpeg','video/ogg','video/webm','video/3gpp','video/mp4'))) return "video";
        if(in_array($mime,array('application/x-shockwave-flash'))) return "flash";
        return "file";
    }

    public static function hashFile($filePath){
        if(!file_exists($filePath)) return null;
        return hash_file("sha256",$filePath);
    }

    public static function hashData($data){
        if($data == null) return null;
        return hash("sha256",$data);
    }
    /**********************************************************************************/
    /******************************************************** GETTERS & SETTERS *******/
    /**********************************************************************************/

    public static function setName($id_bucket,$name){
        // TODO: This function should remove current name tag and create the new one
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            $original = clone($daoBucket);
            $daoBucket->name = $name;
            return $daoBucket->update($original);
        }
        return false;
    }

    public static function setMime($id_bucket,$mime){
        // TODO: This function should remove current type tag and create the new one
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            $original = clone($daoBucket);
            $daoBucket->mime = $mime;
            return $daoBucket->update($original);
        }
        return false;
    }

    public static function setSize($id_bucket,$size){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            $original = clone($daoBucket);
            $daoBucket->size = $size;
            return $daoBucket->update($original);
        }
        return false;
    }

    public static function setType($id_bucket,$type){
        // TODO: This function should remove current type tag and create the new one
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            $original = clone($daoBucket);
            $daoBucket->type = $type;
            return $daoBucket->update($original);
        }
        return false;
    }

    public static function getName($id_bucket){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            return $daoBucket->name;
        }
        return null;
    }

    public static function getSize($id_bucket){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            return $daoBucket->size;
        }
        return null;
    }

    public static function getMime($id_bucket){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            return $daoBucket->mime;
        }
        return null;
    }

    public static function getType($id_bucket){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            return $daoBucket->type;
        }
        return null;
    }

    public static function getCount($id_bucket){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            return $daoBucket->count;
        }
        return null;
    }

    public static function getHash($id_bucket){
        $daoBucket = self::getDaoById($id_bucket);
        if($daoBucket){
            return $daoBucket->hash;
        }
        return null;
    }

    /**************************************************************************************/
    /******************************************************** VIEW & DOWNLOAD LINKS *******/
    /**************************************************************************************/

    public static function getFileUrl($item,$set_id = null,$mode = "full",$additionalParams = array()){
        if(!$item) return "";
        else if(is_int($item)) $item = InnyBucket::getDaoById($item,$set_id);
        else if (is_string($item)) $item = InnyBucket::getDao($item,$set_id);

        $shortHash = substr($item->hash,0,5);

        $words = explode('.', $item->name);
        $extension = array_pop($words);
        $first_chunk = implode('-', $words);
        $name = Denko::str_to_friendlyUrl($first_chunk).".".$extension;

        if($mode == "full") return "$item->type/$item->id_bucket"."x"."$shortHash/".$name;
        if($mode == "preview"){
            switch ($item->type){
                case 'image':
                    $width = isset($additionalParams['width']) ? $additionalParams['width'] : 400;
                    $height = isset($additionalParams['height']) ? $additionalParams['height'] : (isset($additionalParams['width']) ? $additionalParams['width'] : 400);
                    $quality = isset($additionalParams['quality']) ? $additionalParams['quality'] : 80;
                    $mode = isset($additionalParams['crop']) && $additionalParams['crop'] == true ? "c" : "t";
                    return "image/$item->id_bucket"."x"."$shortHash"."x".$mode."x".$width."x".$height."x".$quality."/".$name;
                    break;
                case 'flash':
                    return "$item->type/$item->id_bucket"."x"."$shortHash/".$name;
                    break;
            }
            return "";
        }

        return "download/$item->id_bucket"."x"."$shortHash/".$name;
    }

    public static function getBucketActionUrl($daoBucket, $action){
        //To Do: Check Action
        return "./$action-bucket!".$daoBucket->id_bucket."x".substr($daoBucket->hash,0,5);
    }

    /******************************************************************************/
    /******************************************************** UPDATE USAGES *******/
    /******************************************************************************/

    public static function decrementFileCount($arr,$documentPublicId,$collectionName,$set_id){
        foreach ($arr as $id => $amount){
            $daoBucket = self::getDao($id,$set_id);
            if(!$daoBucket) return;
            $usages = json_decode($daoBucket->usages,true);
            if(!$usages){
                $usages = array();
                $usages[$collectionName][$documentPublicId] = 0;
            }
            $usagesCount = $usages[$collectionName][$documentPublicId] - $amount;
            if($usagesCount <= 0) unset($usages[$collectionName][$documentPublicId]);
            else $usages[$collectionName][$documentPublicId] = $usagesCount;
            $daoBucket->usages = json_encode($usages);
            $daoBucket->count = $daoBucket->count - $amount;
            $daoBucket->update();
            $daoBucket->free();
        }
    }

    public static function incrementFileCount($arr,$documentPublicId,$collectionName,$set_id){
        foreach ($arr as $hash => $amount){
            $daoBucket = self::getDao($hash,$set_id);
            if(!$daoBucket) return;
            $usages = json_decode($daoBucket->usages,true);
            if(!$usages) $usages = array();
            if(!isset($usages[$collectionName])) $usages[$collectionName] = array();
            $usages[$collectionName][$documentPublicId] = isset($usages[$collectionName][$documentPublicId]) ? $usages[$collectionName][$documentPublicId] + $amount : $amount;
            $daoBucket->usages = json_encode($usages);
            $daoBucket->count = $daoBucket->count + $amount;
            $daoBucket->update();
            $daoBucket->free();
        }
    }

}
