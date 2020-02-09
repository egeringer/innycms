<?php
require_once dirname(__FILE__).'/datatype.binary.php';

class InnyType_Image extends InnyType_Binary {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'width' => array(
            'required' => false,
            'default' => '1024'
        ),
        'height' => array(
            'required' => false,
            'default' => '768'
        ),
        'quality' => array(
            'required' => false,
            'default' => '90'
        ),
        'resize' => array(
            'required' => false,
            'default' => 'false'
        )
    );

    public static $allowedMimeTypes = array(
        'image/jpg',
        'image/jpeg',
        'image/pjpeg',
        'image/gif',
        'image/png',
        'image/tiff',
        'image/webp'
    );

    /**
     * InnyType_Image constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'image';
    }
}
################################################################################
?>