<?php
/**
 *
 */
function smarty_outputfilter_dk_include($source, $template){
    # En caso que deban incluirse archivos en el header
    if(isset($GLOBALS['DENKO_INCLUDES'])){
        $html = '';
        foreach($GLOBALS['DENKO_INCLUDES'] as $include){
            $html.= $include."\n";
        }
        return str_replace('<!-- @@DENKO_INCLUDES@@ -->',$html,trim($source));
    }

    # En caso que no deban incluirse archivos en el header
    return str_replace('<!-- @@DENKO_INCLUDES@@ -->','',trim($source));
}
?>