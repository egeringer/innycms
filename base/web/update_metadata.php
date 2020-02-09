<?php
require_once 'common.php';
################################################################################
Denko::noCache();
################################################################################
if(isset($_GET['ping_only'])){
    echo 'PING OK';
    exit(0);
}

function updateHtaccess(){

    $htaccessBaseFilename = '.htaccess.base';
    $htaccessBaseCacheFilename = '.htaccess.base.cache';

    $htaccessLocalFilename = '.htaccess.dev';

    # Obtengo el contenido de los htaccess
    if(file_exists($htaccessBaseFilename) && file_exists($htaccessBaseCacheFilename)){
        $error_messages[] = utf8_encode('Hay 2 archivos .htaccess.base - usando la versión SIN CACHE!');
    }
    if(file_exists($htaccessBaseFilename)){
        $htaccessBase = file_get_contents($htaccessBaseFilename);
    }else if(file_exists($htaccessBaseCacheFilename)){
        $htaccessBase = file_get_contents($htaccessBaseCacheFilename);
    }else{
        $htaccessBase = '';
    }

    $htaccessLocal = file_exists($htaccessLocalFilename) ? file_get_contents($htaccessLocalFilename) : '';

    # En caso que ambos no existan o estén vacíos, no uno nada
    if($htaccessBase == '' && $htaccessLocal == ''){
        return true;
    }

    # Verifico que el archivo .htaccess tenga permisos de escritura
    $htaccess_path = '.htaccess';
    if(file_exists($htaccess_path) && is_file($htaccess_path) && !is_writable('.htaccess')){
        return false;
    }

    # Uno los contenidos de ambos archivos y lo encodeo a UTF-8
    $baseSeparador = '################################ BASE RULES ####################################';
    $localSeparador = '############################### LOCAL RULES ####################################';
    file_put_contents('.htaccess',utf8_encode($baseSeparador."\n\n".$htaccessBase."\n\n".$localSeparador."\n\n".$htaccessLocal));
    return true;
}

function createUniqueId($tableName,$insertColum,$groupColumn = null,$valueGroupColumn = null){
    $timestamp = microtime();

    $dao = Denko::daoFactory($tableName);
    $found = true;
    do {
        $hash = hash("md5",$timestamp);
        $rand = rand(0,22);
        $id = substr($hash,$rand,10);
        if(isset($groupColumn) && !empty($groupColumn)&& isset($valueGroupColumn)) $dao->$groupColumn = $valueGroupColumn;
        $dao->$insertColum = $id;
        if(!$dao->find(true)) $found = true;
    }while(!$found);

    $dao->free();

    return $id;
}

// Funciones que usaremos durante el parseo

function validateJson($string,$file,$assoc = false){
    $json = json_decode($string,$assoc);
    switch(json_last_error()) {
        case JSON_ERROR_NONE:
            return $json;
            break;
        case JSON_ERROR_DEPTH:
            echo "Error en $file - Excedido tamaño máximo de la pila";
            return false;
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo "Error en $file - Desbordamiento de buffer o los modos no coinciden";
            return false;
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo "Error en $file - Encontrado carácter de control no esperado";
            return false;
            break;
        case JSON_ERROR_SYNTAX:
            echo "Error en $file - Error de sintaxis, JSON mal formado";
            return false;
            break;
        case JSON_ERROR_UTF8:
            echo "Error en $file - Caracteres UTF-8 malformados, posiblemente están mal codificados";
            return false;
            break;
        default:
            echo "Error en $file - Error desconocido";
            return false;
            break;
    }
}

