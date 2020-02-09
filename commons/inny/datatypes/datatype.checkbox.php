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
class InnyType_Checkbox extends InnyType_Text {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'checkedText' => array(
            'required' => false,
            'default' => "Checked"
        ),
        'uncheckedText' => array(
            'required' => false,
            'default' => "Unchecked"
        )
    );

    /**
     * InnyType_Richtext constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'checkbox';
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

        foreach($values as $val){
            if(!isset($val) && $required){
                $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                $validValues = self::VALUE_INVALID;
            }elseif(isset($val)) {
                if($val != "true" && $val != "false" && $val != true && $val != false){
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

    /**
     * Devuelve la preview del tipo de dato
     *
     * @access public
     * @return string
     */
    public function preview(){
        $multilang = $this->getParamValue("multilang");

        $fieldName = ucfirst($this->getMetadataValue('name'));

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("fieldName",$fieldName);

        $content = "";
        if ($multilang) {
            $languages = $this->getParamValue("languages");
            foreach($languages as $key => $lang){
                $value = $this->getValue($key);
                $value = ($value == "true") ? $this->getParamValue('checkedText') : $this->getParamValue('uncheckedText');
                $smarty->assign("fieldLang",$lang);
                $smarty->assign("value",$value);
                $content .= $smarty->fetch("text-view.tpl");
                $smarty->assign("fieldName","");
            }
            return $content;
        }else{
            $value = $this->getValue();
            $value = ($value == "true") ? $this->getParamValue('checkedText') : $this->getParamValue('uncheckedText');
            $smarty->assign("value",$value);
            $content = $smarty->fetch("text-view.tpl");
        }

        return $content;
    }

    /**
     * Retorna el código HTML correspondiente al input del checkbox
     *
     * @access public
     * @return string HTML input checkbox
     */
    public function htmlInput(){
        $field      = $this->getMetadataValue("field");
        $required   = $this->getParamValue("required");
        $multilang  = $this->getParamValue("multilang");
        $text       = $this->getParamValue("text");

        $requiredText   = ($required == true) ? "*" : "";
        $fieldName      = ucfirst($this->getMetadataValue('name'));
        $helpText       = ucfirst($this->getMetadataValue("help"));

        $checkboxText   = (isset($text)) ? $text : $fieldName;

        $htmlInput      = "";

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("requiredText",$requiredText);
        $smarty->assign("helpText",$helpText);
        $smarty->assign("checkboxText",$checkboxText);

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
                $checkedText = ($value == '1' ? 'checked="checked"' : '');

                $smarty->assign("field",$field);
                $smarty->assign("fieldName",$fieldName);
                $smarty->assign("value",$value);
                $smarty->assign("checkedText",$checkedText);

                $htmlInput .= $smarty->fetch("checkbox-html.tpl");
            }
        }else{
            $value = $this->hasValue() ? $this->getValue() : $this->getDefaultValue();

            $checkedText = (($value == 'true' || $value == true) ? 'checked="checked"' : '');

            $smarty->assign("field",$field);
            $smarty->assign("fieldName",$fieldName);
            $smarty->assign("value",$value);
            $smarty->assign("checkedText",$checkedText);

            $htmlInput = $smarty->fetch("checkbox-html.tpl");
        }

        return $htmlInput;

    }

}
################################################################################
?>