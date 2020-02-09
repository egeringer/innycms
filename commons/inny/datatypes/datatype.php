<?php
/**
 * Created by PhpStorm.
 * User: egeringer
 * Date: 9/17/16
 * Time: 11:06 AM
 */

abstract class InnyDataType {

    const INSERT_FAIL = 0;
    const INSERT_SUCCESS = 1;

    const VALUE_INVALID = 0;
    const VALUE_VALID = 1;


    const VALUE_ERROR_NONE = 0;
    const VALUE_ERROR_GENERAL = 1;
    const VALUE_ERROR_EMPTY = 2;
    const VALUE_ERROR_FORMAT = 3;
    const VALUE_ERROR_RANGE = 4;
    const VALUE_ERROR_NO_FILE = 5;

    /**
     * @var string nombre del tipo de dato
     */
    public $type;

    /**
     * @var mixed valor del tipo de dato
     */
    public $value = '';

    /**
     * @var array valores de la metadata
     */
    public $metadata = '';

    /**
     * @var string tipo básico de dato (text or binary)
     */
    public $datatype = '';

    /**
     * @var array con los mensajes de error para cada constante
     */
    public static $errorMessages = array(
        self::VALUE_ERROR_NONE => "",
        self::VALUE_ERROR_GENERAL => "An error occurred while procesing this field. Try again.",
        self::VALUE_ERROR_EMPTY => "This field cannot be empty.",
        self::VALUE_ERROR_FORMAT  => "Wrong format for this field.",
        self::VALUE_ERROR_RANGE => "Value out of range.",
        self::VALUE_ERROR_NO_FILE => "No file was uploaded."
    );

    /**
     * @var boolean que permite setear valores que pueden no validar para el tipo de dato
     * @access protected
     */
    protected $unsafemode = false;

    /**
     * @var array parametros esperados en la metadata para el tipo
     *      con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */

    protected static $typeParams =  array(
        'required' => array(
            'required' => false,
            'default' => false
        ),
        'disabled' => array(
            'required' => false,
            'default' => false
        ),
        'readonly' => array(
            'required' => false,
            'default' => false
        )
    );

    /**
     * Setea el valor al tipo de dato
     *
     * @param mixed $value valor del tipo de dato
     * @access public
     * @return boolean
     */
    public abstract function setValue($value);

    /**
     * unsetea el valor al tipo de dato y lo verifica para setearlo
     *
     * @param mixed $value valor del tipo de dato
     * @access public
     * @return boolean
     */
    public function unsetValue(){
        $required = $this->getParamValue('required');
        if(!$required){
            unset($this->value);
            return true;
        }
        return false;
    }

    /**
     * Devuelve true o false dependiendo si el tipo está seteado o no
     *
     * @access public
     * @return boolean
     */
    public function hasValue($lang = null){
        if($lang == null && isset($this->value) && (!empty(trim($this->value)) || strlen(trim($this->value)))) return true;

        if($lang != null && isset($this->value) && !empty(trim($this->value))){
            $multilang = $this->getParamValue("multilang");
            if($multilang == false) return false;
            $languages = $this->getParamValue("languages");
            if(!array_key_exists($lang,$languages)) return false;
            $values = json_decode($this->value,true);
            if(isset($values[$lang]) && (!empty(trim($values[$lang])) || strlen(trim($values[$lang])))) return true;
        }

        return false;
    }

    /**
     * Devuelve que tipo básico de dato es (text or binary)
     *
     * @param mixed $value valor del tipo de dato
     * @access public
     * @return string
     */
    public function getDataType(){
        if(isset($this->datatype) && !empty($this->datatype)) return $this->datatype;
        return 'text';
    }

    /**
     * Devuelve que tipo de dato es
     *
     * @param mixed $value valor del tipo de dato
     * @access public
     * @return string
     */
    public function getType(){
        if(isset($this->type) && !empty($this->type)) return $this->type;
        return 'text';
    }