function validateFile($data, $file, $collection){
    $errors = array();

    if(!is_array($data)){
        $errors[] = "El contenido de files <b>$file</b> en la coleccion <b>$collection</b> no tiene formato válido";
    }else{
        # Verifico que el parametro "item" esté seteado
        if(!isset($data['item']) || empty($data['item'])){
            $errors[] = "El parametro <b>item</b> de files <b>$file</b> en la coleccion <b>$collection</b> no puede ser vacio";
        }

        # Verifico que el parametro "name" esté seteado
        if(!isset($data['name']) || empty($data['name'])){
            $errors[] = "El parametro <b>name</b> de files <b>$file</b> en la coleccion <b>$collection</b> no puede ser vacio";
        }

        # Verifico si tiene ayuda la coleccion
        if(isset($data['help']) && empty($data['help'])){
            $errors[] = "El parametro <b>help</b> de files <b>$file</b> en la coleccion <b>$collection</b> no puede ser vacio";
        }

        # Verifico si tiene tipo los archivos
        if(!isset($data['type']) || empty($data['type'])){
            $errors[] = "El parametro <b>type</b> de files <b>$file</b> en la coleccion <b>$collection</b> no puede ser vacio";
        }else{
            $type = InnyType::factory($data['type']);
            if($type === NULL){
                $errors[] = "El <b>type</b> ".$data['type']." de files <b>$file</b> en la coleccion <b>$collection</b> no existe";
            }elseif($type->datatype != "binary"){
                $errors[] = "El <b>type</b> ".$data['type']." de files <b>$file</b> en la coleccion <b>$collection</b> no es un tipo valido";
            }
        }

        # Parametro quantity
        if(isset($data['quantity']) && !is_int($data['quantity'])){
            $errors[] = "El parámetro <b>quantity</b> de files <b>$file</b> solamente admite valores enteros en la coleccion <b>$collection</b>";
        }

        # Verifico los parámetros de los archivos
        if(isset($data['params'])){
            # Parametro resize
            if(isset($data['params']['resize']) && $data['params']['resize'] !== true && $data['params']['resize'] !== false){
                $errors[] = "El parámetro <b>resize</b> de files <b>$file</b> solamente admite los valores true o false en la coleccion <b>$collection</b>";
            }

            # Parametro required
            if(isset($data['params']['required']) && $data['params']['required'] !== true && $data['params']['required'] !== false){
                $errors[] = "El parámetro <b>required</b> de files <b>$file</b> solamente admite los valores true o false en la coleccion <b>$collection</b>";
            }

            # Parametro limit
            if(isset($data['params']['limit']) && !is_int($data['params']['limit'])){
                $errors[] = "El parámetro <b>limit</b> de files <b>$file</b> solamente admite valores enteros en la coleccion <b>$collection</b>";
            }

            # Parametro width
            if(isset($data['params']['width']) && !is_int($data['params']['width'])){
                $errors[] = "El parámetro <b>width</b> de files <b>$file</b> solamente admite valores enteros en la coleccion <b>$collection</b>";
            }

            # Parametro height
            if(isset($data['params']['height']) && !is_int($data['params']['height'])){
                $errors[] = "El parámetro <b>height</b> de files <b>$file</b> solamente admite valores enteros en la coleccion <b>$collection</b>";
            }

            # Parametro quality
            if(isset($data['params']['quality']) && !is_int($data['params']['quality'])){
                $errors[] = "El parámetro <b>quality</b> de files <b>$file</b> solamente admite valores enteros en la coleccion <b>$collection</b>";
            }

            # Parametro permission
            if(isset($data['params']['permission'])){
                if(empty($data['params']['permission'])) {
                    $errors[] = "El parámetro <b>permission</b> no puede ser vacio en la coleccion <b>$collection</b>";
                }elseif(!is_array($data['params']['permission'])) {
                    $errors[] = "El parámetro <b>permission</b> debe ser un arreglo de char (CRUD) en la coleccion <b>$collection</b>";
                } else if(!in_array("C",$data['params']['permission']) && !in_array("R",$data['params']['permission']) && !in_array("U",$data['params']['permission']) && !in_array("D",$data['params']['permission'])){
                    $errors[] = "El parámetro <b>permission</b> debe ser un arreglo de char (CRUD) en la coleccion <b>$collection</b>";
                }
            }
        }

    }
    return $errors;
}

