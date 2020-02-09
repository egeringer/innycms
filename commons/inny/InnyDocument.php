<?php
/**
 * Created by PhpStorm.
 * User: egeringer
 * Date: 23/02/19
 * Time: 12:20
 */

class InnyDocument {

    /*****************************************************************************************/
    /******************************************************** GET DOCUMENT FUNCTIONS *********/
    /*****************************************************************************************/

    public static function get($documentPublicId,$collectionName,$siteName,$status = null){
        if(empty($documentPublicId)) return null;
        $daoCollection = InnyCollection::get($collectionName,$siteName);
        if(!$daoCollection) return null;
        $collectionMetadata = json_decode($daoCollection->metadata,true);
        $tableName = 'innydb_document';
        if(isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $tableName = $collectionMetadata['table'];
        }
        $daoCollection->free();
        $daoDocument = Denko::daoFactory($tableName);
        $daoDocument->public_id = $documentPublicId;
        $daoDocument->collection_name = $collectionName;
        $daoDocument->site_name = $siteName;
        if(!empty($status)) $daoDocument->status = ($status == InnyCMS::$document_status_published || $status == InnyCMS::$document_status_unpublished) ? $status : InnyCMS::$document_status_published;
        if($daoDocument->find(true)) return $daoDocument;
        $daoDocument->free();
        return null;
    }

    public static function getOne($collectionName,$siteName,$status = null,$order = "desc"){
        return self::getOneByField("","",$collectionName,$siteName,$status,$order);
    }

    public static function getAll($collectionName,$siteName,$status = null,$order = "desc"){
        return self::getAllByField("","",$collectionName,$siteName,$status,$order);
    }

    public static function getOneByField($fieldName,$fieldValue,$collectionName,$siteName,$status = null,$order = "desc"){
        $daoCollection = InnyCollection::get($collectionName,$siteName);
        if(!$daoCollection) return null;
        $collectionMetadata = json_decode($daoCollection->metadata,true);
        $tableName = 'innydb_document';
        if(isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $tableName = $collectionMetadata['table'];
        }
        $daoCollection->free();
        $daoDocument = Denko::daoFactory($tableName);
        $daoDocument->collection_name = $collectionName;
        $daoDocument->site_name = $siteName;
        if(!empty($status)) $daoDocument->status = ($status == InnyCMS::$document_status_published || $status == InnyCMS::$document_status_unpublished) ? $status : InnyCMS::$document_status_published;
        if(!empty($fieldName) && !empty($fieldValue)) $daoDocument->$fieldName = $fieldValue;
        if($order) $daoDocument->orderBy("position $order");
        if($daoDocument->find(true)) return $daoDocument;
        $daoDocument->free();
        return null;
    }

    public static function getAllByField($fieldName,$fieldValue,$collectionName,$siteName,$status = null,$order = "desc"){
        $daoCollection = InnyCollection::get($collectionName,$siteName);
        if(!$daoCollection) return null;
        $collectionMetadata = json_decode($daoCollection->metadata,true);
        $tableName = 'innydb_document';
        if(isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $tableName = $collectionMetadata['table'];
        }
        $daoCollection->free();
        $daoDocument = Denko::daoFactory($tableName);
        $daoDocument->collection_name = $collectionName;
        $daoDocument->site_name = $siteName;
        if(!empty($status)) $daoDocument->status = ($status == InnyCMS::$document_status_published || $status == InnyCMS::$document_status_unpublished) ? $status : InnyCMS::$document_status_published;
        if(!empty($fieldName) && !empty($fieldValue)) $daoDocument->$fieldName = $fieldValue;
        if($order) $daoDocument->orderBy("position $order");
        $daoDocument->find();
        return $daoDocument;
    }

    /*****************************************************************************************/
    /******************************************************** STATUS DOCUMENT FUNCTIONS ******/
    /*****************************************************************************************/

    public static function delete($documentPublicId,$collectionName,$siteName){
        $daoDocument = self::get($documentPublicId,$collectionName,$siteName,null);
        if(!$daoDocument) return false;
        $delete = $daoDocument->delete();
        $daoDocument->free();
        return $delete;
    }

    public static function unpublish($documentPublicId,$collectionName,$siteName){
        $daoDocument = self::get($documentPublicId,$collectionName,$siteName,null);
        if(!$daoDocument) return false;
        $daoDocument->status = InnyCMS::$document_status_unpublished;
        $daoDocumentOriginal = clone($daoDocument);
        $result = $daoDocument->update($daoDocumentOriginal);
        $daoDocument->free();
        $daoDocumentOriginal->free();
        return $result;
    }

