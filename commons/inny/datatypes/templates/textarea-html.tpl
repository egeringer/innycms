<div class='form-group m-form__group form-group-{$field}'>
    <label class='control-label' for='{$field}'><strong>{$fieldName} <span class='text-danger'>{$requiredText}</span></strong></label>
    <textarea class='form-control' rows='3' id='{$field}' name='{$field}' aria-describedby='help-{$field}' style='resize:vertical;' {$requiredParam} {$disabledParam} {$readonlyParam}>{$value}</textarea>
    <span id='help-{$field}' data-original-text='{$helpText|default:""}' class='m-form__help'>{$helpText|default:""}</span>
</div>