function validateField($field, $fieldName, $collection){
    $errors = array();

    # Verifico que el parametro "name" esté seteado
    if(isset($field['name']) && empty($field['name'])){
        $errors[] = "El parametro <b>name</b> de $fieldName en la coleccion <b>$collection</b> no puede ser vacio";
    }

    # Verifico si tiene ayuda la coleccion
    if(isset($field['help']) && empty($field['help'])){
        $errors[] = "El parametro <b>help</b> de $fieldName en la coleccion <b>$collection</b> no puede ser vacio";
    }

    # Verifico si tiene tipo los fields
    if(isset($field['type']) && empty($field['type'])){
        $errors[] = "El parametro <b>type</b> de $fieldName en la coleccion <b>$collection</b> no puede ser vacio";
    }elseif(isset($field['type'])){
        $type = InnyType::factory($field['type']);
        if($type === NULL){
            $errors[] = "El <b>type</b> ".$field['type']." de $fieldName en la coleccion <b>$collection</b> no existe";
        }elseif($type->datatype != "text"){
            $errors[] = "El <b>type</b> ".$field['type']." de $fieldName en la coleccion <b>$collection</b> no es un tipo valido";
        }
    }

    # Verifico los parámetros de los textos
    if(isset($field['params'])){
        # Parametro required
        if(isset($field['params']['required']) && $field['params']['required'] !== true && $field['params']['required'] !== false){
            $errors[] = "El parámetro <b>required</b> de $fieldName solamente admite los valores true o false en la coleccion $collection";
        }

        # Parametro multilang
        if(!empty($field['params']['multilang']) && $field['params']['multilang'] !== true && $field['params']['multilang'] !== false){
            $errors[] = "El parámetro <b>multilang</b> de $fieldName solamente admite los valores true o false en la coleccion $collection";
        }

        # Parametro default
        if(isset($field['params']['default']) && $field['params']['default'] === ""){
            $errors[] = "El parámetro <b>default</b> de $fieldName en la coleccion $collection no puede ser vacío";
        }

        # Parametro unique
        if(isset($field['params']['unique']) && empty($field['params']['unique'])){
            $errors[] = "El parámetro <b>default</b> de $fieldName en la coleccion $collection no puede ser vacío";
        }elseif(isset($field['params']['unique']) && $field['params']['unique'] != "ci" && $field['params']['unique'] != "cs" && $field['params']['unique'] != "no"){
            $errors[] = "El parámetro <b>unique</b> de $fieldName solamente admite los valores ci, cs o no en la coleccion $collection";
        }
    }

    return $errors;
}

function validateLanguages($multilangArray){
    $errors = array();

    if(!isset($multilangArray) || empty($multilangArray)) {
        $errors[] = "El parametro <b>languages</b> del sitio es requerido si el parametro <b>multilang</b> es true";
        return $errors;
    }
    foreach ($multilangArray as $key => $lang){
        if(empty($lang) || !is_string($lang)){
            $errors[] = "El nombre del language <b>$key</b> es inválido";
        }
    }
    return $errors;
}

