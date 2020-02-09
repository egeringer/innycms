<?php
/**
 *
 *
 */
require_once dirname(__FILE__).'/datatype.text.php';

/**
 *
 *
 */
class InnyType_Select extends InnyType_Text {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'options' => array(
            'required' => true
        ),
        'default' => array(
            'required' => true
        ),
        'multiple' => array(
            'required' => false,
            'default' => false
        )
    );

    /**
     * InnyType_Select constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'select';
    }

    public function getDefaultValue(){
        # Obtengo el conjunto de opciones posibles para el select
        $options = $this->getParamValue('options');
        # Verifico si existe valor por default y si además es válido (o sea, pertenece al conjunto de opciones)
        $default = $this->getParamValue('default');
        if(isset($default) && !empty($default) && $this->getParamValue("multiple") == false && array_key_exists($default,$options)) return $default;
        if (isset($default) && !empty($default) && $this->getParamValue("multiple") == true){
            $arr = array_filter(explode(",",$default));
            $valid = true;
            if($arr){
                foreach($arr as $def){
                    if (!array_key_exists($def,$options)) $valid = false;
                }
            }
            if($valid) return $arr;
        }
        return null;
    }

    /**
     * Valida los parametros del tipo de dato y los asigna
     * @param array $parameters parametros en formateada en arreglo
     * @access public
     * @return array
     */
    public function validateParams($parameters){
        $errors = parent::validateParams($parameters);

        if(!empty($errors)) return $errors;

        //Chequeo puntual para el tipo select
        if(!is_array($parameters['options']) || empty($parameters['options']))
            $errors[] = "Options param has an invalid format";

        if(is_array($parameters['options']) && !empty($parameters['options'])  && (!isset($parameters['multiple']) || $parameters['multiple'] == false) && !array_key_exists($parameters['default'],$parameters['options']))
            $errors[] = "Default value param ".$parameters['default']." is not part of the options";

        if(is_array($parameters['options']) && !empty($parameters['options'])  && isset($parameters['multiple']) && $parameters['multiple'] == true){
            $arr = array_filter(explode(",",$parameters['default']));
            if($arr){
                foreach($arr as $def){
                    if (!array_key_exists($def,$parameters['options'])) $errors[] = "Default value param $def is not part of the options";
                }
            }
        }

        return $errors;
    }

    /**
     * Setea el valor al tipo de dato y lo verifica para setearlo
     *
     * @param mixed $value valor del tipo de dato
     * @access public
     * @return array
     */
    public function setValue($value){
        $return = array();
        $validate = $this->validate($value);
        if($validate['valid'] == self::VALUE_VALID){
            $default = $this->getParamValue('default');
            $value = (empty($validate['value']) && !empty($default)) ? $default : $validate['value'];
            $this->value = $value;
            $return['value'] = $value;
            $return['success'] = self::INSERT_SUCCESS;
        }else{
            $return['success'] = self::INSERT_FAIL;
        }
        $return['validate'] = $validate;
        return $return;
    }

    /**
     * Valida el contenido que se le setea al tipo
     *
     * @access public
     * @return array
     */

    private function validateSingleValue($value){

        $value = strval($value);

        $return = array();

        $required = $this->getParamValue('required');
        $options = $this->getParamValue('options');

        if (empty($value) && !strlen($value)){
            if($required){
                $return['valid'] = self::VALUE_INVALID;
                $return['value'] = $value;
                $return['messages'] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                return $return;
            }else{
                $return['valid'] = self::VALUE_VALID;
                $return['value'] = $value;
                $return['messages'] = static::$errorMessages[self::VALUE_ERROR_NONE];
                return $return;
            }
        }else{
            if(!array_key_exists($value,$options)) {
                if($required){
                    // IF UNSAFE MODE => Le seteo el default
                    // IF SAFE MODE => Le devuelvo invalido
                }else{
                    $return['valid'] = self::VALUE_VALID;
                    $return['value'] = "";
                    $return['messages'] = static::$errorMessages[self::VALUE_ERROR_RANGE];
                    return $return;
                }
            }else{
                $return['valid'] = self::VALUE_VALID;
                $return['value'] = $value;
                $return['messages'] = static::$errorMessages[self::VALUE_ERROR_NONE];
                return $return;
            }
        }
    }

    private function validateMultipleValues($values){
        $return = array();

        $return['valid'] = self::VALUE_VALID;
        $return['messages'] = array();

        $valuesArray = is_array($values) ? $values : explode(",",$values);
        $newValuesArray = array();

        foreach($valuesArray as $value){
            $validation = $this->validateSingleValue($value);
            if($validation['valid'] === self::VALUE_INVALID){
                $return['valid'] = self::VALUE_INVALID;
            }else{
                $newValuesArray[] = $validation['value'];
            }

            $return['messages'][$value] = $validation['messages'];
        }

        $return['value'] = implode(",",$newValuesArray);

        return $return;
    }

    private function validateMultilangValues($values){
        $return = array();

        $return['valid'] = self::VALUE_VALID;
        $return['messages'] = array();

        $valuesArray = json_decode($values,true);
        $newValuesArray = array();

        if(!is_array($valuesArray)){
            $return['valid'] = self::VALUE_INVALID;
            $return['messages'] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
            return $return;
        }

        $multiple = $this->getParamValue('multiple');

        foreach($valuesArray as $key => $value){
            if($multiple){
                $validation = $this->validateMultipleValues($value);
            } else {
                $validation = $this->validateSingleValue($value);
            }

            if($validation['valid'] === self::VALUE_INVALID){
                $return['valid'] = self::VALUE_INVALID;
            }else{
                $newValuesArray[$key] = $validation['value'];
            }

            $return['messages'][$key] = $validation['messages'];
        }

        $return['value'] = json_encode($newValuesArray);
        return $return;
    }

    public function validate($value = null){
        $return = array();

        $return['valid'] = self::VALUE_VALID;
        $return['messages'] = array();

        if($this->hasUnsafeMode()){
            $return['value'] = $value;
            $return['valid'] = self::VALUE_VALID;
            return $return;
        }

        # Obtengo la configuración si el valor es multilenguaje
        $multilang = $this->getParamValue('multilang');
        if($multilang === true){
            return $this->validateMultilangValues($value);
        }

        # Obtengo la configuración si el valor puede ser multiple
        $multiple = $this->getParamValue('multiple');
        if($multiple === true){
            return $this->validateMultipleValues($value);
        }

        return $this->validateSingleValue($value);
    }

    public function is_empty(){
        return false;
    }

    /**
     * Devuelve la preview del tipo de dato
     *
     * @access public
     * @return string
     */
    public function preview(){
        $multilang = $this->getParamValue("multilang");
        $multiple   = $this->getParamValue("multiple");
        $options = $this->getParamValue('options');

        $fieldName = ucfirst($this->getMetadataValue('name'));

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("fieldName",$fieldName);
        $smarty->assign("multiple",$multiple);
        $smarty->assign("options",$options);

        $content = "";
        if ($multilang) {
            $languages = $this->getParamValue("languages");
            foreach($languages as $key => $lang){
                $value = $this->getValue($key);
                $smarty->assign("value",$value);
                $smarty->assign("fieldLang",$lang);
                $content .= $smarty->fetch("select-view.tpl");
                $smarty->assign("fieldName","");
            }
            return $content;
        }else{
            $value = $this->getValue();
            $smarty->assign("value",$value);
            $content = $smarty->fetch("select-view.tpl");
        }

        return $content;
    }

    /**
     * Devuelve true o false dependiendo si el tipo está seteado o no
     *
     * @access public
     * @return boolean
     */
    public function hasValue($lang = null){

        $hasValue = parent::hasValue($lang);

        if(!$hasValue) return false;

        $value = $this->value;

        # Obtengo la configuración si el valor es multilenguaje
        $multilang = $this->getParamValue('multilang');
        if($multilang === true){
            $validation = $this->validateMultilangValues($value);
            return $validation['valid'];
        }

        # Obtengo la configuración si el valor puede ser multiple
        $multiple = $this->getParamValue('multiple');
        if($multiple === true){
            $validation = $this->validateMultipleValues($value);
            return $validation['valid'];
        }

        $validation = $this->validateSingleValue($value);
        return $validation['valid'];
    }

    public function htmlInput(){
        $field      = $this->getMetadataValue("field");
        $required   = $this->getParamValue("required");
        $multilang  = $this->getParamValue("multilang");
        $disabled   = $this->getParamValue("disabled");
        $multiple   = $this->getParamValue("multiple");

        $options    = $this->getParamValue('options');

        $requiredParam  = ($required == true) ? "required='required'" : "";
        $disabledParam  = ($disabled == true) ? "disabled='disabled'" : "";
        $multipleParam  = ($multiple == true) ? "multiple='multiple'" : "";

        $requiredText   = ($required == true) ? "*" : "";
        $fieldName      = ucfirst($this->getMetadataValue('name'));
        $helpText       = ucfirst($this->getMetadataValue("help"));

        $htmlInput      = "";

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("requiredText",$requiredText);
        $smarty->assign("helpText",$helpText);
        $smarty->assign("options",$options);
        $smarty->assign("requiredParam",$requiredParam);
        $smarty->assign("disabledParam",$disabledParam);
        $smarty->assign("multipleParam",$multipleParam);

        if ($multilang === true) {
            $languages = $this->getParamValue("languages");
            $originalName = $field;
            $htmlInput .= "<div class='form-group m-form__group form-group-$originalName'>";
            $htmlInput .= "<label class='control-label'><strong>$fieldName <span class='text-danger'>$requiredText</span></strong></label><br/>";
            $htmlInput .= "<span id='help-$originalName' data-original-text='$helpText' class='help-block'>$helpText</span><hr/>";
            $htmlInput .= "</div>";
            foreach($languages as $langKey => $lang){
                $field = $originalName."_$langKey";
                $fieldName = $lang;

                $value = $this->hasValue($langKey) ? $this->getValue($langKey) : $this->getDefaultValue();

                $smarty->assign("field",$field);
                $smarty->assign("fieldName",$fieldName);
                $smarty->assign("value",$value);
                $smarty->assign("helpText","");

                $htmlInput .= $smarty->fetch("select-html.tpl");
            }
        }else{

            $value = $this->hasValue() ? $this->getValue() : $this->getDefaultValue();

            $smarty->assign("field",$field);
            $smarty->assign("fieldName",$fieldName);
            $smarty->assign("value",$value);

            $htmlInput = $smarty->fetch("select-html.tpl");
        }

        return $htmlInput;

    }
}
################################################################################
?>