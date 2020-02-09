<div class='form-group m-form__group form-group-{$field}'>
    <label class='control-label' for='{$field}'><strong>{$fieldName} <span class='text-danger'>{$requiredText}</span></strong></label>
    <input type='{$type|default:'text'}' class='form-control m-input' id='{$field}' name='{$field}' value='{$value}' aria-describedby='help-{$field}' {$requiredParam} {$disabledParam} {$readonlyParam} {$additionalParams|default:""}/>
    <span id='help-{$field}' data-original-text='{$helpText|default:""}' class='m-form__help'>{$helpText|default:""}</span>
</div>