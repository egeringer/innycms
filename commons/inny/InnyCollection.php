<?php
/**
 * Created by PhpStorm.
 * User: egeringer
 * Date: 1/10/17
 * Time: 20:55
 */

class InnyCollection {

    /*****************************************************************************************/
    /******************************************************** COLLECTION FUNCTIONS ***********/
    /*****************************************************************************************/

    public static function get($collectionName,$siteName){
        if(empty($collectionName) || empty($siteName)) return null;
        $daoCollection = Denko::daoFactory('innydb_collection');
        $daoCollection->name = $collectionName;
        $daoCollection->site_name = $siteName;
        if($daoCollection->find(true)) return $daoCollection;
        $daoCollection->free();
        return null;
    }

    /**
     * @deprecated deprecated since Feb 2019. Please use InnyCollection::get()
     */
    public static function getDao($collectionName,$siteName){
        return self::get($collectionName,$siteName);
    }

    public static function exists($collectionName,$siteName){
        $daoCollection = self::get($collectionName,$siteName);
        if(!$daoCollection) return false;
        $daoCollection->free();
        return true;
    }

    public static function contains($documentPublicId,$collectionName,$siteName){
        $daoDocument = InnyDocument::get($documentPublicId,$collectionName,$siteName);
        if(!$daoDocument) return false;
        $daoDocument->free();
        return true;
    }

    public static function getListingFields($collectionName,$siteName){
        $listingFields = array("Position"=>array("fields" => array("position"=>null), "display" => "all"),"Public ID"=>array("fields" => array("public_id"=>null)),"Description"=>array("fields" => array("field1"=>null), "display" => "all"),"Document ID"=>array("fields" => array("id_document"=>null)));
        $daoCollection = self::get($collectionName,$siteName);
        if($daoCollection){
            if(isset($daoCollection->metadata) && !empty($daoCollection->metadata)){
                $metadata = json_decode($daoCollection->metadata,true);
                if(isset($metadata['listingFields']) && !empty($metadata['listingFields'])) $listingFields = $metadata['listingFields'];
            }
        }
        return $listingFields;
    }

    public static function emptyCollection($collectionName,$siteName){
        $daoCollection = self::get($collectionName,$siteName);
        if(!$daoCollection) return false;
        $collectionMetadata = json_decode($daoCollection->metadata,true);
        $tableName = 'innydb_document';
        if(isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $tableName = $collectionMetadata['table'];
        }
        $daoCollection->free();
        $daoDocument = Denko::daoFactory($tableName);
        $daoDocument->collection_name = $collectionName;
        $daoDocument->site_name = $siteName;
        $daoDocument->find();
        while($daoDocument->fetch()){
            $daoDocument->delete();
        }
        $daoDocument->free();
        return true;
    }

    /*****************************************************************************************/
    /******************************************************** DOCUMENTS FUNCTIONS ************/
    /*****************************************************************************************/

