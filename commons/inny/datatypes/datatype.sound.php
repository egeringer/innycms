<?php
require_once dirname(__FILE__).'/datatype.binary.php';

/**
 *
 *
 */
class InnyType_Sound extends InnyType_Binary {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array();

    public static $allowedMimeTypes = array(
        'audio/aac',
        'audio/mp4',
        'audio/mpeg',
        'audio/ogg',
        'audio/wav',
        'audio/webm'
    );

    /**
     * InnyType_Sound constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'sound';
    }

    /**
     * Devuelve la preview del tipo de dato
     *
     * @access public
     * @return string
     */
    public function preview($template = "sound-view.tpl"){
        return parent::preview($template);
    }
}
################################################################################
?>