function validateListingField($field, $name, $collection){
    $errors = array();

    # Verifico que el parametro "fields" esté seteado
    if(isset($field['fields']) && empty($field['fields'])){
        $errors[] = "El parametro <b>fields</b> del listingField $name en la coleccion <b>$collection</b> no puede ser vacio";
    }else if(isset($field['fields']) && !is_array($field['fields'])) {
        $errors[] = "El parametro <b>fields</b> del listingField $name en la coleccion <b>$collection</b> no tiene formato valido";
    }

    if(isset($field['files']) && empty($field['files'])){
        $errors[] = "El parametro <b>files</b> del listingField $name en la coleccion <b>$collection</b> no puede ser vacio";
    }else if(isset($field['files']) && !is_array($field['files'])) {
        $errors[] = "El parametro <b>files</b> del listingField $name en la coleccion <b>$collection</b> no tiene formato valido";
    }

    if(isset($field['visible']) && empty($field['visible'])){
        $errors[] = "El parametro <b>visible</b> del listingField $name en la coleccion <b>$collection</b> no puede ser vacio";
    }else if(isset($field['visible']) && !in_array($field['visible'],array("sm","md","lg","xl"))){
        $errors[] = "El parametro <b>visible</b> del listingField $name en la coleccion <b>$collection</b> solo puede tomar los valores sm, md, lg, xl";
    }

    if(isset($field['hidden']) && empty($field['hidden'])){
        $errors[] = "El parametro <b>hidden</b> del listingField $name en la coleccion <b>$collection</b> no puede ser vacio";
    }else if(isset($field['hidden']) && !in_array($field['hidden'],array("sm","md","lg","xl"))){
        $errors[] = "El parametro <b>hidden</b> del listingField $name en la coleccion <b>$collection</b> solo puede tomar los valores sm, md, lg, xl";
    }

    return $errors;
}

function completeFieldsMetadata($data){
    // Complete Multilang Param
    global $multilang;
    global $languages;
    if($multilang && isset($data['params']['multilang']) && $data['params']['multilang'] == true) $data['params']['languages'] = $languages;
    else if($multilang && !isset($data['params']['multilang'])) $data['params']['multilang'] = false;

    if(!isset($data['fields'])) return $data;

    foreach($data['fields'] as $key=>$value){
        if ((!isset($value['name']) || empty($value['name'])) && (isset($data['name']) && !empty($data['name']))) $data['fields'][$key]['name'] = $data['name'];
        if ((!isset($value['help']) || empty($value['help'])) && (isset($data['help']) && !empty($data['help']))) $data['fields'][$key]['help'] = $data['help'];
        if ((!isset($value['type']) || empty($value['type'])) && (isset($data['type']) && !empty($data['type']))) $data['fields'][$key]['type'] = $data['type'];
        else if((!isset($value['type']) || empty($value['type']))) $data['fields'][$key]['type'] = "text";

        if (!isset($value['params']['required']) && isset($data['params']['required'])) $data['fields'][$key]['params']['required'] = $data['params']['required'];
        else if (!isset($value['params']['required'])) $data['fields'][$key]['params']['required'] = false;

        if (!isset($value['params']['disabled']) && isset($data['params']['disabled'])) $data['fields'][$key]['params']['disabled'] = $data['params']['disabled'];
        else if (!isset($value['params']['disabled'])) $data['fields'][$key]['params']['disabled'] = false;

        if (!isset($value['params']['readonly']) && isset($data['params']['readonly'])) $data['fields'][$key]['params']['readonly'] = $data['params']['readonly'];
        else if (!isset($value['params']['readonly'])) $data['fields'][$key]['params']['readonly'] = false;

        // Si no tengo el parametro multilang y la coleccion es multilang, lo pongo en true y le copio los idiomas
        if (!isset($value['params']['multilang']) && $data['params']['multilang'] == true) $data['fields'][$key]['params']['multilang'] = true;

        // Si tengo el parametro multilang en true en el field y la coleccion es multilang, le copio los idiomas
        if(isset($data['fields'][$key]['params']['multilang']) && $data['fields'][$key]['params']['multilang'] == true && isset($data['params']['multilang']) && $data['params']['multilang'] == true) $data['fields'][$key]['params']['languages'] = $languages;
        else $data['fields'][$key]['params']['multilang'] = false;
    }

    return $data;
}

function completeFilesMetadata($files){
    // Complete Multilang Param
    global $multilang;
    global $languages;

    foreach($files as $key => $value){
        if(!$multilang) $files[$key]['params']['multilang'] = false;
        else if (isset($value['params']['multilang']) && $value['params']['multilang'] == true) $files[$key]['params']['languages'] = $languages;
    }

    return $files;
}

