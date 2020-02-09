<?php

require_once dirname(__FILE__).'/datatype.php';

abstract class InnyType_Binary extends InnyDataType {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'description' => array(
            'required' => false,
            'default' => 'none'
        ),
        'allowed_extensions' => array(
            'required' => false,
            'default' => '*'
        ),
        'allowed_mimes' => array(
            'required' => false
        )
    );

    /**
     * InnyType_File constructor.
     */
    public function __construct(){
        $this->datatype = 'binary';
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

    /**
     * Setea el valor al tipo de dato
     *
     * @param mixed $value valor del tipo de dato
     * @access public
     * @return array
     */
    public function setValue($value){
        $return = array();

        $validate = $this->validate($value);

        if($validate['valid'] == self::VALUE_VALID) {
            $return['success'] = self::INSERT_SUCCESS;
            $return['value'] = $validate['value'];
            $this->value = $validate['value'];
        }else{
            $return['success'] = self::INSERT_FAIL;
        }
        $return['validate'] = $validate;

        return $return;
    }

    /**
     * Valida el valor que se le intentará setear al tipo
     * @param mixed $value valor que se intentará validar para el tipo
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
        $quantity = $this->getParamValue('quantity');

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
        $clearedIds = "";
        foreach($values as $key => $val) {
            $clearedIds = "";
            $val = Denko::trim($val);
            if (empty($val) && $required) {
                $validValues = self::VALUE_INVALID;
                $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_EMPTY];
            } else {
                if ($quantity > 1) {
                    $ids = explode(',', $val);
                    $validIteration = self::VALUE_VALID;
                    foreach ($ids as $id) {
                        $trimmedId = Denko::trim($id);
                        if (!empty($trimmedId)){
                            if(!InnyCMS::fileExists($id)) $validIteration = self::VALUE_INVALID;
                            else $clearedIds .= !empty($clearedIds) ? ", $trimmedId" : $trimmedId;
                        }
                    }
                    if(!$validIteration){
                        $validValues = self::VALUE_INVALID;
                        $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_NO_FILE];
                    }else{
                        $values[$key] = $clearedIds;
                    }
                } else {
                    if (InnyCMS::fileExists(Denko::trim($val))) {
                        $clearedIds = Denko::trim($val);
                        $values[$key] = $clearedIds;
                    } else if(!empty($val)){
                        $validValues = self::VALUE_INVALID;
                        $return['msg_list'][$key][] = static::$errorMessages[self::VALUE_ERROR_NO_FILE];
                    }
                }
            }
        }

        if($multilang) $clearedIds = json_encode($values);
        $return['value'] = str_replace(" ","",$clearedIds);
        $return['valid'] = $validValues;
        return $return;
    }

    /**
     * Devuelve la preview del tipo de dato
     *
     * @access public
     * @return string
     */
    public function preview($template = "binary-view.tpl"){
        $multilang = $this->getParamValue("multilang");
        $field = $this->getMetadataValue("field");

        $fieldName = ucfirst($this->getMetadataValue('name'));
        $header = "<h3>$fieldName</h3>";

        if ($multilang) {
            $languages = $this->getParamValue("languages");
            $originalField = $field;
            $content = "<hr/>";
            foreach($languages as $key => $lang){
                $field = $originalField. "_$key";
                $fieldName = $lang;

                $smarty = new Smarty();
                $smarty->addTemplateDir("../commons/inny/datatypes/templates");
                $smarty->assign("field",$field);
                $smarty->assign("header",$fieldName);
                $smarty->assign("datatype",$this);
                $smarty->assign("files",explode(",",$this->getValue($key)));
                $content .= $smarty->fetch($template);
            }
            return $header.$content;
        }else{
            $smarty = new Smarty();
            $smarty->addTemplateDir("../commons/inny/datatypes/templates");
            $smarty->assign("field",$field);
            $smarty->assign("header",$header);
            $smarty->assign("datatype",$this);
            $smarty->assign("files",explode(",",$this->getValue()));
            return $smarty->fetch($template);
        }
    }

    /**
     * Retorna el HTML input
     *
     * @access public
     * @return string
     */

    public function htmlInput(){

        $field = $this->getMetadataValue("field");
        $required = $this->getParamValue("required");
        $quantity = $this->getParamValue("quantity");
        $multilang = $this->getParamValue("multilang");

        $requiredText = ($required == true) ? "*" : "";
        $fieldName = ucfirst($this->getMetadataValue('name'));
        $helpText = ucfirst($this->getMetadataValue("help"));

        $htmlInput = "";

        if ($multilang === true) {

            $languages = $this->getParamValue("languages");
            $originalField = $field;
            $htmlInput .= "<div class='form-group m-form__group form-group-$originalField'>";
            $htmlInput .= "<label class='control-label'><h3>$fieldName <span class='text-danger'>$requiredText</span></h3></label>";
            $htmlInput .= "<span id='help-$originalField' data-original-text='$helpText' class='help-block'>$helpText</span><hr/>";
            $htmlInput .= "</div>";

            foreach($languages as $key => $lang) {
                $field = $originalField. "_$key";
                $fieldName = $lang;
                $value = $this->hasValue($key) ? $this->getValue($key) : $this->getDefaultValue();
                $files = !empty($value) ? explode(",",$value) : array();
                $smarty = new Smarty();
                $smarty->addTemplateDir("../commons/inny/datatypes/templates");
                $smarty->assign("field",$field);
                $smarty->assign("fieldName",$fieldName);
                $smarty->assign("requiredText",$requiredText);
                $smarty->assign("helpText","");
                $smarty->assign("value",$value);
                $smarty->assign("files",$files);
                $smarty->assign("quantity",$quantity);
                $htmlInput .= $smarty->fetch("binary-html.tpl");
            }

        }else{
            $value = $this->hasValue() ? $this->getValue() : null;
            $value = str_replace(" ","",$value);
            $files = !empty($value) ? explode(",",$value) : array();
            $smarty = new Smarty();
            $smarty->addTemplateDir("../commons/inny/datatypes/templates");
            $smarty->assign("field",$field);
            $smarty->assign("fieldName",$fieldName);
            $smarty->assign("requiredText",$requiredText);
            $smarty->assign("helpText",$helpText);
            $smarty->assign("value",$value);
            $smarty->assign("files",$files);
            $smarty->assign("quantity",$quantity);
            $htmlInput = $smarty->fetch("binary-html.tpl");
        }

        return $htmlInput;
    }
}