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
class InnyType_Richtext extends InnyType_Text {

    /**
     * @var array parametros esperados en la metadata para el tipo con su valor por defecto e indicando si el mismo es requerido o no
     * @static
     * @access protected
     */
    protected static $typeParams =  array(
        'plaintext' => array(
            'required'  => false,
            'default'   => 'true'
        ),
        'toolbar' => array(
            'required'  => false,
            'default'   =>  "{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                            { name: 'forms', groups: [ 'forms' ] },
                            '/',
                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                            { name: 'links', groups: [ 'links' ] },
                            { name: 'insert', groups: [ 'insert' ] },
                            '/',
                            { name: 'styles', groups: [ 'styles' ] },
                            { name: 'colors', groups: [ 'colors' ] },
                            { name: 'tools', groups: [ 'tools' ] },
                            { name: 'others', groups: [ 'others' ] },
                            { name: 'about', groups: [ 'about' ] }"
        ),
        'removeButtons' => array(
            'required'  => false,
            'default'   => "Save,NewPage,Preview,Print,Templates,Scayt,Form,Checkbox,Radio,TextField,Select,Textarea,Button,ImageButton,HiddenField,Subscript,Superscript,CreateDiv,Blockquote,BidiLtr,BidiRtl,Language,Anchor,Flash,SpecialChar,Iframe,ShowBlocks,About,Styles,Smiley,PageBreak"
        )
    );

    /**
     * InnyType_Richtext constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->type = 'richtext';
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

        $requiredText   = ($required == true) ? "*" : "";
        $fieldName      = ucfirst($this->getMetadataValue('name'));
        $helpText       = ucfirst($this->getMetadataValue("help"));

        Denko::smarty_include('vendor/ckeditor/ckeditor.js',false,false,true);
        $toolbar        = $this->getParamValue("toolbar");
        $removeButtons  = $this->getParamValue("removeButtons");
        $plaintext      = $this->getParamValue("plaintext");

        $javascript = "";
        $htmlInput = "";

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

                $smarty = new Smarty();
                $smarty->addTemplateDir("../commons/inny/datatypes/templates");
                $smarty->assign("field",$field);
                $smarty->assign("fieldName",$fieldName);
                $smarty->assign("requiredText",$requiredText);
                $smarty->assign("helpText",$helpText);
                $smarty->assign("value",$value);
                $smarty->assign("requiredParam",$requiredParam);
                $smarty->assign("disabledParam",$disabledParam);
                $smarty->assign("readonlyParam",$readonlyParam);
                $smarty->assign("toolbar",$toolbar);
                $smarty->assign("removeButtons",$removeButtons);
                $smarty->assign("plaintext",$plaintext);

                $htmlInput .= $smarty->fetch("richtext-html.tpl");
            }
        }else{
            $value = $this->hasValue() ? $this->getValue() : $this->getDefaultValue();

            $smarty = new Smarty();
            $smarty->addTemplateDir("../commons/inny/datatypes/templates");
            $smarty->assign("field",$field);
            $smarty->assign("fieldName",$fieldName);
            $smarty->assign("requiredText",$requiredText);
            $smarty->assign("helpText",$helpText);
            $smarty->assign("value",$value);
            $smarty->assign("requiredParam",$requiredParam);
            $smarty->assign("disabledParam",$disabledParam);
            $smarty->assign("readonlyParam",$readonlyParam);
            $smarty->assign("toolbar",$toolbar);
            $smarty->assign("removeButtons",$removeButtons);
            $smarty->assign("plaintext",$plaintext);

            $htmlInput = $smarty->fetch("richtext-html.tpl");
        }

        return $htmlInput.$javascript;

    }
}
################################################################################
?>