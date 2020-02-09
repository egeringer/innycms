<?php
/**
 * InnyType
 *
 * Tipo de dato del InnyCMS
 *
 * @author Denko Developers Group <info at dokkogroup dot com dot ar>
 * @copyright Copyright (c) 2009 Dokko Group.
 * @link http://www.dokkogroup.com.ar/
 * @package InnyType
 */
abstract class InnyType{
    /**
     * Crea una instancia de un tipo de dato
     *
     * @param string $type nombre del tipo de dato
     * @static
     * @access public
     * @return object
     */
    public static function factory($type){
        $type = strtolower(trim($type));
        $required_file = dirname(__FILE__).'/datatypes/datatype.'.$type.'.php';

        if(!file_exists($required_file)){
            throw new Exception("DataType $type is not implemented");
        }

        require_once $required_file;
        $className = "InnyType_".ucfirst($type);
        $innyType = new $className();

        return $innyType;
    }
}
################################################################################