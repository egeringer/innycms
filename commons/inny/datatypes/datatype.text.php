<?php
/**
 * Created by PhpStorm.
 * User: egeringer
 * Date: 9/17/16
 * Time: 11:47 AM
 */

require_once dirname(__FILE__).'/datatype.php';

class InnyType_Text extends InnyDataType {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'unique' => array(
            'required' => false,
            'default' => 'no',
            'values' => array(
                'no',
                'ci',
                'cs'
            )
        ),
        'multilang' => array(
            'required' => false,
            'default' => false
        )
    );

    /**
     * InnyType_Text constructor.
     */
    public function __construct(){
        $this->type = 'text';
        $this->datatype = 'text';
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
            $this->value = $validate['value'];
            $return['success'] = self::INSERT_SUCCESS;
            $return['value'] = $validate['value'];
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

        $return['value'] = "";
        foreach($values as $key => $val){
            $val = Denko::trim($val);
            if(empty($val) && $required){
                $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                $validValues = self::VALUE_INVALID;
            }else{
                $return['value'] = $value;
            }
        }

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
                $smarty->assign("fieldLang",$lang);
                $smarty->assign("value",$value);
                $content .= $smarty->fetch("text-view.tpl");
                $smarty->assign("fieldName","");
            }
            return $content;
        }else{
            $value = $this->getValue();
            $smarty->assign("value",$value);
            $content = $smarty->fetch("text-view.tpl");
        }

        return $content;
    }

    /**
     * Retorna todos los parametros del tipo junto con los del padre
     *
     * @access public
     * @return array
     */
    public static function getParams(){
        return array_merge(self::$typeParams,parent::getParams());
    }

    public function htmlInput(){
        $type       = "text";
        $field      = $this->getMetadataValue("field");
        $required   = $this->getParamValue("required");
        $multilang  = $this->getParamValue("multilang");
        $disabled   = $this->getParamValue("disabled");
        $readonly   = $this->getParamValue("readonly");

        $requiredParam  = ($required == true) ? "required='required'" : "";
        $disabledParam  = ($disabled == true) ? "disabled='disabled'" : "";
        $readonlyParam  = ($readonly == true) ? "readonly='readonly'" : "";

        $requiredText   = ($required == true) ? "*" : "";
        $fieldName      = ucfirst($this->getMetadataValue('name'));
        $helpText       = ucfirst($this->getMetadataValue("help"));

        $htmlInput      = "";

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("type",$type);
        $smarty->assign("requiredText",$requiredText);
        $smarty->assign("helpText",$helpText);
        $smarty->assign("requiredParam",$requiredParam);
        $smarty->assign("disabledParam",$disabledParam);
        $smarty->assign("readonlyParam",$readonlyParam);

        if ($multilang === true) {
            $languages = $this->getParamValue("languages");
            $originalName = $field;
            $htmlInput .= "<div class='form-group m-form__group form-group-$originalName'>";
            $htmlInput .= "<label class='control-label'><h3>$fieldName <span class='text-danger'>$requiredText</span></h3></label>";
            $htmlInput .= ($helpText) ? "<br/><span id='help-$originalName' data-original-text='$helpText' class='hm-form__help'>$helpText</span>" : "";
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