    public static function publish($documentPublicId,$collectionName,$siteName){
        $daoDocument = self::get($documentPublicId,$collectionName,$siteName,null);
        if(!$daoDocument) return false;
        $daoDocument->status = InnyCMS::$document_status_published;
        $daoDocumentOriginal = clone($daoDocument);
        $result = $daoDocument->update($daoDocumentOriginal);
        $daoDocument->free();
        $daoDocumentOriginal->free();
        return $result;
    }

    public static function discardDraft($documentPublicId,$collectionName,$siteName){
        $daoDocument = self::get($documentPublicId,$collectionName,$siteName,null);
        if(!$daoDocument) return false;
        $daoDocument->draft = 'NULL';
        $daoDocumentOriginal = clone($daoDocument);
        $result = $daoDocument->update($daoDocumentOriginal);
        $daoDocument->free();
        $daoDocumentOriginal->free();
        return $result;
    }

    /*****************************************************************************************/
    /******************************************************** HANDLE DOCUMENT FUNCTIONS ******/
    /*****************************************************************************************/

    public static function moveUp($documentPublicId,$collectionName,$siteName){
        $daoDocument = self::get($documentPublicId,$collectionName,$siteName,null);
        if(!$daoDocument) return false;

        $prevPosition = $daoDocument->position-1;
        if(!$prevPosition) return false;

        $daoDocumentPrev = self::getOneByField("position",$prevPosition,$collectionName,$siteName,null);
        if(!$daoDocumentPrev) return false;

        $daoDocumentOriginal = clone($daoDocument);
        $daoDocumentPrevOriginal = clone($daoDocumentPrev);

        //$daoDocument->position = DB_DataObject_Cast::sql('position-1');
        //$daoDocumentPrev->position = DB_DataObject_Cast::sql('position+1');

        $daoDocument->position = $daoDocument->position - 1;
        $daoDocumentPrev->position = $daoDocumentPrev->position + 1;

        $daoDocument->update($daoDocumentOriginal);
        $daoDocumentPrev->update($daoDocumentPrevOriginal);

        $daoDocument->free();
        $daoDocumentOriginal->free();
        $daoDocumentPrev->free();
        $daoDocumentPrevOriginal->free();

        return true;
    }

    public static function moveDown($documentPublicId,$collectionName,$siteName){
        $daoDocument = self::get($documentPublicId,$collectionName,$siteName,null);
        if(!$daoDocument) return false;

        $daoDocumentNext = self::getOneByField("position",($daoDocument->position+1),$collectionName,$siteName,null);
        if(!$daoDocumentNext) return false;

        $daoDocumentOriginal = clone($daoDocument);
        $daoDocumentNextOriginal = clone($daoDocumentNext);

        //$daoDocument->position = DB_DataObject_Cast::sql('position+1');
        //$daoDocumentNext->position = DB_DataObject_Cast::sql('position-1');

        $daoDocument->position = $daoDocument->position + 1;
        $daoDocumentNext->position = $daoDocumentNext->position - 1;

        $daoDocument->update($daoDocumentOriginal);
        $daoDocumentNext->update($daoDocumentNextOriginal);

        $daoDocument->free();
        $daoDocumentOriginal->free();
        $daoDocumentNext->free();
        $daoDocumentNextOriginal->free();

        return true;
    }

    /*****************************************************************************************/
    /******************************************************** ADD/EDIT DOCUMENT FUNCTIONS ****/
    /*****************************************************************************************/

    public static function add($collectionName,$siteName,$data){

        $daoCollection = InnyCollection::get($collectionName,$siteName);
        $collectionMetadata = json_decode($daoCollection->metadata,true);
        $tableName = 'innydb_document';
        if($daoCollection && isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $tableName = $collectionMetadata['table'];
        }
        $daoDocument = Denko::daoFactory($tableName);

        $innyTypes = InnyCMS::createInnyTypes($daoCollection,$daoDocument);
        $existingFiles = array();
        $error = array();
        $filesArray = array();

        foreach($innyTypes as $innyType){
            $field = $innyType->getMetadataValue("field");
            if($innyType->getParamValue("adminOnly",false) == false || ($innyType->getParamValue("adminOnly",false) == true && InnyCMS::adminUserLogged())) {
                $field = $innyType->getMetadataValue("field");
                $multilang = $innyType->getParamValue("multilang");
                if ($multilang) {
                    $languages = $innyType->getParamValue("languages");
                    $arreglo_textos = array();
                    foreach ($languages as $key => $lang) {
                        $arreglo_textos[$key] = (isset($data[$field . "_" . $key])) ? $data[$field . "_" . $key] : null;
                    }
                    $datos = json_encode($arreglo_textos);
                    unset($arreglo_textos);
                } else {
                    // Antes: $datos = (isset($data[$field])) ? $data[$field] : null;
                    // Se cambio porque no permitía select con subindice cero
                    $datos  = (!is_array($data[$field])) ? strval($data[$field]) : $data[$field];
                }
            }else{
                $datos = $innyType->getParamValue("default");
            }

            $valid = $innyType->validate($datos);

            if($valid['valid'] == 1){
                if($innyType->datatype == "text") $daoDocument->$field = $valid['value'];
                else $filesArray[$field] = $valid['value'];
            }else{
                $error[$field] = $valid;
            }
        }

        if(empty($error)){
            $documentPublicId = InnyCMS::createUniqueId($tableName,"public_id","site_name",$siteName);
            $daoDocument->public_id = $documentPublicId;
            $daoDocument->collection_name = $collectionName;
            $daoDocument->site_name = $siteName;
            $daoDocument->status = (isset($data['innystatusflag']) && $data['innystatusflag'] == "unpublished") ? InnyCMS::$document_status_unpublished : InnyCMS::$document_status_published;
            if(!empty($filesArray)){
                InnyCMS::updateBucketQuantities($documentPublicId,$collectionName,$existingFiles,$filesArray);
                $daoDocument->files = json_encode($filesArray);
            }
            return $daoDocument->insert();
        }

        return $error;
    }

