<div class='form-group m-form__group form-group-{$field}'>
    <label class='control-label' for='{$field}'><strong>{$fieldName} <span class='text-danger'>{$requiredText}</span></strong></label>
    {if !empty($disabledParam)}<input type='hidden' name='{$field}' value="{$value}" />{/if}
    <select name='{$field}{if $multipleParam != ""}[]{/if}' id='{$field}' class='form-control' {$requiredParam} {$disabledParam} {$multipleParam}>
        {foreach $options as $key => $val}
            {$selected = ""}
            {if $multipleParam == "" && $key == $value}{$selected = "selected='selected'"}{/if}
            {if $multipleParam != ""}{foreach $value as $k => $v}{if $key == $v}{$selected = "selected='selected'"}{/if}{/foreach}{/if}
            <option value='{$key}' label='{$val}' {$selected}>{$val}</option>";
        {/foreach}
    </select>
    <span id='help-{$field}' data-original-text='{$helpText|default:""}' class='m-form__help'>{$helpText|default:""}</span>
</div>