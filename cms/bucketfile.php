<?php
/**
 * Inny Base Project
 *
 * File: image.php
 * Purpose: script PHP para mostrar imágenes alojadas en la DB
 *
 * @copyright 2007-2009 Dokko Group
 * @author Dokko Group <info at dokkogroup dot com dot ar>
 */
$INNY_START_SESSION = true;
require_once 'common.php';
require_once '../commons/inny/InnyBucket.php';

################################################################################
if(empty($_GET['id_bucket']) || !InnyBucket::getDaoById($_GET['id_bucket'],null)){
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 File not found', true, 404);
    exit;
} else{
    $daoBucket = InnyBucket::getDaoById($_GET['id_bucket'],null);
    $hash = substr($daoBucket->hash,0,5);
    if($hash != $_GET['hash']) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 File not found', true, 404);
        exit;
    }

    $filename = $daoBucket->name;
    $mime = $daoBucket->mime    ;

    $stream = null;

    $daoBucketChunk = Denko::daoFactory('innydb_bucket_chunk');
    $daoBucketChunk->id_bucket = $_GET['id_bucket'];
    $daoBucketChunk->orderBy("id_bucket_chunk");
    $daoBucketChunk->find();
    while($daoBucketChunk->fetch()){
        $stream .= $daoBucketChunk->data;
    }

    # En caso que haya que redimensionar la imagen:
    if(!empty($_GET['type']) && $mime != "image/svg+xml" && ($_GET['type'] == 'thumb' || $_GET['type'] == 'crop')){
        $stream = Denko::createImage($stream,($_GET['width']!=='null'?$_GET['width']:null),($_GET['height']!=='null'?$_GET['height']:null),$_GET['quality'],$mime,$_GET['type'] == 'crop');
    }

    # Preparo para entregar la imagen

    header('Content-type: '.$mime);
    header('Content-disposition: '.$mime);
    header('cache-control: public, max-age=2592000');
    if(isset($_GET['mode']) && $_GET['mode'] == "inline")  header('Content-disposition: inline; filename='.$filename);
    else header('Content-disposition: attachment; filename='.$filename);


    # verifico si tengo que usar la cache de imagenes al disco y si es asi, cacheo
    if(isset($_GET['fscache']) && $_GET['fscache']=='true'){
        $fname=substr(urldecode($_SERVER['REQUEST_URI']),1);
        $base=substr(dirname(str_replace('/web/','/',$_SERVER['PHP_SELF'])),1).'/';
        if($base!='/' && $base!='' && strpos(strtolower($fname),strtolower($base))===0){
            $fname=substr($fname,strlen($base));
        }
        if(substr($fname,0,4)=='web/'){
            $fname=substr($fname,4);
        }
        @mkdir(dirname($fname),0777,true);
        file_put_contents($fname,$stream);
    }else{
        # Control de cache
        // $dl->setCache('true');
        // $dl->headers['Cache-Control'] = 'public';
    }

    # Entrego el contenido y termino y finaliza el script
    echo $stream;
    exit(0);
}
################################################################################