    public static function edit($documentPublicId,$collectionName,$siteName,$data){

        $daoCollection = InnyCollection::get($collectionName,$siteName);

        $daoDocument = self::get($documentPublicId,$collectionName,$siteName);

        $daoDocumentOriginal = clone($daoDocument);

        $existingFiles = json_decode($daoDocument->files,true);

        $innyTypes = InnyCMS::createInnyTypes($daoCollection,$daoDocument);

        $error = array();
        $filesArray = array();

        foreach($innyTypes as $innyType){
            $field = $innyType->getMetadataValue("field");

            if($innyType->getParamValue("adminOnly",false) == false || ($innyType->getParamValue("adminOnly",false) == true && InnyCMS::adminUserLogged())){
                //If this field is not for admin or if it is for admin users and i'm an admin, then i edit
                $multilang = $innyType->getParamValue("multilang");
                if($multilang){
                    $languages = $innyType->getParamValue("languages");
                    $arreglo_textos = array();
                    foreach($languages as $key => $lang){
                        $arreglo_textos[$key] = (isset($data[$field."_".$key])) ? $data[$field."_".$key] : null;
                    }
                    $datos = json_encode($arreglo_textos);
                    unset($arreglo_textos);
                }else{
                    if(!isset($data[$field])) continue;
                    // Antes: $datos = (!empty($data[$field])) ? $data[$field] : "";
                    // Se cambio porque no permitía select con subindice cero
                    $datos  = (!is_array($data[$field])) ? strval($data[$field]) : $data[$field];
                }
            }else{
                //If the field is for admins and i'm not, then i copy the previous value
                $datos = $daoDocument->$field;
            }

            $valid = $innyType->validate($datos);

            if($valid['valid'] == 1){
                if($innyType->datatype == "text") $daoDocument->$field = $valid['value'];
                else $filesArray[$field] = $valid['value'];
            }else{
                $error[$field] = $valid;
            }
        }

        if(empty($error)){
            $innyStatusFlag = isset($data['innystatusflag']) ? $data['innystatusflag'] : "published";
            switch ($innyStatusFlag){
                case 'unpublished':
                    InnyCMS::updateBucketQuantities($daoDocument->public_id,$collectionName,$existingFiles,$filesArray);
                    if(!empty($filesArray)) $daoDocument->files = json_encode($filesArray);
                    $daoDocument->status = InnyCMS::$document_status_unpublished;
                    $return = $daoDocument->update($daoDocumentOriginal);
                    break;
                case 'published':
                    InnyCMS::updateBucketQuantities($daoDocument->public_id,$collectionName,$existingFiles,$filesArray);
                    if(!empty($filesArray)) $daoDocument->files = json_encode($filesArray);
                    $daoDocument->status = InnyCMS::$document_status_published;
                    $return = $daoDocument->update($daoDocumentOriginal);
                    break;
                case 'draft':
                    $daoDocument->draft = 'NULL';
                    if(!empty($filesArray)) $daoDocument->files = json_encode($filesArray);
                    $json = json_encode($daoDocument->toArray());
                    $daoDocument = clone($daoDocumentOriginal);
                    $daoDocument->draft = $json;
                    $return = $daoDocument->update($daoDocumentOriginal);
                    break;
            }
            return $return;
        }

        return $error;
    }

    /*****************************************************************************************/
    /******************************************************** SEARCH DOCUMENTS FUNCTIONS *****/
    /*****************************************************************************************/