function validateCollection($string,$name){

    $collection = json_decode($string,true);
    $errors = array();
    $data = array();

    # =============== METADATA GENERAL ================= #
    # =============== METADATA GENERAL ================= #
    # =============== METADATA GENERAL ================= #
    # =============== METADATA GENERAL ================= #

    # Verifico que el parametro "item" esté seteado
    if(!isset($collection['item']) || empty($collection['item'])){
        $errors[] = "El parametro <b>item</b> en la coleccion $name es requerido";
    }

    # Verifico que el parametro "name" esté seteado
    if(!isset($collection['name']) || empty($collection['name'])){
        $errors[] = "El parametro <b>name</b> en la coleccion $name es requerido";
    }

    # Verifico si tiene ayuda la coleccion
    if(isset($collection['help']) && empty($collection['help'])){
        $errors[] = "El parametro <b>help</b> en la coleccion $name no puede ser vacio";
    }

    # Verifico los parámetros dla coleccion
    if(isset($collection['params'])){
        # Parametro private
        if(isset($collection['params']['private']) && $collection['params']['private'] !== true && $collection['params']['private'] !== false){
            $errors[] = "El parámetro <b>private</b> solamente admite los valores true o false en la coleccion $name";
        }

        # Parametro limit
        if(isset($collection['params']['limit']) && !is_int($collection['params']['limit'])){
            $errors[] = "El parámetro <b>limit</b> solamente admite valores enteros en la coleccion $name";
        }

        # Parametro multilang
        if(!empty($collection['params']['multilang'])){
            if($collection['params']['multilang'] !== true && $collection['params']['multilang'] !== false){
                $errors[] = "El parámetro <b>multilang</b> solamente admite los valores true o false en la coleccion $name";
            }else if($collection['params']['multilang'] === true && $GLOBALS['multilang'] === false){
                $errors[] = "El parámetro <b>multilang</b> no admite el valor true en la coleccion $name debido a que el sitio no es multilenguaje";
            }else{
                $GLOBALS[$name]['multilang'] = $collection['params']['multilang'];
            }
        }else{
            $GLOBALS[$name]['multilang'] = false;
        }


        # Parametro permission
        if(isset($collection['params']['permission']) && empty($collection['params']['permission'])){
            $errors[] = "El parámetro <b>permission</b> no puede ser vacio en la coleccion $name";
        }elseif (isset($collection['params']['permission']) && (!is_array($collection['params']['permission']))){
            $errors[] = "El parámetro <b>permission</b> debe ser un arreglo de char (CRUD) en la coleccion $name";
        }elseif (isset($collection['params']['permission']) && is_array($collection['params']['permission'])) {
            if(!in_array("C",$collection['params']['permission']) && !in_array("R",$collection['params']['permission']) && !in_array("U",$collection['params']['permission']) && !in_array("D",$collection['params']['permission'])){
                $errors[] = "El parámetro <b>permission</b> debe ser un arreglo de char (CRUD) en la coleccion $name";
            }
        }
    }


    # =============== METADATA PARA LOS ARCHIVOS ================= #
    # =============== METADATA PARA LOS ARCHIVOS ================= #
    # =============== METADATA PARA LOS ARCHIVOS ================= #
    # =============== METADATA PARA LOS ARCHIVOS ================= #

    if(isset($collection['files']) && !empty($collection['files'])){
        if(!is_array($collection['files'])){
            $errors[] = "El contenido de files en la coleccion <b>$name</b> no tiene formato válido";
        }else{
            $files = $collection['files'];
            foreach($collection['files'] as $key=>$file){
                $err = validateFile($file,$key,$name);
                if(!empty($err)) foreach ($err as $e) $errors[] = $e;
            }
        }
    }

    # =============== METADATA PARA LOS FIELDS ================= #
    # =============== METADATA PARA LOS FIELDS ================= #
    # =============== METADATA PARA LOS FIELDS ================= #
    # =============== METADATA PARA LOS FIELDS ================= #

    if(isset($collection['fields'])) {
        if(empty($collection['fields'])){
            $errors[] = "El contenido de fields en la coleccion <b>$name</b> no puede ser vacío";
        }else if(!is_array($collection['fields'])){
            $errors[] = "El contenido de fields en la coleccion <b>$name</b> no tiene formato válido";
        }else{
            $fields = $collection['fields'];

            if(isset($fields) && !empty($fields)) {
                foreach ($fields as $key => $field) {
                    $err = validateField($field,$key,$name);
                    if(!empty($err)) foreach ($err as $e) $errors[] = $e;
                }
            }
        }
    }

    # =============== METADATA PARA LOS LISTING FIELDS ================= #
    # =============== METADATA PARA LOS LISTING FIELDS ================= #
    # =============== METADATA PARA LOS LISTING FIELDS ================= #
    # =============== METADATA PARA LOS LISTING FIELDS ================= #

    if(isset($collection['listingFields']) && !empty($collection['listingFields'])) {
        if(!is_array($collection['listingFields'])){
            $errors[] = "El contenido de listingFields en la coleccion <b>$name</b> no tiene formato válido";
        }else{
            $listingFields = $collection['listingFields'];

            # Check los fields
            if(!empty($listingFields)) {
                foreach ($listingFields as $key => $field) {
                    $err = validateListingField($field,$key,$name);
                    if(!empty($err)) foreach ($err as $e) $errors[] = $e;
                }
            }
        }
    }

    if (empty($errors)){
        # =============== LA COLECCION ESTA OK ================= #
        # =============== LA COLECCION ESTA OK ================= #
        # =============== LA COLECCION ESTA OK ================= #
        # =============== LA COLECCION ESTA OK ================= #

        // Completar la Metadata de Multilang de la Colección con la Metadata Multilang Genérica del Sitio
        global $multilang;
        global $languages;
        if($multilang == true && isset($collection['params']['multilang']) && $collection['params']['multilang'] == true) $collection['params']['languages'] = $languages;
        else if($multilang && !isset($collection['params']['multilang'])) $collection['params']['multilang'] = false;

        // Completar Metadata de los Fields con la Metadata Genérica de los Fields
        if(!empty($data)) $collection['data'] = completeFieldsMetadata($data);
        if(!empty($files)) $collection['files'] = completeFilesMetadata($files);

        $string = json_encode($collection);

        global $daoSitio;
        $daoCollection = Denko::daoFactory("innydb_collection");
        $daoCollection->name = str_replace(".json","",$name);
        $daoCollection->site_name = $daoSitio->public_id;
        if($daoCollection->find(true)){
            $daoCollection->metadata = $string;
            $daoCollection->update();
        }else{
            $public_id = createUniqueId("innydb_collection","public_id","site_name",$daoSitio->public_id);
            $daoCollection->name = str_replace(".json","",$name);
            $daoCollection->site_name = $daoSitio->public_id;
            $daoCollection->public_id = $public_id;
            $daoCollection->metadata = $string;
            $daoCollection->insert();
            Denko::print_r($daoCollection);
        }
    }

    return empty($errors) ? null : $errors;
}

