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
class InnyType_Email extends InnyType_Text {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'pattern' => array(
            'required' => false,
            'default' => '/^[A-Z0-9._%\-]+@[A-Z0-9._%\-]+\.[A-Z]{2,4}$/i'
        ),
        'multiple' => array(
            'required' => false,
            'default' => false
        )
    );

    /**
     * InnyType_Textarea constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'email';
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
        $multiple = $this->getParamValue('multiple');

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
        # Obtengo el patrón de expresión regular
        $email_pattern = $this->getParamValue('pattern');

        $return['value'] = "";
        $clearedEmails = "";
        foreach($values as $key => $val) {
            $clearedEmails = "";
            $val = Denko::trim($val);
            if (empty($val) && $required) {
                $validValues = self::VALUE_INVALID;
                $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
            } else {
                if ($multiple) {
                    $emails = explode(',', $val);
                    $validIteration = self::VALUE_VALID;
                    foreach ($emails as $email) {
                        $trimmedEmail = Denko::trim($email);
                        if (!empty($trimmedEmail)){
                            if(!preg_match($email_pattern, $trimmedEmail)) $validIteration = self::VALUE_INVALID;
                            else $clearedEmails .= !empty($clearedEmails) ? ", $trimmedEmail" : $trimmedEmail;
                        }
                    }
                    if(!$validIteration){
                        $validValues = self::VALUE_INVALID;
                        $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_FORMAT];
                    }else{
                        $values[$key] = $clearedEmails;
                    }
                } else {
                    # En caso que sea simplemente un email
                    if(empty(Denko::trim($val)) && !$required){
                        $validValues = self::VALUE_VALID;
                        $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_NONE];
                    } else if (preg_match($email_pattern, Denko::trim($val)) == 1) {
                        $clearedEmails = Denko::trim($val);
                        $values[$key] = $clearedEmails;
                    } else {
                        $validValues = self::VALUE_INVALID;
                        $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_FORMAT];
                    }
                }
            }
        }

        if($multilang) $clearedEmails = json_encode($values);
        $return['value'] = $clearedEmails;
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

        $email_pattern = $this->getParamValue('pattern');

        if($lang == null && preg_match($email_pattern, $this->value)) return true;

        if($lang != null){
            $values = json_decode($this->value,true);
            if(!preg_match($email_pattern, $values[$lang])) return true;
            $emails = explode(',', $values[$lang]);
            foreach ($emails as $email) {
                $trimmedEmail = Denko::trim($email);
                if (!preg_match($email_pattern, $trimmedEmail)) return false;
            }
            return true;
        }

        return false;
    }

    public function htmlInput(){
        $type       = "email";
        $field      = $this->getMetadataValue("field");
        $required   = $this->getParamValue("required");
        $multilang  = $this->getParamValue("multilang");
        $disabled   = $this->getParamValue("disabled");
        $readonly   = $this->getParamValue("readonly");

        $multiple   = $this->getParamValue("multiple");

        $requiredParam  = ($required == true) ? "required='required'" : "";
        $disabledParam  = ($disabled == true) ? "disabled='disabled'" : "";
        $readonlyParam  = ($readonly == true) ? "readonly='readonly'" : "";

        $requiredText   = ($required == true) ? "*" : "";
        $fieldName      = ucfirst($this->getMetadataValue('name'));
        $helpText       = ucfirst($this->getMetadataValue("help"));

        $additionalParams = ($multiple == true) ? "multiple='multiple'" : "";

        $htmlInput      = "";

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