    public static function searchDocuments($collectionName,$siteName,$searchOptions = array(),$additionalParams = array()){

        $response = array();
        $response['total'] = 0;
        $response['resultsCount'] = 0;
        $response['pagesCount'] = 1;
        $response['currentPage'] = 1;
        $response['documents'] = array();


        $daoCollection = null;
        if(isset($collectionName) && !empty($collectionName)) {
            $daoCollection = InnyCollection::get($collectionName,$siteName);
        }

        // No collection found
        if(!$daoCollection) return $response;

        $listingFields = InnyCollection::getListingFields($collectionName,$siteName);

        $collectionMetadata = json_decode($daoCollection->metadata,true);
        $tableName = 'innydb_document';
        if(isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $tableName = $collectionMetadata['table'];
        }
        $daoDocument = Denko::daoFactory($tableName);

        if(!empty($searchOptions)){
            foreach($searchOptions as $option){
                $logic = isset($option['logic']) ? $option['logic'] : "OR";
                if(isset($option['query'])){
                    $daoDocument->whereAdd($option['query'],$logic);
                }else{
                    $operator = isset($option['operator']) ? $option['operator'] : "=";
                    if($operator == "like")
                        $daoDocument->whereAdd($option['searchField']." like '%".InnyCMS::mysqlEscape($option['searchString'])."%'",$logic);
                    else
                        $daoDocument->whereAdd($option['searchField']." $operator '".InnyCMS::mysqlEscape($option['searchString'])."'",$logic);
                }
                $daoDocument->whereAdd("status= '1'","AND");
                $daoDocument->whereAdd("collection_name = '".$daoCollection->name."'","AND");
                $daoDocument->whereAdd("site_name = '".$siteName."'","AND");
            }
        }else{
            $daoDocument->status = InnyCMS::$document_status_published;
            $daoDocument->collection_name = $daoCollection->name;
            $daoDocument->site_name = $siteName;
        }

        if(!empty($additionalParams['order']) && !empty($additionalParams['orderField'])){
            $daoDocument->orderBy();
            $daoDocument->orderBy($additionalParams['orderField']." ".$additionalParams['order']);
        }else{
            $daoDocument->orderBy();
            $daoDocument->orderBy("position desc");
        }

        $response['total'] = $response['resultsCount'] = $daoDocument->count();

        if(isset($additionalParams['limit'])){
            if(isset($additionalParams['page'])){
                $daoDocument->limit(($additionalParams['page']-1)*$additionalParams['limit'],$additionalParams['limit']);
                $response['pagesCount'] = ceil($response['total'] / $additionalParams['limit']);
                $response['currentPage'] = $additionalParams['page'];
            }else{
                $daoDocument->limit($additionalParams['limit']);
            }
        }

        $daoDocument->find();

        $i = 0;
        while($daoDocument->fetch()){
            $files = json_decode($daoDocument->files,true);
            $arr = array();

            if(!empty($searchOptions)){
                foreach($searchOptions as $option){
                    $sField = $option['searchField'];
                    if(isset($option['searchField'])) $arr[$option['searchField']] = $daoDocument->$sField;
                }
            }

            foreach($listingFields as $fieldKey => $value){
                $str = "";
                if(isset($value['fields'])){
                    foreach ($value['fields'] as $name => $field) {
                        $jsonArr = json_decode($daoDocument->$name,true);
                        if(is_array($jsonArr)){
                            foreach ($jsonArr as $key => $value) {
                                $str .= substr("<b>$key:</b> $value<br/>",0,150);
                            }
                        }else{
                            $str .= substr($daoDocument->$name,0,300);
                        }
                    }
                }

                if(isset($value['files'])){
                    foreach ($value['files'] as $name => $field) {
                        $jsonArr = json_decode($files[$name],true);
                        if(is_array($jsonArr)){
                            foreach ($jsonArr as $key => $value) {
                                $str .= "<b>$key:</b><br/>";
                                $str .= "<img src='".InnyCMS::getBucketFileUrl($value,"preview")."' style='width:auto;max-width:100%;'/>";
                            }
                        }else{
                            $str .= "<img src='".InnyCMS::getBucketFileUrl($files[$name],"preview")."' style='width:auto;max-width:100%;' />";
                        }
                    }
                }

                $arr[str_replace(" ","",$fieldKey)] = $str;
            }

            $keyField = (isset($additionalParams['keyField']) && !empty($additionalParams['keyField'])) ? $additionalParams['keyField'] : "public_id";
            $searchOpt = isset($searchOptions[0]['searchField']) ? $searchOptions[0]['searchField'] : "";
            $sField = (!empty($searchOptions) && !empty($searchOpt)) ? $daoDocument->$searchOpt : "";

            $arr['id'] = $daoDocument->$keyField;
            $arr['text'] = $daoDocument->$keyField.((!empty($sField)) ? " - ".$sField : "");
            $arr['public_id'] = $daoDocument->public_id;
            $arr['document_id'] = $daoDocument->$keyField;

            $response['documents'][] = $arr;
            $i++;
        }

        $response['resultsCount'] = $i;
        return $response;
    }
}