    /**
     * Obtiene el valor del tipo de dato
     *
     * @access public
     * @return mixed
     */

    public function getRawValue(){
        return $this->value;
    }

    public function getValue($lang = null){
        if(!$this->hasValue($lang)) return null;

        # Obtengo la configuración si el valor es multilenguaje
        $multilang = $this->getParamValue('multilang');

        # Obtengo la configuración si el valor puede ser multiple
        $multiple = $this->getParamValue('multiple');

        if($multilang === true){
            $values = json_decode($this->value,true);
            if($multiple === true){
                return explode(",",$values[$lang]);
            }else{
                return $values[$lang];
            }
        }

        if($multiple === true){
            $values = explode(",",$this->value);
            return $values;
        }

        return $this->value;
    }

    /**
     * Setea la metadata al tipo de dato validando los parametros para el tipo
     *
     * @param array $metadata metadata en formateada en arreglo
     * @access public
     * @return void
     */
    public function setMetadata($metadata){
        if(isset($metadata['params'])){
            $errors = static::validateParams($metadata['params']);
            if(empty($errors)) $this->metadata = $metadata;
        }
    }

    protected function unsetMetadata(){
        $this->metadata = null;
    }

    /**
     * Valida los parametros del tipo de dato y los asigna
     * Mejorar esta funcion para que la pueda interpretar mejor el core.metadata.php
     * @param array $params parametros en formateada en arreglo
     * @access public
     * @return array
     */
    public function validateParams($parameters){
        $errors = array();

        $params = static::getParams();

        foreach ($params as $paramKey => $paramValues) {
            if($paramValues['required']){
                if(!isset($parameters[$paramKey])) {
                    $errors[] = "El parametro $paramKey es requerido en el tipo $this->type";
                }
            }

            if(isset($parameters[$paramKey]) && !empty($paramValues['values'])){
                if(!in_array($parameters[$paramKey],$paramValues['values'])){
                    $errors[] = "El valor parameters[$paramKey] del parametro $paramKey no es soportado por el tipo $this->type";
                }
            }
        }

        return $errors;
    }

    /**
     * Valida el valor que se le intentará setear al tipo
     * @param mixed $value valor que se intentará validar para el tipo
     * @access public
     * @return array
     */
    public abstract function validate($value = null);

    /**
     * Retorna los parametros comunes a todos los tipos
     *
     * @access public
     * @return array
     */
    public static function getParams(){
        return self::_getParams();
    }

    /**
     * Sirve para clases que extienden de otros tipos por si se olvidan
     * de definir el metodo getParams()
     * @return array
     */
    protected static function _getParams(){
        return array_merge(self::$typeParams,static::$typeParams);
    }

    public function getParamValue($param, $default=null){
        if(isset($this->metadata['params']) && isset($this->metadata['params'][$param])) return $this->metadata['params'][$param];
        if($default!=null) return $default;
        return static::getDefaultParamValue($param);
    }

    public function getDefaultParamValue($param){
        $params = static::getParams();
        if(isset($params[$param]) && isset($params[$param]['default'])) return $params[$param]['default'];
        return null;
    }

    public function getMetadataValue($param){
        if(isset($this->metadata) && isset($this->metadata[$param])) return $this->metadata[$param];
        return '';
    }

    /**
     * Obtiene el valor por defecto de un tipo de dato
     *
     * @access public
     * @return string
     */
    public function getDefaultValue(){
        return isset($this->metadata['params']['default']) ? $this->metadata['params']['default'] : "";
    }

    public function isTextType(){
        return ($this->datatype === "text");
    }

    public function isBinaryType(){
        return ($this->datatype === "binary");
    }

    public function setUnsafeMode(){
        $this->unsafemode = true;
    }

    public function unsetUnsafeMode(){
        $this->unsafemode = false;
    }

    public function hasUnsafeMode(){
        return $this->unsafemode;
    }
}
