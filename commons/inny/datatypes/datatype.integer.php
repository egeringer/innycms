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
class InnyType_Integer extends InnyType_Text {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'min' => array(
            'required' => false,
            'default' => ''
        ),
        'max' => array(
            'required' => false,
            'default' => ''
        ),
        'thousands_sep' => array(
            'required' => false,
            'default' => '.'
        ),
        'step' => array(
            'required' => false,
            'default' => '1'
        )
    );

    /**
     * InnyType_Textarea constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'integer';
    }

    /**
     * Verifica si una cadena es un número entero válido
     *
     * @param string $integer número entero
     * @param string $thousands_sep separación de miles
     * @static
     * @access public
     * @return boolean
     */
    public static function is_integer($integer,$thousands_sep=''){
        $pattern = '/^[-]{0,1}([0-9]+)'.($thousands_sep != '' ? '('.$thousands_sep.'[0-9][0-9][0-9])*' : '').'$/';
        return (preg_match($pattern,$integer) >= 1);
    }

    /**
     * Obtiene el valor entero de una variable
     *
     * @param string $integer número entero
     * @param string $thousands_sep separación de miles
     * @static
     * @access public
     * @return integer
     */

    public static function int_value($integer,$thousands_sep=''){
        return intval(($thousands_sep != '' ? str_replace($thousands_sep,'',$integer) : $integer),10);
    }

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
        if($multilang){
            $vals = json_decode($value,true);
            if(!is_array($vals)){
                $return['valid'] = self::VALUE_INVALID;
                $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                return $return;
            }
            $languages = $this->getParamValue("languages");
            foreach($languages as $key => $lang){
                $values[$key] = $vals[$key];
            }
        }else{
            $values[] = $value;
        }

        $validValues = self::VALUE_VALID;
        # Obtengo la separación de miles
        $thousands_sep = $this->getParamValue('thousands_sep');

        $return['value'] = "";
        foreach($values as $key => $val){
            # Obtengo el valor trimmeado
            $data = Denko::trim($val);

            #Chequeo por vacío en caso de ser requerido
            if(empty($data) && $required){
                $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                $validValues = self::VALUE_INVALID;
                continue;
            }

            #Acepto vacío en caso de no ser requerido
            if(empty($data) && !$required){
                $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_NONE];
                continue;
            }

            # Verifico que sea un entero válido
            if(!self::is_integer($data,$thousands_sep)){
                $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_FORMAT];
                $validValues = self::VALUE_INVALID;
                continue;
            }

            # Verifico los si está dentro de los rangos seteados
            $min = $this->getParamValue('min');
            $max = $this->getParamValue('max');
            if($min != '' || $max != ''){
                $int_value = self::int_value($data,$thousands_sep);
                if($min != '' && (self::int_value($min,$thousands_sep) > $int_value)){
                    $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_RANGE];
                    $validValues = self::VALUE_INVALID;
                    continue;
                }
                if($max != '' && self::int_value($max,$thousands_sep) < $int_value){
                    $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_RANGE];
                    $validValues = self::VALUE_INVALID;
                    continue;
                }
            }
        }

        $return['value'] = $value;
        $return['valid'] = $validValues;
        return $return;
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

        if($lang == null && Denko::isInt($this->value)) return true;

        if($lang != null){
            $values = json_decode($this->value,true);
            return Denko::isInt($values[$lang]);
        }

        return false;
    }

    public function htmlInput(){
        $type       = "number";
        $field      = $this->getMetadataValue("field");
        $required   = $this->getParamValue("required");
        $multilang  = $this->getParamValue("multilang");
        $disabled   = $this->getParamValue("disabled");
        $readonly   = $this->getParamValue("readonly");

        $minValue   = $this->getParamValue("min");
        $maxValue   = $this->getParamValue("max");
        $stepValue  = $this->getParamValue("step");

        $requiredParam  = ($required == true) ? "required='required'" : "";
        $disabledParam  = ($disabled == true) ? "disabled='disabled'" : "";
        $readonlyParam  = ($readonly == true) ? "readonly='readonly'" : "";

        $requiredText   = ($required == true) ? "*" : "";
        $fieldName      = ucfirst($this->getMetadataValue('name'));
        $helpText       = ucfirst($this->getMetadataValue("help"));

        $additionalParams = "min='$minValue' max='$maxValue' step='$stepValue'";

        $htmlInput = "";

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("type",$type);
        $smarty->assign("requiredText",$requiredText);
        $smarty->assign("helpText",$helpText);
        $smarty->assign("requiredParam",$requiredParam);
        $smarty->assign("disabledParam",$disabledParam);
        $smarty->assign("readonlyParam",$readonlyParam);
        $smarty->assign("additionalParams",$additionalParams);

        if ($multilang === true) {
            $languages = $this->getParamValue("languages");
            $originalName = $field;
            $htmlInput .= "<div class='form-group m-form__group form-group-$originalName'>";
            $htmlInput .= "<label class='control-label'><strong>$fieldName <span class='text-danger'>$requiredText</span></strong></label><br/>";
            $htmlInput .= "<span id='help-$originalName' data-original-text='$helpText' class='help-block'>$helpText</span><hr/>";
            $htmlInput .= "</div>";
            foreach($languages as $key => $lang){
                $field = $originalName."_$key";
                $fieldName = $lang;
                $value = $this->hasValue($key) ? $this->getValue($key) : $this->getDefaultValue();

                $smarty->assign("field",$field);
                $smarty->assign("fieldName",$fieldName);
                $smarty->assign("value",$value);
                $smarty->assign("helpText","");

                $htmlInput .= $smarty->fetch("input-html.tpl");
            }
        }else{
            $value = $this->hasValue() ? $this->getValue() : $this->getDefaultValue();

            $smarty->assign("type",$type);
            $smarty->assign("field",$field);
            $smarty->assign("fieldName",$fieldName);
            $smarty->assign("value",$value);

            $htmlInput = $smarty->fetch("input-html.tpl");
        }

        return $htmlInput;

    }

}
################################################################################
?>