function validateSidebarItem($item, $name){

    $errors = array();

    # Verifico que el "label" esté seteado
    if(!isset($item['label']) || empty($item['label'])){
        $errors[] = "El parametro <b>type</b> en el sidebar $name es requerido";
    }else{
        $name = $item['label'];
    }

    # Verifico que si el "icon" está seteado, no sea vacío
    if(isset($item['icon']) && empty($item['icon'])){
        $errors[] = "El parametro <b>icon</b> en el sidebar $name es requerido";
    }

    # Verifico que el "type" esté seteado
    if(!isset($item['type']) || empty($item['type'])){
        $errors[] = "El parametro <b>type</b> en el sidebar $name es requerido";
    }else if($item['type'] != "group" && $item['type'] != "single" && $item['type'] != "heading"){
        $errors[] = "El parametro <b>type</b> en el sidebar $name solo admite los valores group, single y heading";
    }else{
        if(($item['type'] == "single") && (!isset($item['collection']) || empty($item['collection']))){
            $errors[] = "El parametro <b>collection</b> en el sidebar $name es requerido";
        }else if(($item['type'] == "single") && !is_string($item['collection'])){
            $errors[] = "El parametro <b>collection</b> en el sidebar $name debe ser un string";
        }

        if(($item['type'] == "group") && (!isset($item['collection']) || empty($item['collection']))){
            $errors[] = "El parametro <b>collection</b> en el sidebar $name es requerido";
        }else if($item['type'] == "group"){
            if(!is_array($item['collection'])){
                $errors[] = "El parametro <b>collection</b> en el sidebar $name no tiene formato valido";
            }else{
                foreach($item['collection'] as $key => $value){
                    # Verifico que el "label" esté seteado
                    if(!isset($value['label']) || empty($value['label'])){
                        $errors[] = "El parametro <b>label</b> de la <b>collection</b> ".$key." en el sidebar $name es requerido";
                    }

                    # Verifico que si el "icon" está seteado, no sea vacío
                    if(isset($value['icon']) && empty($value['icon'])){
                        $errors[] = "El parametro <b>icon</b> de la <b>collection</b> ".$key." en el sidebar $name requerido";
                    }
                }
            }
        }
    }

    return $errors;
}
//////////////////////////////
//////////////////////////////
// Vamos a parsear la nueva metadata en jSON
//////////////////////////////
//////////////////////////////

