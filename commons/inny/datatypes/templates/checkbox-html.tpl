<div class='form-group m-form__group form-group-{$field}'>
    <label><strong>{$fieldName}  <span class='text-danger'>{$requiredText}</span></strong></label><br/>
    <label class='control-label' for='{$field}'>
        <input type='hidden' name='{$field}' value='false' />
        <input type='checkbox' name='{$field}' id='{$field}' {$checkedText} value='true' />
        {$checkboxText}
    </label>
    <span id='help-{$field}' data-original-text='{$helpText}' class='help-block'>{$helpText}</span>
</div>