<?php
/**
 * Created by PhpStorm.
 * User: egeringer
 * Date: 9/17/16
 * Time: 11:47 AM
 */

require_once dirname(__FILE__).'/datatype.text.php';

class InnyType_Reference extends InnyDataType {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'multilang' => array(
            'required' => false,
            'default' => false
        ),
        'keyField' => array(
            'required' => false,
            'default' => 'public_id'
        )
    );

    /**
     * InnyType_Text constructor.
     */
    public function __construct(){
        $this->type = 'reference';
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
        $validate = $this->validate($value);
        if($validate['valid'] == self::VALUE_VALID){
            $this->value = $validate['value'];
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
    public function validate($value = null){

        $return = array();
        $return['msg_list'] = array();

        if($this->hasUnsafeMode()){
            $return['value'] = $value;
            $return['valid'] = self::VALUE_VALID;
            return $return;
        }

        # Obtengo la configuración si el valor es multilenguaje
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

        # Obtengo la configuración si el valor es requerido
        $required = $this->getParamValue('required');
        # Obtengo la configuración si el valor puede ser multiple
        $multiple = $this->getParamValue('multiple');

        foreach($values as $val){
            if(empty($val) && $required){
                $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
                $validValues = self::VALUE_INVALID;
            }elseif(!empty($val)) {
                if ($multiple == true || $multiple == 'true') {
                    $validIteration = self::VALUE_VALID;
                    if(!is_array($val)) $options = explode(',', $val);
                    else $options = $val;
                    foreach ($options as $v) {
                        $trimmedVal = Denko::trim($v);
                        if (!empty($trimmedVal) && false) {
                            // Check the ID Exists?
                            $validIteration = self::VALUE_INVALID;
                        }
                    }
                    if(!$validIteration){
                        $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_RANGE];
                        $validValues = self::VALUE_INVALID;
                    }else{
                        $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_NONE];
                    }
                }else{
                    if(false){
                        // Check the ID Exists?
                        $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_RANGE];
                        $validValues = self::VALUE_INVALID;
                    }else{
                        $return['msg_list'][] = static::$errorMessages[self::VALUE_ERROR_NONE];
                    }
                }
            }
        }

        if(is_array($value)) $value = implode(",", $value);

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

        $field      = $this->getMetadataValue("field");
        $required   = $this->getParamValue("required");
        $multilang  = $this->getParamValue("multilang");
        $disabled   = $this->getParamValue("disabled");
        $readonly   = $this->getParamValue("readonly");
        $multiple   = $this->getParamValue('multiple');

        $requiredParam  = ($required == true) ? "required='required'" : "";
        $disabledParam  = ($disabled == true) ? "disabled='disabled'" : "";
        $readonlyParam  = ($readonly == true) ? "readonly='readonly'" : "";
        $multipleParam  = ($multiple == true) ? "multiple='multiple'" : "";

        $requiredText   = ($required == true) ? "*" : "";
        $fieldName      = ucfirst($this->getMetadataValue('name'));
        $helpText       = ucfirst($this->getMetadataValue("help"));

        $collectionReference    = $this->getParamValue('collection');
        $referenceFieldName     = $this->getParamValue('field');
        $referenceKeyField      = $this->getParamValue('keyField');

        $htmlInput = "";

        $smarty = new Smarty();
        $smarty->addTemplateDir("../commons/inny/datatypes/templates");
        $smarty->assign("requiredText",$requiredText);
        $smarty->assign("helpText",$helpText);
        $smarty->assign("requiredParam",$requiredParam);
        $smarty->assign("disabledParam",$disabledParam);
        $smarty->assign("readonlyParam",$readonlyParam);
        $smarty->assign("multipleParam",$multipleParam);

        if ($multilang === true) {
            $languages = $this->getParamValue("languages");
            $originalName = $field;
            $htmlInput .= "<div class='form-group m-form__group form-group-$originalName'>";
            $htmlInput .= "<label class='control-label'><strong>$fieldName <span class='text-danger'>$requiredText</span></strong></label><br/>";
            $htmlInput .= "<span id='help-$originalName' data-original-text='$helpText' class='hm-form__help'>$helpText</span><hr/>";
            $htmlInput .= "</div>";
            foreach($languages as $key => $lang){
                $field = $originalName."_$key";
                $fieldName = $lang;
                $value = $this->hasValue($key) ? $this->getValue($key) : $this->getDefaultValue();

                $smarty->assign("field",$field);
                $smarty->assign("fieldName",$fieldName);
                $smarty->assign("value",$value);
                $smarty->assign("helpText","");
                $smarty->assign("collectionReference",$collectionReference);
                $smarty->assign("referenceFieldName",$referenceFieldName);
                $smarty->assign("referenceKeyField",$referenceKeyField);

                $htmlInput .= $smarty->fetch("reference-html.tpl");
            }
        }else{
            $value = $this->hasValue() ? $this->getValue() : $this->getDefaultValue();

            $smarty->assign("field",$field);
            $smarty->assign("fieldName",$fieldName);
            $smarty->assign("value",$value);
            $smarty->assign("collectionReference",$collectionReference);
            $smarty->assign("referenceFieldName",$referenceFieldName);
            $smarty->assign("referenceKeyField",$referenceKeyField);

            $htmlInput = $smarty->fetch("reference-html.tpl");
        }

        return $htmlInput;

    }
}