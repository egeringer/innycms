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
class InnyType_Radio extends InnyType_Text {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'checked' => array(
            'required' => false,
            'default' => 'false'
        ),
        'options' => array(
            'required' => true
        ),
        'default' => array(
            'required' => true
        )
    );

    /**
     * InnyType_Radio constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'radio';
    }

    /**
     * Valida el contenido que se le setea al tipo
     *
     * @access public
     * @return array
     */
    public function validate($value = null){
        $return = array();
        $return['msg_list'] = array();

        if($this->hasUnsafeMode()){
            $return['value'] = $value;
            $return['valid'] = self::VALUE_VALID;
            return $return;
        }

        $required = $this->getParamValue('required');
        $multilang = $this->getParamValue('multilang');

        $values = array();
        if($multilang === "true" || $multilang === true){
            $values = json_decode($value,true);
            if(!is_array($values)){
                $return['valid'] = self::VALUE_INVALID;
                $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                return $return;
            }
        }else{
            $values[] = $value;
        }

        $validValues = self::VALUE_VALID;

        # Obtengo el conjunto de opciones posibles para el select
        $options = $this->getParamValue('options');

        foreach($values as $val){
            if(empty($val) && $required){
                $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                $validValues = self::VALUE_INVALID;
            }elseif(!empty($val)) {
                if(!array_key_exists($val,$options)){
                    $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_RANGE];
                    $validValues = self::VALUE_INVALID;
                }else{
                    $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_NONE];
                }
            }else{
                $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                $validValues = self::VALUE_INVALID;
            }
        }

        $return['value'] = $value;
        $return['valid'] = $validValues;

        return $return;
    }

    public function getDefaultValue(){
        # Obtengo el conjunto de opciones posibles para el select
        $options = $this->getParamValue('options');
        # Verifico si existe valor por default y si además es válido (o sea, pertenece al conjunto de opciones)
        $default = $this->getParamValue('default');
        return (isset($default) && !empty($default) && array_key_exists($default,$options)) ? $default : null;
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

        //Chequeo puntual para el tipo radio
        if(!is_array($parameters['options']) || empty($parameters['options']))
            $errors[] = "El parametro options del radio no tiene formato válido";

        if(is_array($parameters['options']) && !empty($parameters['options']) && !array_key_exists($parameters['default'],$parameters['options']))
            $errors[] = "El valor por defecto ".$parameters['default']." no es parte de las opciones del radio";

        return $errors;
    }

    /**
     * Devuelve la preview del tipo de dato
     *
     * @access public
     * @return string
     */
    public function preview(){
        $multilang = $this->getParamValue("multilang");
        $options = $this->getParamValue('options');

        $fieldName = ucfirst($this->getMetadataValue('name'));

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("fieldName",$fieldName);

        $content = "";
        if ($multilang) {
            $languages = $this->getParamValue("languages");
            foreach($languages as $key => $lang){
                $value = $this->getValue($key);
                $textValue = !empty($value) ? $options[$value] : "";
                $smarty->assign("fieldLang",$lang);
                $smarty->assign("value",$textValue);
                $content .= $smarty->fetch("text-view.tpl");
                $smarty->assign("fieldName","");
            }
            return $content;
        }else{
            $value = $this->getValue();
            $textValue = !empty($value) ? $options[$value] : "";
            $smarty->assign("value",$textValue);
            $content = $smarty->fetch("text-view.tpl");
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

        $options = $this->getParamValue('options');

        if($lang == null && array_key_exists($this->value,$options)) return true;

        if($lang != null){
            $values = json_decode($this->value,true);
            if(array_key_exists($values[$lang],$options)) return true;
            return false;
        }

        return false;
    }

    public function htmlInput(){
        $field      = $this->getMetadataValue("field");
        $required   = $this->getParamValue("required");
        $multilang  = $this->getParamValue("multilang");
        $disabled   = $this->getParamValue("disabled");

        $options    = $this->getParamValue('options');

        $requiredParam  = ($required == true) ? "required='required'" : "";
        $disabledParam  = ($disabled == true) ? "disabled='disabled'" : "";

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
                if(!array_key_exists($value,$options)) $value = $this->getDefaultValue();

                $smarty->assign("field",$field);
                $smarty->assign("fieldName",$fieldName);
                $smarty->assign("value",$value);
                $smarty->assign("helpText","");

                $htmlInput .= $smarty->fetch("radio-html.tpl");
            }
        }else{
            $value = $this->hasValue() ? $this->getValue() : $this->getDefaultValue();
            if(!array_key_exists($value,$options)) $value = $this->getDefaultValue();

            $smarty->assign("field",$field);
            $smarty->assign("fieldName",$fieldName);
            $smarty->assign("value",$value);

            $htmlInput = $smarty->fetch("radio-html.tpl");
        }

        return $htmlInput;

    }
}
################################################################################
?>