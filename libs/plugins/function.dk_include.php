<?php
/**
 * Denko Smarty plugin
 * @package Denko
 * @subpackage plugins
 */

/**
 * Inny Smarty {dk_include} function plugin
 *
 * Type: function
 * <br>
 * Name: dk_include
 * <br>
 * Purpose: Incluye un archivo JS o CSS al template
 * <br>
 * Input:
 * <br>
 * - Requeridos:
 *   - file = ruta del archivo
 * - Opcionales:
 *   - ignoreVersion = Ignora la versión del archivo. Por default, se agrega una versión del archivo para evitar el cacheo del archivo.
 *   - version = versión del archivo. Por defecto, le agrega fecha y hora de update del archivo (ymdHis)
 *   - inline = indica si el archivo será agregado dentro del header del HTML (FALSE), o en la linea donde se pidió (TRUE).
 *   - compress = indica si el archivo se comprimirá (si se minifica el archivo)
 *
 * @author Dokko Group Developers Team <info at dokkogroup dot com>
 * @link http://wiki.dokkogroup.com.ar/index.php/http://wiki.dojo/index.php/Denko%20Plugin%3A%20funci%F3n%20dk_include {dk_include} (Denko wiki)
 * @param array $params parámetros
 * @param Smarty $template instancia de Smarty
 * @return void
 */
################################################################################
function smarty_function_dk_include($params,$template){
    if(empty($params['file'])){
        Denko::plugin_fatal_error('el parámetro <b>file</b> es requerido','dk_include');
    }

    if(!isset($params['compress'])) $params['compress']= true; 
    if(!isset($params['version'])) $params['version']= ''; 
    if(!isset($params['inline'])) $params['inline']= 'auto'; 
    if(!isset($params['media'])) $params['media']= ''; 
    if(!isset($params['ignoreVersion'])) $params['ignoreVersion']= ''; 

    # Obtengo los parámetros extra que se setearon en el plugin
    $extraParams = $params;
    unset($extraParams['compress']);
    unset($extraParams['version']);
    unset($extraParams['inline']);
    unset($extraParams['media']);
    unset($extraParams['ignoreVersion']);

    Denko::smarty_include($params['file'],$params['inline'],$params['compress'],$params['ignoreVersion'],$params['version'],$params['media'],$extraParams);
}
################################################################################
?>