$filePath = "../innycms.json";
$metadataPath = "../innycms-metadata/";
$errors = false;
$c = Denko::getConnection();
Denko::beginTransaction($c);


//////////////////////////////
//////////////////////////////
// Check file exists
//////////////////////////////
//////////////////////////////

if (!file_exists($filePath)){
    echo "No existe el archivo de configuracion del sitio";
    exit;
}

//////////////////////////////
//////////////////////////////
// Check valid json
//////////////////////////////
//////////////////////////////

$metadataString = file_get_contents($filePath);
$metadata = validateJson($metadataString,"innycms.json",true);
if($metadata === FALSE) exit;

//////////////////////////////
//////////////////////////////
// ID del Sitio
//////////////////////////////
//////////////////////////////
$daoSitio = null;
if(!isset($metadata['public_id']) || empty($metadata['public_id'])) {
    echo "El Public ID del Sitio es requerido";
    exit;
}else{
    $daoSitio = Denko::daoFactory("innydb_site");
    $daoSitio->public_id = $metadata['public_id'];
    if(!$daoSitio->find(true)){
        echo "El Sitio con public_id ".$metadata['public_id']." no existe en el sistema";
        exit;
    }
}

//////////////////////////////
//////////////////////////////
// Check valid json from metadata path
//////////////////////////////
//////////////////////////////

// Ignore ., .. and .gitignore file
$files = array_slice(scandir($metadataPath,SCANDIR_SORT_ASCENDING),3);
foreach($files as $file){
    $string = file_get_contents($metadataPath.$file);
    $json = validateJson($string,$file);
    if($json === FALSE) $errors = true;
}

if($errors === TRUE) exit;

//////////////////////////////
//////////////////////////////
// check every json from metadatapath and store it into DB
//////////////////////////////
//////////////////////////////
foreach($files as $file){
    $string = file_get_contents($metadataPath.$file);
    $errors = validateCollection($string,$file);
    if(!empty($errors)) Denko::print_r($errors);
}

//////////////////////////////
//////////////////////////////
// La metadata está ok
//////////////////////////////
//////////////////////////////

if(empty($errors)){
    echo "Update Metadata: Fin con exito<br/>";
    Denko::commitTransaction($c);
}else{
    echo "Update Metadata: Fin con errores<br/>";
    Denko::rollbackTransaction($c);
}

if(updateHtaccess()){
    echo "Generar .htaccess: Fin con exito<br/>";
}else{
    echo "Generar .htaccess: Fin con errores<br/>";
}

################################################################################