    public static function getDocuments($params,$siteName){
        // Used by Datatables
        $documentStatus = null;
        $collectionName = null;
        $daoCollection = null;

        $listingFields = self::getListingFields($params['name'],$siteName);

        if(isset($params['name']) && ($params['name'] != "")) {
            $daoCollection = self::get($params['name'], $siteName);
            if($daoCollection) $collectionName = $daoCollection->name;
        }

        $collectionMetadata = json_decode($daoCollection->metadata,true);

        // Check Collection Table
        if($daoCollection && isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $daoDocument = Denko::daoFactory($collectionMetadata['table']);
        } else {
            $daoDocument = Denko::daoFactory('innydb_document');
        }

        if(isset($params['query']['status'])){
            $documentStatus = $params['query']['status'];
            unset($params['query']['status']);
        }

        if(isset($params['sort']) && isset($params['sort']['field']) && !empty($params['sort']['field'])){
            $field = $params['sort']['field'];
            foreach ($listingFields as $key => $value){
                if(str_replace(" ","",$key) == $field){
                    $field = array_keys($value['fields'])[0];
                }
            }

            $order = (isset($params['sort']['sort']) && !empty($params['sort']['sort'])) ? $params['sort']['sort'] : "desc";

            // Check if order field exists on table
            $tableFields = $daoDocument->table();
            if(isset($tableFields[$field])) $daoDocument->orderBy("$field $order");
            else $daoDocument->orderBy("position desc");
        }else{
            $daoDocument->orderBy("position desc");
        }

        if(!empty($params['query']['generalSearch'])){
            $generalSearch = $params['query']['generalSearch'];
            unset($params['query']['generalSearch']);
            $daoDocument->whereAdd('public_id like \'%'.InnyCMS::mysqlEscape($generalSearch).'%\'','OR');
            if($documentStatus !== NULL) $daoDocument->whereAdd("status= '".$documentStatus."'","AND");
            if($collectionName !== NULL) $daoDocument->whereAdd("collection_name = '".$daoCollection->name."'","AND");
            foreach ($params['query'] as $fieldRestriction => $valueRestriction){
                $daoDocument->whereAdd($fieldRestriction." = '".$valueRestriction."'","AND");
            }
            $daoDocument->whereAdd("site_name = '".$siteName."'","AND");
            foreach ($listingFields as $key => $value){
                $fields = array_keys($value['fields']);
                foreach ($fields as $key => $field){
                    $daoDocument->whereAdd($field.' like \'%'.InnyCMS::mysqlEscape($generalSearch).'%\'','OR');
                }
                if($documentStatus !== NULL){
                    if($documentStatus != 0) $daoDocument->whereAdd("status = '".$documentStatus."'","AND");
                    else $daoDocument->whereAdd("draft != 'NULL'","AND");
                }
                if($collectionName !== NULL) $daoDocument->whereAdd("collection_name = '".$daoCollection->name."'","AND");
                foreach ($params['query'] as $fieldRestriction => $valueRestriction){
                    $daoDocument->whereAdd($fieldRestriction." = '".$valueRestriction."'","AND");
                }
                $daoDocument->whereAdd("site_name = '".$siteName."'","AND");
            }
        }else{
            if($documentStatus !== NULL){
                if($documentStatus != 0) $daoDocument->whereAdd("status = '".$documentStatus."'","AND");
                else $daoDocument->whereAdd("draft != 'NULL'","AND");
            }
            if($collectionName !== NULL) $daoDocument->whereAdd("collection_name = '".$daoCollection->name."'","AND");
            $daoDocument->whereAdd("site_name = '".$siteName."'","AND");
            if(!empty($params['query'])){
                foreach ($params['query'] as $fieldRestriction => $valueRestriction){
                    $daoDocument->whereAdd($fieldRestriction." = '".$valueRestriction."'","AND");
                }
            }
        }

        $total = $daoDocument->count();

        $perpage = isset($params['pagination']['perpage']) && !empty($params['pagination']['perpage']) ? $params['pagination']['perpage'] : 10;

        $pages = ceil($total/$perpage);

        $page = isset($params['pagination']['page']) && !empty($params['pagination']['page']) ? $params['pagination']['page'] : 1;
        if($page < 0) $page = 1;
        if($page > $pages) $page = $pages;

        $offset = ($page - 1) * $perpage;

        $daoDocument->limit($offset,$perpage);
        $daoDocument->find();

        $res = array();

        $res['meta']['req'] = $_REQUEST;

        $res['meta'] = array();
        $res['meta']['page']    =   $page;
        $res['meta']['pages']   =   $pages;
        $res['meta']['perpage'] =   $perpage;
        $res['meta']['total']   =   $total;
        if (isset($order)) $res['meta']['sort']    =   $order;
        if (isset($field)) $res['meta']['field']   =   $field;

        $res['data'] = array();

        while($daoDocument->fetch()){
            $files = json_decode($daoDocument->files,true);
            $arr = array();

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
                                $str .= "<img src='".InnyCMS::getBucketFileUrl($value,"preview",array("crop"=>true,"width"=>"100","height"=>"100"))."' style='width:auto;max-width:100%;'/>";
                            }
                        }else{
                            $str .= "<img src='".InnyCMS::getBucketFileUrl($files[$name],"preview",array("crop"=>true,"width"=>"100","height"=>"100"))."' style='width:auto;max-width:100%;'/>";
                        }
                    }
                }

                $arr[str_replace(" ","",$fieldKey)] = $str;

            }

            $arr['public_id'] = $daoDocument->public_id;
            $arr['position'] = $daoDocument->position;
            $arr['status'] = $daoDocument->status;

            $res['data'][$offset] = $arr;
            $offset++;
        }

        return $res;
    }

    /*********************************************************************************************/
    /******************************************************** IMPORT & EXPORT ********************/
    /*********************************************************************************************/

    public static function download($collectionName,$siteName){
        $daoCollection = self::get($collectionName,$siteName);
        if($daoCollection){
            $results = array();
            $daoDocument = InnyDocument::getAll($collectionName,$siteName);
            while($daoDocument->fetch()){
                $arr = array();
                $innyTypes = InnyCMS::createInnyTypes($daoCollection,$daoDocument,true);
                foreach ($innyTypes as $innyType){
                    $arr[$innyType->getMetadataValue('field')] = $innyType->getRawValue();
                }
                $results[] = $arr;
            }

            $headers = array();
            foreach ($innyTypes as $innyType) {
                $headers[] = $innyType->getMetadataValue('name');
            }
            $output = fopen("php://output",'w') or die("Can't open php://output");
            header("Content-Type:application/csv");
            header("Content-Disposition:attachment;filename=$siteName-$collectionName.csv");
            fputcsv($output, $headers,";");
            foreach ($results as $result) {
                fputcsv($output, $result,";");
            }
            fclose($output) or die("Can't close php://output");
        }
    }

    public static function upload($collectionName,$siteName,$file){
        $response = array();
        $response['status'] = 1;
        $response['message'] = _t("Data uploaded Correctly");

        $daoCollection = self::get($collectionName,$siteName);
        if(!$daoCollection){
            $response['status'] = 0;
            $response['message'] = _t("No collection named %s for site %", $collectionName, $siteName);
            return $response;
        }

        if (empty($file) || $file['error'] != UPLOAD_ERR_OK) {
            $response['status'] = 0;
            $response['message'] = _t("No file uploaded");
            return $response;
        }

        $collectionMetadata = json_decode($daoCollection->metadata,true);
        $tableName = 'innydb_document';
        if(isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $tableName = $collectionMetadata['table'];
        }

        if($file['type'] == "text/csv"){
            // CSV
            $innyTypes = InnyCMS::createInnyTypes($daoCollection);
            $file = fopen($file['tmp_name'], 'r');
            while (($line = fgetcsv($file,0,";")) !== FALSE) {
                $data = array();
                $lineIndex = 0;
                foreach($innyTypes as $innyType){
                    $data[$innyType->metadata['field']] = $line[$lineIndex];
                    $lineIndex++;
                }

                $document = InnyDocument::add($collectionName,$siteName,$data);

                if(!$document){
                    $response['status'] = 0;
                    $response['message'] = _t("There was an error while uploading the file. Please check: ").implode(";",$line);
                    fclose($file);
                    return $response;
                }
            }

            fclose($file);
        }else{
            $response['status'] = 0;
            $response['message'] = _t("File type not supported");
        }

        return $response;
    }

    public static function export($collectionName,$siteName,$format = "json"){
        $daoCollection = self::get($collectionName,$siteName);
        if($daoCollection){

            $hiddenFields = array("aud_ins_date","aud_upd_date","aud_ins_user","aud_upd_user","position","site_name","collection_name");

            $collectionData = array();
            $tableFields = $daoCollection->table();
            $tableKeys = $daoCollection->keys();
            foreach ($tableFields as $field => $number){
                if(!in_array($field,$tableKeys) && !in_array($field,$hiddenFields)) {
                    $collectionData[$field] = $daoCollection->$field;
                }
            }

            $documentData = array();
            $daoDocument = InnyDocument::getAll($collectionName,$siteName,null,"asc");
            $tableFields = $daoDocument->table();
            $tableKeys = $daoDocument->keys();
            while($daoDocument->fetch()){
                $arr = array();
                foreach ($tableFields as $field => $number){
                    if(!in_array($field,$tableKeys) && !in_array($field,$hiddenFields)) {
                        $arr[$field] = $daoDocument->$field;
                    }
                }
                $documentData[] = $arr;
            }

            $time = time();

            $download = array();
            $download['collectionName'] = $collectionName;
            $download['siteName'] = $siteName;
            $download['date'] = $time;
            $download['signature'] = md5($time."mclpvytvb".$siteName.$collectionName);
            $download['collection'] = $collectionData;

            if($format == "csv"){
                $headers = array();
                $headers[] = "collectionInfo";
                foreach ($tableFields as $field => $number){
                    if(!in_array($field,$tableKeys) && !in_array($field,$hiddenFields)) {
                        $headers[] = $field;
                    }
                }

                $output = fopen("php://output",'w') or die("Can't open php://output");
                header("Content-Type:application/csv");
                header("Content-Disposition:attachment;filename=$siteName-$collectionName-$time.csv");
                fputcsv($output, $headers,";");
                foreach ($documentData as $data) {
                    array_unshift($data,json_encode($download));
                    fputcsv($output, $data,";");
                }
                fclose($output) or die("Can't close php://output");
            }else{

                $download['documents'] = $documentData;

                $json = json_encode($download);

                $output = fopen("php://output",'w') or die("Can't open php://output");
                header("Content-Type:application/json");
                header("Content-Disposition:attachment;filename=$siteName-$collectionName-$time.json");
                fputs($output,$json);
                fclose($output) or die("Can't close php://output");
            }
        }
    }

    public static function import($collectionName,$siteName,$file){
        $response = array();
        $response['status'] = 1;
        $response['message'] = _t("Collection Imported Correctly");

        $daoCollection = self::get($collectionName,$siteName);
        if(!$daoCollection){
            $response['status'] = 0;
            $response['message'] = _t("No collection named %s for site %", $collectionName, $siteName);
            return $response;
        }

        if (empty($file) || $file['error'] != UPLOAD_ERR_OK) {
            $response['status'] = 0;
            $response['message'] = _t("No file uploaded");
            return $response;
        }

        $collectionMetadata = json_decode($daoCollection->metadata,true);
        $tableName = 'innydb_document';
        if(isset($collectionMetadata['table']) && !empty($collectionMetadata['table'])){
            $tableName = $collectionMetadata['table'];
        }

        if($file['type'] == "text/csv"){
            // CSV
            $file = fopen($file['tmp_name'], 'r');

            // Prepare Headers
            $headers = fgetcsv($file,0,";");
            array_shift($headers);

            $idIndex = 0;
            while($idIndex < count($headers) && $headers[$idIndex] != "public_id") $idIndex++;

            while (($line = fgetcsv($file,0,";")) !== FALSE) {
                array_shift($line);
                $daoDocument = InnyCMS::getDocument($line[$idIndex],$collectionName);
                if($daoDocument){
                    foreach ($headers as $key => $field) {
                        $daoDocument->$field = $line[$key];
                    }
                    $daoDocument->update();
                }else{
                    $daoDocument = Denko::daoFactory($tableName);
                    foreach ($headers as $key => $field) {
                        $daoDocument->$field = $line[$key];
                    }
                    if(empty($daoDocument->public_id)) $daoDocument->public_id = InnyCMS::createUniqueId($tableName,"public_id","site_name",$siteName);
                    $daoDocument->site_name = $siteName;
                    $daoDocument->collection_name = $collectionName;
                    $daoDocument->insert();
                }
            }

            fclose($file);
        }else{
            // JSON
            $file = file_get_contents($file['tmp_name']);
            $json = json_decode($file,true);
            foreach ($json['documents'] as $document) {
                $daoDocument = InnyCMS::getDocument($document['public_id'],$collectionName);
                if($daoDocument){
                    foreach ($document as $field => $value) {
                        $daoDocument->$field = $value;
                    }
                    $daoDocument->update();
                }else{
                    $daoDocument = Denko::daoFactory($tableName);
                    foreach ($document as $field => $value) {
                        $daoDocument->$field = $value;
                    }
                    if(empty($daoDocument->public_id)) $daoDocument->public_id = InnyCMS::createUniqueId($tableName,"public_id","site_name",$siteName);
                    $daoDocument->site_name = $siteName;
                    $daoDocument->collection_name = $collectionName;
                    $daoDocument->insert();
                }
            }
        }

        return $response;
    }


}
