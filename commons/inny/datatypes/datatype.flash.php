<?php
/**
 *
 *
 */
require_once dirname(__FILE__).'/datatype.binary.php';

/**
 *
 *
 */
class InnyType_Flash extends InnyType_Binary {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array();

    /**
     * InnyType_Flash constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'flash';
    }

    public function preview($template = "flash-view.tpl"){
        return parent::preview($template);
    }
}
################################################################################
?>