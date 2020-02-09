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
class InnyType_Textarea extends InnyType_Text {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array();

    /**
     * InnyType_Textarea constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'textarea';
    }

    public function htmlInput(){
        $field      = $this->getMetadataValue("field");
        $required   = $this->getParamValue("required");
        $multilang  = $this->getParamValue("multilang");
        $disabled   = $this->getParamValue("disabled");
        $readonly   = $this->getParamValue("readonly");

        $requiredParam = ($required == true) ? "required='required'" : "";
        $disabledParam = ($disabled == true) ? "disabled='disabled'" : "";
        $readonlyParam = ($readonly == true) ? "readonly='readonly'" : "";

        $requiredText = ($required == true) ? "*" : "";
        $fieldName = ucfirst($this->getMetadataValue('name'));
        $helpText = ucfirst($this->getMetadataValue("help"));

        $htmlInput = "";

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("requiredText", $requiredText);
        $smarty->assign("helpText", $helpText);
        $smarty->assign("requiredParam", $requiredParam);
        $smarty->assign("disabledParam", $disabledParam);
        $smarty->assign("readonlyParam", $readonlyParam);

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
                $smarty->assign("helpText", "");

                $htmlInput .= $smarty->fetch("textarea-html.tpl");
            }
        }else {
            $value = $this->hasValue() ? $this->getValue() : $this->getDefaultValue();

            $smarty->assign("field", $field);
            $smarty->assign("fieldName", "<strong>" . $fieldName . "</strong>");
            $smarty->assign("value", $value);

            $htmlInput = $smarty->fetch("textarea-html.tpl");
        }
        return $htmlInput;

    }
}
################################################################################
?>